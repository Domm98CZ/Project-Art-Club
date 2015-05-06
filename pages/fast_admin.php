<?php
include "_Core/config.php"; 
if(!EMPTY($_SESSION["USER_ID"]))
{
  if($_SESSION["USER_ROLE"] == "MODERATOR" || $_SESSION["USER_ROLE"] == "ADMIN" || $_SESSION["USER_ROLE"] == "MAIN_ADMIN")
  { 
    if(!EMPTY($_GET["action"]))
    {
      include "_Core/database.php"; 
      if($_GET["action"] == "comment_remove" && !EMPTY($_GET["id"]) && is_numeric($_GET["id"]))
      {
        $comment_qd = $db->prepare("DELETE FROM `COMMENT` WHERE `COMMENT_ID` = ? LIMIT 1");
        $comment_qd->bindValue(1, $_GET["id"]);
        $comment_qd->execute();
      }
    }
    echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_HOME"]."'>"; 
  }
  else echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_HOME"]."'>"; 
}
else echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_HOME"]."'>"; 
?>