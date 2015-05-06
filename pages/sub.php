<?php
include "_Core/config.php";
if(!EMPTY($_SESSION["USER_ID"])) 
{
  if(isset($_GET["sub"]) && !EMPTY($_GET["sub"]) && isset($_GET["user"]) && !EMPTY($_GET["user"]) && is_numeric($_GET["user"]))
  {
    include "_Core/database.php";
    if($_GET["sub"] == "on")
    {
      $sub_query = $db->prepare("INSERT INTO `SUBS`(`ID`, `USER_ID`, `SUB_ID`, `DATE`) VALUES (NULL,?,?,?)");
      $sub_query->bindValue(1, $_SESSION["USER_ID"]);
      $sub_query->bindValue(2, $_GET["user"]);
      $sub_query->bindValue(3, date("m/d/Y H:i"));
      $sub_query->execute();
      echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_PROFILE:ID"].$_GET["user"]."'>"; 
    }
    else if($_GET["sub"] == "off")
    {
      $sub_query = $db->prepare("DELETE FROM `SUBS` WHERE USER_ID = ? AND SUB_ID = ? LIMIT 1");
      $sub_query->bindValue(1, $_SESSION["USER_ID"]);
      $sub_query->bindValue(2, $_GET["user"]);
      $sub_query->execute();
      echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_PROFILE:ID"].$_GET["user"]."'>";     
    }
    else echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_HOME"]."'>";
  }
  else echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_HOME"]."'>";
}
else echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_HOME"]."'>";
?>