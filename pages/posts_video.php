<?php
include "_Core/config.php"; 
if($_SESSION["USER_ID"])
{
  include "_Core/database.php";
  $count_query = $db->prepare("SELECT SUB_ID FROM `SUBS` WHERE USER_ID = ? ORDER BY `SUBS`.`ID`");
  $count_query->bindValue(1, $_SESSION["USER_ID"]);
  $count_query->execute();  
  $count = $count_query->rowCount();
  if($count > 2)
  {
    $subs_query = $db->prepare("SELECT * FROM `SUBS` WHERE USER_ID = ? ORDER BY `SUBS`.`ID`");
    $subs_query->bindValue(1, $_SESSION["USER_ID"]);
    $subs_query->execute();  
    $sql_command = "SELECT * FROM `FILE` WHERE `FILE_SHOW` = 1 AND `USER_ID` = ".$_SESSION["USER_ID"]." OR `USER_ID` = 1";
    while ($subs_info = $subs_query->fetch(PDO::FETCH_ASSOC)) $sql_command .= " OR `USER_ID` = ".$subs_info["SUB_ID"]."";
    $sql_command .= " ORDER BY `FILE`.`FILE_ID` DESC";

    $file_query = $db->prepare($sql_command);
    $file_query->execute();  
    while ($file_info = $file_query->fetch(PDO::FETCH_ASSOC))
    {
      if($file_info["FILE_SHOW"] == "1")
      {
        if($file_info["FILE_TYPE"] == "video/mp4")
        {
          $user_info = UserInfo($file_info["USER_ID"]);
          echo "<div class='avatarBG'><a href='".$pInfo["P_MAIN"] ."/".$pInfo["P_PROFILE:ID"].$user_info["USER_ID"]."'><img title='".$user_info["USER_DISPLAYNAME"]."' src='".$user_info["USER_AVATAR"]."'class='avatar' alt='".$user_info["USER_DISPLAYNAME"]." avatar'></a></div>";
          echo "<div class='imgpost'><video src='".$pInfo["F_DIR:ORIGINAL"].$file_info["FILE_PATH"]."' width='320' height='200' controls preload class='image'></video></div>";
          echo "  
          <div class='postbar'><p><span class='prispeveklevo'><a href='".$pInfo["P_MAIN"]."/".$pInfo["P_REPORT:POST"].$file_info["FILE_ID"]."'><img src='images/report_flag_white.png' width='12px' height='16px' title='Nahlásit příspěvek'></a></span><span class='prispevekpravo'><a href='".$pInfo["P_MAIN"]."/".$pInfo["P_POST"].$file_info["FILE_ID"]."' class='postfunkce'>Zobrazit více</a></span></p></div> 
          <p class='cas'>".$file_info["FILE_DATE"]."</p> 
          <hr>";    
        }  
        else if($file_info["FILE_TYPE"] == "video/youtube")
        {
          $user_info = UserInfo($file_info["USER_ID"]);
          echo "<div class='avatarBG'><a href='".$pInfo["P_MAIN"] ."/".$pInfo["P_PROFILE:ID"].$user_info["USER_ID"]."'><img title='".$user_info["USER_DISPLAYNAME"]."' src='".$user_info["USER_AVATAR"]."'class='avatar' alt='".$user_info["USER_DISPLAYNAME"]." avatar'></a></div>";
          echo "<div class='imgpost'><iframe class='image' width='320' height='200' src='//www.youtube.com/embed/".$file_info["FILE_PATH"]."' frameborder='0' allowfullscreen></iframe></div>";       
          echo "  
          <div class='postbar'><p><span class='prispeveklevo'><a href='".$pInfo["P_MAIN"]."/".$pInfo["P_REPORT:POST"].$file_info["FILE_ID"]."'><img src='images/report_flag_white.png' width='12px' height='16px' title='Nahlásit příspěvek'></a></span><span class='prispevekpravo'><a href='".$pInfo["P_MAIN"]."/".$pInfo["P_POST"].$file_info["FILE_ID"]."' class='postfunkce'>Zobrazit více</a></span></p></div> 
          <p class='cas'>".$file_info["FILE_DATE"]."</p> 
          <hr>";    
        }  
      }
    }  
  }
  else
  {
    $user_info = UserInfo("1");
    echo "<div class='avatarBG'><a href='".$pInfo["P_MAIN"] ."/".$pInfo["P_PROFILE:ID"].$user_info["USER_ID"]."'><img title='".$user_info["USER_DISPLAYNAME"]."' src='".$user_info["USER_AVATAR"]."'class='avatar' alt='".$user_info["USER_DISPLAYNAME"]." avatar'></a></div>";
    echo "<div class='imgpost'><br /><br /><br />
    Jejda, vypadáto, že ještě nikoho nesleduješ. Klikni do horního pravého rohu na <img src='http://art-club.cz/images/sidemenu_icon.png'> a vyhledej si svého oblíbeného umělce, nebo klikni na X a prozkoumej kdo všechno zde je.
    </div>";
    echo "<hr>";  
  }
}
?>                  