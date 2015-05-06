<?php
include "_Core/config.php"; 
if(!EMPTY($_SESSION["USER_ID"])) 
{
  include "_Core/database.php";
  echo "<div class='explorearea'>";
  $explore_query = $db->prepare("SELECT * FROM  `FILE` WHERE FILE_SHOW = ? AND FILE_TYPE != ? ORDER BY  `FILE`.`FILE_ID` DESC LIMIT 0 , 50");
  $explore_query->bindValue(1, "1");
  $explore_query->bindValue(2, "video/youtube");
  $explore_query->execute();
  while ($explore_info = $explore_query->fetch(PDO::FETCH_ASSOC))
  {
    echo "<div class='post'>";
    if($explore_info["FILE_TYPE"] == "image/png" || $explore_info["FILE_TYPE"] == "image/jpeg" || $explore_info["FILE_TYPE"] == "image/jpg" || $explore_info["FILE_TYPE"] == "image/gif")
    { 
      echo "<a href='".$pInfo["P_MAIN"]."/".$pInfo["P_POST"].$explore_info["FILE_ID"]."'><img src='".$pInfo["F_DIR:EDITED"].$explore_info["FILE_PATH"]."' class='image'></a>";
    }
    else if($explore_info["FILE_TYPE"] == "audio/mp3") echo "<a href='".$pInfo["P_MAIN"]."/".$pInfo["P_POST"].$explore_info["FILE_ID"]."'><audio src='".$pInfo["F_DIR:ORIGINAL"].$explore_info["FILE_PATH"]."' controls preload class='image'></audio></a>";
    else if($explore_info["FILE_TYPE"] == "video/mp4") echo "<a href='".$pInfo["P_MAIN"]."/".$pInfo["P_POST"].$explore_info["FILE_ID"]."'><video src='".$pInfo["F_DIR:ORIGINAL"].$explore_info["FILE_PATH"]."' width='300' height='200' controls preload class='image'></video></a>";
    //else if($explore_info["FILE_TYPE"] == "video/youtube") echo "<a href='".$pInfo["P_MAIN"]."/".$pInfo["P_POST"].$explore_info["FILE_ID"]."'><iframe class='image' width='300' height='200' src='//www.youtube.com/embed/".$explore_info["FILE_PATH"]."' frameborder='0' allowfullscreen></iframe></a>"; 
    echo "</div>";
  }
  echo "</div>";
}
else echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_HOME"]."'>";
?>