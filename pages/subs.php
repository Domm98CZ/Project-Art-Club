<?php
include "_Core/config.php"; 
if(!EMPTY($_SESSION["USER_ID"])) 
{
  include "_Core/database.php";
  echo "<div class='subavatararea'>";
  $count_query = $db->prepare("SELECT SUB_ID FROM `SUBS` WHERE USER_ID = ? ORDER BY `SUBS`.`ID`");
  $count_query->bindValue(1, $_SESSION["USER_ID"]);
  $count_query->execute();  
  $count = $count_query->rowCount();
  if($count > 2)
  {
    $subs_query = $db->prepare("SELECT * FROM `SUBS` WHERE USER_ID = ? ORDER BY `SUBS`.`ID` DESC");
    $subs_query->bindValue(1, $_SESSION["USER_ID"]);
    $subs_query->execute();  
    while ($subs_info = $subs_query->fetch(PDO::FETCH_ASSOC)) 
    {   
      if($subs_info["SUB_ID"] != $_SESSION["USER_ID"])
      {
        $user_info = UserInfo($subs_info["SUB_ID"]);
        echo "<div class='subavatarBG'><a href='".$pInfo["P_MAIN"] ."/".$pInfo["P_PROFILE:ID"].$user_info["USER_ID"]."'><img title='".$user_info["USER_DISPLAYNAME"]."' src='".$user_info["USER_AVATAR"]."' class='avatar' alt='".$user_info["USER_DISPLAYNAME"]." avatar'></a></div>";
      }
    } 
  }
  else echo "Nemáš žádné odběry.";
  echo "</div>";
}
else echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_HOME"]."'>";
?>