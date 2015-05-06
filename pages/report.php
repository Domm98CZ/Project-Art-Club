<?php
include "_Core/config.php"; 
if(!EMPTY($_SESSION["USER_ID"]))
{
  include "_Core/database.php";
  if(!EMPTY($_GET["post"]) && is_numeric($_GET["post"]) && isset($_GET["post"]))
  {
    echo "<div class='comment_area'>";  
    echo "    
    <div class='comment-box'>
        <div class='comment-people-pic'><a href='".$pInfo["P_MAIN"] ."/".$pInfo["P_PROFILE:ID"].$_SESSION["USER_ID"]."'><img title='".$_SESSION["USER_DISPLAYNAME"]."' src='".$_SESSION["USER_AVATAR"]."'class='avatar' alt='".$_SESSION["USER_DISPLAYNAME"]." avatar'></a></div>
        <div class='comment-info'>
          <div class='comment-info-head'>
            <div class='comment-info-head-left'><a href='".$pInfo["P_MAIN"] ."/".$pInfo["P_PROFILE:ID"].$_SESSION["USER_ID"]."'>Nahlašuje: ".UserName($_SESSION["USER_ID"])."</a></div>
            <div class='comment-info-head-right'>
              <ul>
                <li></li>
              </ul>
            </div>
          <div class='clear'> </div>
          <div class='comment-place'>
             <form method='post'>
              <h3 style='color:#fff;'>Nahlášení příspěvku <i>".PostName($_GET["post"])."</i></h3><br />
              <textarea class='comment-new' name='text' placeholder='Prosím napište důvod nahlášení.'></textarea><br />
              <input type='submit' name='add_report' value='Nahlásit' class='comment-new-button'>
              </form>
          </div>
          <div class='clear'> </div>";
    if(@$_POST["add_report"] && !EMPTY($_GET["post"]) && !EMPTY($_POST["text"]))
    {
      $report_query = $db->prepare("INSERT INTO `REPORT`(`ID`, `USER_ID`, `TYPE`, `REPORTED_ID`, `TEXT`, `DATE`) VALUES (NULL, ?,?,?,?,?)");
      $report_query->bindValue(1, $_SESSION["USER_ID"]);
      $report_query->bindValue(2, "POST");
      $report_query->bindValue(3, $_GET["post"]);
      $report_query->bindValue(4, strip_tags($_POST["text"]));
      $report_query->bindValue(5, date("m/d/Y H:i"));
      $report_query->execute();
      echo "<h4 style='color:#fff;'>Příspěvek byl nahlášen, děkujeme.</h4>";
    }
    echo "</div>
        </div>
        <div class='clear'> </div>
      </div>
    </div>";
  }
  else if(!EMPTY($_GET["user"]) && isset($_GET["user"]))
  {
    
    echo "<div class='comment_area'>";  
    echo "    
    <div class='comment-box'>
        <div class='comment-people-pic'><a href='".$pInfo["P_MAIN"] ."/".$pInfo["P_PROFILE:ID"].$_SESSION["USER_ID"]."'><img title='".$_SESSION["USER_DISPLAYNAME"]."' src='".$_SESSION["USER_AVATAR"]."'class='avatar' alt='".$_SESSION["USER_DISPLAYNAME"]." avatar'></a></div>
        <div class='comment-info'>
          <div class='comment-info-head'>
            <div class='comment-info-head-left'><a href='".$pInfo["P_MAIN"] ."/".$pInfo["P_PROFILE:ID"].$_SESSION["USER_ID"]."'>Nahlašuje: ".UserName($_SESSION["USER_ID"])."</a></div>
            <div class='comment-info-head-right'>
              <ul>
                <li></li>
              </ul>
            </div>
          <div class='clear'> </div>
          <div class='comment-place'>
             <form method='post'>
              <h3 style='color:#fff;'>Nahlášení uživatele <i>".UserName($_GET["user"])."</i></h3><br />
              <textarea class='comment-new' name='text' placeholder='Prosím napište důvod nahlášení.'></textarea><br />
              <input type='submit' name='add_report' value='Nahlásit' class='comment-new-button'>
              </form>
          </div>
      <div class='clear'> </div>
      ";
    if(@$_POST["add_report"] && !EMPTY($_GET["user"]) && !EMPTY($_POST["text"]))
    {
      $report_query = $db->prepare("INSERT INTO `REPORT`(`ID`, `USER_ID`, `TYPE`, `REPORTED_ID`, `TEXT`, `DATE`, `SHOWED`) VALUES (NULL, ?,?,?,?,?,?)");
      $report_query->bindValue(1, $_SESSION["USER_ID"]);
      $report_query->bindValue(2, "USER");
      $report_query->bindValue(3, $_GET["user"]);
      $report_query->bindValue(4, strip_tags($_POST["text"]));
      $report_query->bindValue(5, date("m/d/Y H:i"));
      $report_query->bindValue(6, 0);
      $report_query->execute();
      echo "<h4 style='color:#fff;'>Uživatel byl nahlášen, děkujeme.</h4>";
    }
    echo "
        </div>
        </div>
        <div class='clear'> </div>
      </div>
    </div>
    ";  
  }
  else echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_HOME"]."'>";
}
else echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_HOME"]."'>"; 
?>