<?php 
include "_Core/config.php"; 
if(EMPTY($_SESSION["USER_ID"])) 
{
  include "_Core/database.php";
  ?>
  <form method='post'>
  <DIV class='row2'><DIV class='iconBG2'><IMG src='images/usermini.png'></DIV><INPUT class='pole2' type='text' name='nick'></DIV> 
  <DIV class='row2'><DIV class='iconBG2'><IMG src='images/lockmini.png'></DIV><INPUT class='pole2' type='password' name='pass'></DIV> 
  <DIV class='row3'><input type='submit' value='Přihlásit se' name='login' class='btn2'></DIV>
  </form>
  <DIV class='row2'><a href='<?php echo $pInfo["P_MAIN"]."/".$pInfo["P_LOGIN:FB"];?>'><img src='images/fb_login.png' alt='Facebook Login'></a></DIV>
  <P class='info'>
    Ještě nejste členem? <a href='<?php echo $pInfo["P_MAIN"]."/".$pInfo["P_REGISTER"];?>'>Zaregistrujte se</a>!<br />
    Zapomněli jste heslo? <a href='<?php echo $pInfo["P_MAIN"]."/".$pInfo["P_PASSWORD"]?>'>Obnovte si jej</a>!<br />
  </P> 

  <?php
  if(@$_POST["login"])
  {
    if(!EMPTY($_POST["nick"]) && !EMPTY($_POST["pass"]))                                                                                    
    {
      $query = $db->prepare("SELECT * FROM USER WHERE USER_NICKNAME = ? AND USER_PASS = ? LIMIT 1");
      $query->bindValue(1, $_POST["nick"]);
      $query->bindValue(2, hash("sha512", $_POST["pass"]));
      $query->execute();
      $user_info = $query->fetch(); 
      if($user_info > 0)
      {
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
        $_SESSION["USER_ADMIN_KEY"]   = $user_info["USER_ADMIN_KEY"];
        
        $setinfo = $db->prepare("UPDATE `USER` SET `USER_LASTLOGIN`= ?,`USER_LASTACTIVE`= ?,`USER_IP` = ? WHERE USER_ID = ?");
        $setinfo->bindValue(1, $_SESSION["USER_LASTLOGIN"]);
        $setinfo->bindValue(2, date("m/d/Y H:i"));  
        $setinfo->bindValue(3, $_SERVER['REMOTE_ADDR']);  
        $setinfo->bindValue(4, $_SESSION["USER_ID"]);     
        $setinfo->execute(); 
        $_SESSION["USER_LASTACTIVE"]  = date("m/d/Y H:i");
        echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_WALL"]."'>";
      }
      else echo "<br /><P class='info'>Špatné údaje!</P>";
    }
  }
}
?>
