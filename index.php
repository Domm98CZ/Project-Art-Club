<?php 
session_start();
include "_Core/main.php";

echo PageHead();
if($pInfo["M_DEV_MODE"] == true) error_reporting(-1);
else if($pInfo["M_DEV_MODE"] == false) error_reporting(0); 

echo "<body>\n"; 
if(!EMPTY($_SESSION["USER_ID"])) 
{
  if($_SESSION["USER_ROLE"] == "BANNED") ShowPage("banned");
  echo "
  <div id='side_fix'>
    <div id='sidebar_panel'>   
        <form method='post' id='search'>
          <input class='searcharea' type='text' name='hledany_vyraz'>
          <button name='vyhledavani' class='vyhledat'><img src='".$pInfo["P_MAIN"]."/images/lupa.png' alt='Hledaci lupa' title='Hledej'></button>
        </form>
        <div id='user_bar'>
          <a href='".$pInfo["P_MAIN"]."/".$pInfo["P_PROFILE"]."'><IMG src='".$_SESSION["USER_AVATAR"]."' alt='".$_SESSION["USER_DISPLAYNAME"]." Avatar'></a>
          <ul>
            <li><a href='".$pInfo["P_MAIN"]."/".$pInfo["P_PROFILE:ID"].$_SESSION["USER_ID"]."'>Můj Profil</a></li>
            <li><a href='".$pInfo["P_MAIN"]."/".$pInfo["P_PROFILE"]."'>Nastavení</A></li> 
            <li><a href='".$pInfo["P_MAIN"]."/".$pInfo["P_LOGOUT"]."'>Odhlásit se</a></li> 
          </ul>  
        </div>
        <div id='subscribe_bar'>
          <ul>
            <li><a>Mé odběry</a></li>
          </ul>
          <ul class='odbery'>";     
            $count_query = $db->prepare("SELECT SUB_ID FROM `SUBS` WHERE USER_ID = ?");
            $count_query->bindValue(1, $_SESSION["USER_ID"]);
            $count_query->execute();  
            $count = $count_query->rowCount();
            if($count > 0 )
            {
              $subs_query = $db->prepare("SELECT SUB_ID FROM `SUBS` WHERE USER_ID = ? ORDER BY `SUBS`.`ID` DESC");
              $subs_query->bindValue(1, $_SESSION["USER_ID"]);
              $subs_query->execute();   
              while ($subs_info = $subs_query->fetch(PDO::FETCH_ASSOC))
              {
                if($subs_info["SUB_ID"] != $_SESSION["USER_ID"])
                {
                  $user_info = UserInfo($subs_info["SUB_ID"]);
                  echo "<li><a href='".$pInfo["P_MAIN"] ."/".$pInfo["P_PROFILE:ID"].$user_info["USER_ID"]."'><img title='".$user_info["USER_DISPLAYNAME"]."' src='".$user_info["USER_AVATAR"]."' alt='".$user_info["USER_DISPLAYNAME"]." avatar'><span class='jmenoodberu'>".$user_info["USER_DISPLAYNAME"]."</span></a></li>";
                }
              }
            }
            else echo "<li><a href='".$pInfo["P_MAIN"] ."/".$pInfo["P_EXPLORE"]."'>Nemáš žádné odběry</a></li>";
            echo "</ul><ul>";
            echo "
            <li><a href='".$pInfo["P_MAIN"] ."/".$pInfo["P_MY_SUBS"]."'>Zobrazit všechny odběry</a></li>
            <li><a href='".$pInfo["P_MAIN"] ."/".$pInfo["P_EXPLORE"]."'>Prozkoumat</a></li>
            <li><a href='".$pInfo["P_MAIN"] ."/".$pInfo["P_SEARCH"]."'>Vyhledávání</a></li>";
            if($_SESSION["USER_ROLE"] == "MODERATOR" || $_SESSION["USER_ROLE"] == "ADMIN" || $_SESSION["USER_ROLE"] == "MAIN_ADMIN") echo "<li><a href='".$pInfo["A_MAIN"]."?admin=".$_SESSION["USER_NICKNAME"]."&key=".$_SESSION["USER_ADMIN_KEY"]."'>Administrace</a></li>";
            echo "  
          </ul> 
        </div>
    </div>
  </div>
  <div class='cara_panel'>
  <div class='cara_panel_left'><div id='toggleside'><img src='images/sidemenu_icon.png' alt='Side Menu'></div> <span class='text'>".$pInfo["M_TITLE"]." ".$pInfo["M_VERSION"]."</span></div>
  <div id='cara_panel_midle'>
    <div id='malemenu'></div> 
  </div>";
  echo "<div class='cara_panel_right'></div>\n
  </div>\n";
}
echo "
  <div class='headerBG'></div>\n  
  <div class='cara'></div>\n";  

if(!EMPTY($_SESSION["USER_ID"])) 
{
  echo " 
    <div class='menuBG'><div class='menu'><a href='".$pInfo["P_MAIN"]."/".$pInfo["P_WALL"]."'><img src='images/logo.png' class='img'></a></div></div>\n          
    <div class='menuBG2'><div class='menu'><a href='".$pInfo["P_MAIN"]."/".$pInfo["P_WALL:MUSIC"]."'><img src='images/music.png' class='img'></a></div></div>\n 
    <div class='menuBG3'><div class='menu'><a href='".$pInfo["P_MAIN"]."/".$pInfo["P_WALL:VIDEO"]."'><img src='images/video.png' class='img'></a></div></div>\n  
    <div class='menuBG4'><div class='menu'><a href='".$pInfo["P_MAIN"]."/".$pInfo["P_WALL:IMG"]."'><img src='images/image.png' class='img'></a></div></div>\n   
    <div class='menuBG5'><div class='menu'><a href='".$pInfo["P_MAIN"]."/".$pInfo["P_PROFILE"]."'><img src='images/user.png' class='img'></a></div></div>\n 
  ";
  $query = $db->prepare("SELECT * FROM USER WHERE USER_ID = ? AND USER_NAME = ? AND USER_PASS = ? AND USER_IP = ? LIMIT 1");
  $query->bindValue(1, $_SESSION["USER_ID"]);
  $query->bindValue(2, $_SESSION["USER_NAME"]);
  $query->bindValue(3, $_SESSION["USER_PASS"]);
  $query->bindValue(4, $_SERVER["REMOTE_ADDR"]);
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
    $_SESSION["USER_ADMIN_KEY"]   = $user_info["USER_ADMIN_KEY"];
    
    $setinfo = $db->prepare("UPDATE `USER` SET `USER_LASTACTIVE`= ? WHERE USER_ID = ?");
    $setinfo->bindValue(1, date("m/d/Y H:i"));  
    $setinfo->bindValue(2, $_SESSION["USER_ID"]);     
    $setinfo->execute(); 
  }   
  else ShowPage("logout");
}
else echo "<DIV class='menuBG'><DIV class='menu'><a href='".$pInfo["P_MAIN"]."/".$pInfo["P_HOME"]."'><img src='images/logo.png' class='img'></a></DIV></DIV>\n";
if($_GET["page"] == "index") $_GET["page"] = "home";
if(isset($_GET["page"]) && !EMPTY($_GET["page"]) && !EMPTY($_SESSION["USER_ID"])) ShowPage($_GET["page"]);
else if(EMPTY($_SESSION["USER_ID"]) && !EMPTY($_GET["page"]) && $_GET["page"] == "register") ShowPage("register"); 
else if(EMPTY($_SESSION["USER_ID"]) && !EMPTY($_GET["page"]) && $_GET["page"] == "login") ShowPage("login"); 
else if(EMPTY($_SESSION["USER_ID"]) && !EMPTY($_GET["page"]) && $_GET["page"] == "podminky") ShowPage("podminky"); 
else if(EMPTY($_SESSION["USER_ID"]) && !EMPTY($_GET["page"]) && $_GET["page"] == "password") ShowPage("password"); 
else if(EMPTY($_SESSION["USER_ID"]) && !EMPTY($_GET["page"]) && $_GET["page"] == "home") ShowPage("login"); 
else if(EMPTY($_SESSION["USER_ID"]) && EMPTY($_GET["page"])) ShowPage("login"); 
else ShowPage("wall");
echo "<div class='scroll'>ʌ</div>";
echo "</body>\n";
echo PageFooter();

if(isset($_POST["vyhledavani"]) && !EMPTY($_POST["hledany_vyraz"])) echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_SEARCH:POST"].$_POST["hledany_vyraz"]."'>";
?>
