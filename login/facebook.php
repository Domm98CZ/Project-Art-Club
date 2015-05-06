<?php
include "../_Core/database.php";
include "../_Core/config.php";
session_start();
$app_id = "";
$app_secret = "";
$my_url = $pInfo["P_MAIN"]."/".$pInfo["P_LOGIN:FB"];  

$code = $_REQUEST["code"];

if(empty($code)) 
{
  $_SESSION['state'] = md5(uniqid(rand(), TRUE)); // CSRF protection
  $dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" 
  .$app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
  .$_SESSION['state'] . "&scope=publish_stream,email";
  
  
  echo("<script> top.location.href='" . $dialog_url . "'</script>");
}
if($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state'])) 
{
  $token_url = "https://graph.facebook.com/oauth/access_token?"
       . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
       . "&client_secret=" . $app_secret . "&code=" . $code;

  $response = file_get_contents($token_url);
  $params = null;
  parse_str($response, $params);
  $longtoken=$params['access_token'];
  //echo $longtoken;
  $json = file_get_contents("https://graph.facebook.com/me?access_token=".$longtoken);
  $fb_user = json_decode($json, true);
  if(!EMPTY($fb_user)) 
  {
    //print_r($fb_user);
    $select_query = $db->prepare("SELECT * FROM USER WHERE USER_NICKNAME = ? AND USER_PASS = ? LIMIT 1");
    $select_query->bindValue(1, $fb_user["id"]);
    $select_query->bindValue(2, hash("sha512", $fb_user["id"]));
    $select_query->execute();
    $data = $select_query->fetch(); 
    if($data == 0)
    {
      $insert_query = $db->prepare("INSERT INTO `USER`(`USER_ID`, `USER_NICKNAME`, `USER_DISPLAYNAME`, `USER_PASS`, `USER_NAME`, `USER_SURNAME`, `USER_MAIL`, `USER_AVATAR`, `USER_ROLE`, `USER_REGDATE`, `USER_REGTYPE`, `USER_LASTLOGIN`, `USER_IP`, `USER_LASTACTIVE`) VALUES (NULL,?,?,?,?,?,?,?,?,?,?,?,?,?)");
      $insert_query->bindValue(1, $fb_user["id"]); 
      $insert_query->bindValue(2, $fb_user['name']);
      $insert_query->bindValue(3, hash("sha512", $fb_user["id"]));
      $insert_query->bindValue(4, $fb_user["first_name"]);
      $insert_query->bindValue(5, $fb_user["last_name"]);
      $insert_query->bindValue(6, $fb_user["email"]);
      $insert_query->bindValue(7, "http://files.domm98.cz/noav.png");
      $insert_query->bindValue(8, "USER");
      $insert_query->bindValue(9, date("m/d/Y H:i"));
      $insert_query->bindValue(10, "Facebook");
      $insert_query->bindValue(11, "NONE");
      $insert_query->bindValue(12, $_SERVER['REMOTE_ADDR']);
      $insert_query->bindValue(13, date("m/d/Y H:i"));
      $insert_query->execute();   
      
      $user_query = $db->prepare("SELECT * FROM `USER` WHERE USER_NICKNAME = ? LIMIT 1");
      $user_query->bindValue(1, $_POST["nick"]);
      $user_query->execute();  
      $user_info = $user_query->fetch();
                
      $subs_query = $db->prepare("INSERT INTO  `d57129_artclub`.`SUBS` (`ID` ,`USER_ID` ,`SUB_ID` ,`DATE`)VALUES (NULL ,?,?,?), (NULL , ?,?,?);");
      $sub_query->bindValue(1, $user_info["USER_ID"]);
      $sub_query->bindValue(2, '1');
      $sub_query->bindValue(3, date("m/d/Y H:i"));
      $sub_query->bindValue(4, $user_info["USER_ID"]);
      $sub_query->bindValue(5, $user_info["USER_ID"]);
      $sub_query->bindValue(6, date("m/d/Y H:i")); 
    }
    $getinfo_query = $db->prepare("SELECT * FROM USER WHERE USER_NICKNAME = ? AND USER_PASS = ? LIMIT 1");
    $getinfo_query->bindValue(1, $fb_user["id"]);
    $getinfo_query->bindValue(2, hash("sha512", $fb_user["id"]));
    $getinfo_query->execute();
    $user_info = $getinfo_query->fetch(); 
    $_SESSION["USER_ID"]          = $user_info["USER_ID"];
    $_SESSION["USER_NICKNAME"]    = $user_info["USER_NICKNAME"];
    $_SESSION["USER_DISPLAYNAME"] = $user_info["USER_DISPLAYNAME"];
    $_SESSION["USER_PASS"]        = $user_info["USER_PASS"];
    $_SESSION["USER_NAME"]        = $user_info["USER_NAME"];
    $_SESSION["USER_SURNAME"]     = $user_info["USER_SURNAME"];
    $_SESSION["USER_MAIL"]        = $user_info["USER_MAIL"];
    $_SESSION["USER_AVATAR"]      = $user_info["USER_AVATAR"];
    $_SESSION["USER_ROLE"]        = $user_info["USER_ROLE"];
    $_SESSION["USER_REGDATE"]     = $user_info["USER_REGDATE"];
    $_SESSION["USER_REGTYPE"]     = $user_info["USER_REGTYPE"];
    $_SESSION["USER_LASTLOGIN"]   = date("m/d/Y H:i");   
    $_SESSION["USER_LASTACTIVE"]  = date("m/d/Y H:i");
    $_SESSION["USER_ADMIN_KEY"]   = $user_info["USER_ADMIN_KEY"];
    
    $setinfo = $db->prepare("UPDATE `USER` SET `USER_LASTLOGIN`= ?,`USER_IP` = ? WHERE USER_ID = ?");
    $setinfo->bindValue(1, $_SESSION["USER_LASTLOGIN"]);  
    $setinfo->bindValue(2, $_SERVER['REMOTE_ADDR']);  
    $setinfo->bindValue(3, $_SESSION["USER_ID"]);     
    $setinfo->execute(); 
    echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_WALL"]."'>";   
  }
}
?>
