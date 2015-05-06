<?php
include "_Core/config.php"; 
if(!EMPTY($_GET["id"]) || !isset($_GET["id"]) || !is_numeric($_GET["id"])) 
{  
  if(!EMPTY($_SESSION["USER_ID"])) 
  {
    include "_Core/database.php";
    
    if($_GET["action"] == "delete")
    {
      if($_SESSION["USER_ROLE"] == "MODERATOR" || $_SESSION["USER_ROLE"] == "ADMIN" || $_SESSION["USER_ROLE"] == "MAIN_ADMIN")
      {
        $file_q = $db->prepare("SELECT * FROM `FILE` WHERE `FILE_ID` = ? LIMIT 1");
        $file_q->bindValue(1, $_GET["id"]);
        $file_q->execute();
        $file_i = $file_q->fetch();
        
        $file_qd = $db->prepare("DELETE FROM `FILE` WHERE `FILE_ID` = ? LIMIT 1");
        $file_qd->bindValue(1, $_GET["id"]);
        $file_qd->execute();
        
        $comment_qd = $db->prepare("DELETE FROM `COMMENT` WHERE `FILE_ID` = ?");
        $comment_qd->bindValue(1, $_GET["id"]);
        $comment_qd->execute();
        
        $report_qd = $db->prepare("DELETE FROM `REPORT` WHERE TYPE = ? AND REPORTED_ID = ?");
        $report_qd->bindValue(1, "POST");
        $report_qd->bindValue(2, $_GET["id"]);
        $report_qd->execute();
        
        if(file_exists($pInfo["F_DIR:ORIGINAL"].$file_i["FILE_PATH"])) unlink($pInfo["F_DIR:ORIGINAL"].$file_i["FILE_PATH"]);
        if(file_exists($pInfo["F_DIR:EDITED"].$file_i["FILE_PATH"])) unlink($pInfo["F_DIR:EDITED"].$file_i["FILE_PATH"]);
        echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_WALL"]."'>";
      }
      $file_q = $db->prepare("SELECT * FROM FILE WHERE FILE_ID = ? AND USER_ID = ? LIMIT 1");
      $file_q->bindValue(1, $_GET["id"]);
      $file_q->bindValue(2, $_SESSION["USER_ID"]);
      $file_q->execute();
      $file_c = $file_q->rowCount();
      if($file_c == 0) echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_POST"].$_GET["id"]."'>"; 
      else if($file_c == 1)
      {
        $file_qd = $db->prepare("DELETE FROM `FILE` WHERE `FILE_ID` = ? LIMIT 1");
        $file_qd->bindValue(1, $_GET["id"]);
        $file_qd->execute();
        
        $comment_qd = $db->prepare("DELETE FROM `COMMENT` WHERE `FILE_ID` = ?");
        $comment_qd->bindValue(1, $_GET["id"]);
        $comment_qd->execute();
        
        $report_qd = $db->prepare("DELETE FROM `REPORT` WHERE TYPE = ? AND REPORTED_ID = ?");
        $report_qd->bindValue(1, "POST");
        $report_qd->bindValue(2, $_GET["id"]);
        $report_qd->execute();
        
        if(file_exists($pInfo["F_DIR:ORIGINAL"].$file_i["FILE_PATH"])) unlink($pInfo["F_DIR:ORIGINAL"].$file_i["FILE_PATH"]);
        if(file_exists($pInfo["F_DIR:EDITED"].$file_i["FILE_PATH"])) unlink($pInfo["F_DIR:EDITED"].$file_i["FILE_PATH"]);
        echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_WALL"]."'>";
      }  
    }
    
    $file_query = $db->prepare("SELECT * FROM `FILE` WHERE FILE_ID = ? AND FILE_SHOW = ?");
    $file_query->bindValue(1, $_GET["id"]);
    $file_query->bindValue(2, "1");
    $file_query->execute(); 
    $file_info = $file_query->fetch();
    if($file_info > 0)
    {
      $user_info = UserInfo($file_info["USER_ID"]);
      echo "<div class='avatarBG'><a href='".$pInfo["P_MAIN"] ."/".$pInfo["P_PROFILE:ID"].$user_info["USER_ID"]."'><img title='".$user_info["USER_DISPLAYNAME"]."' src='".$user_info["USER_AVATAR"]."'class='avatar' alt='".$user_info["USER_DISPLAYNAME"]." avatar'></a></div>";
      if($file_info["FILE_TYPE"] == "image/png" || $file_info["FILE_TYPE"] == "image/jpeg" || $file_info["FILE_TYPE"] == "image/jpg" || $file_info["FILE_TYPE"] == "image/gif")
      { 
        echo "<div class='imgpost'>";
        echo "<a href='".$pInfo["F_DIR:ORIGINAL"].$file_info["FILE_PATH"]."' target='_blank'><img src='".$pInfo["F_DIR:EDITED"].$file_info["FILE_PATH"]."' class='image'></a>";
        echo "</div>";
        echo ShowUserPostOptions($_SESSION["USER_ID"], $file_info["FILE_ID"], $file_info["FILE_TYPE"], $file_info["FILE_PATH"]);
      }
      else if($file_info["FILE_TYPE"] == "audio/mp3")
      {
        echo "<div class='imgpost'><audio src='".$pInfo["F_DIR:ORIGINAL"].$file_info["FILE_PATH"]."' controls preload class='image'></audio></div>";
        echo ShowUserPostOptions($_SESSION["USER_ID"], $file_info["FILE_ID"], $file_info["FILE_TYPE"], $file_info["FILE_PATH"]); 
      }
      else if($file_info["FILE_TYPE"] == "video/mp4")
      {
        echo "<div class='imgpost'><video src='".$pInfo["F_DIR:ORIGINAL"].$file_info["FILE_PATH"]."' width='320' height='200' controls preload class='image'></video></div>";
        echo ShowUserPostOptions($_SESSION["USER_ID"], $file_info["FILE_ID"], $file_info["FILE_TYPE"], $file_info["FILE_PATH"]);      }
      else if($file_info["FILE_TYPE"] == "video/youtube")
      {
        echo "<div class='imgpost'><iframe class='image' width='320' height='200' src='//www.youtube.com/embed/".$file_info["FILE_PATH"]."' frameborder='0' allowfullscreen></iframe></div>"; 
        echo ShowUserPostOptions($_SESSION["USER_ID"], $file_info["FILE_ID"], $file_info["FILE_TYPE"], $file_info["FILE_PATH"]);
      }
      $comment_str = NULL;    
      $comment_query = $db->prepare("SELECT * FROM `COMMENT` WHERE FILE_ID = ?");
      $comment_query->bindValue(1, $_GET["id"]);
      $comment_query->execute();  
      echo "<div class='comment_area'>";  
      while ($comment_info = $comment_query->fetch(PDO::FETCH_ASSOC))
      {
        //$comment_str .= "<p><i>".$comment_info["COMMENT_DATE"]."</i> | <a href='".$pInfo["P_MAIN"] ."/".$pInfo["P_PROFILE:ID"].$comment_info["USER_ID"]."'>".UserName($comment_info["USER_ID"])."</a>: ".$comment_info["COMMENT_TEXT"]."</p>";
        //$comment_str = StrMagic($comment_str);
        $user_info_comments = UserInfo($comment_info["USER_ID"]);  
        $comment_str .= "
        <div class='comment-box'>
          <div class='comment-people-pic'><a href='".$pInfo["P_MAIN"] ."/".$pInfo["P_PROFILE:ID"].$comment_info["USER_ID"]."'><img title='".$user_info_comments["USER_DISPLAYNAME"]."' src='".$user_info_comments["USER_AVATAR"]."'class='avatar' alt='".$user_info_comments["USER_DISPLAYNAME"]." avatar'></a></div>
          <div class='comment-info'>
            <div class='comment-info-head'>
              <div class='comment-info-head-left'><a href='".$pInfo["P_MAIN"] ."/".$pInfo["P_PROFILE:ID"].$comment_info["USER_ID"]."'>".UserName($comment_info["USER_ID"])."</a></div>
              <div class='comment-info-head-right'>
                <ul>
                  <li><a class='date-of-post'>".$comment_info["COMMENT_DATE"]."</a></li>";
                  if($_SESSION["USER_ROLE"] == "MODERATOR" || $_SESSION["USER_ROLE"] == "ADMIN" || $_SESSION["USER_ROLE"] == "MAIN_ADMIN") $comment_str .= "<li><a class='comment-options' href='".$pInfo["P_MAIN"] ."/".$pInfo["A_REMOVE_C"].$comment_info["COMMENT_ID"]."'>X</a></li>";
        $comment_str .= "       
                </ul>
              </div>
              <div class='clear'> </div>
              <div class='comment-place'><p>".StrMagic($comment_info["COMMENT_TEXT"])."</p></div>
              <div class='clear'> </div>
            </div>
          </div>
          <div class='clear'> </div>
        </div>
        ";        
      }
      if(!EMPTY($comment_str)) echo $comment_str;
      echo "
      <div class='comment-box'>
        <div class='comment-people-pic'><a href='".$pInfo["P_MAIN"] ."/".$pInfo["P_PROFILE:ID"].$_SESSION["USER_ID"]."'><img title='".$_SESSION["USER_DISPLAYNAME"]."' src='".$_SESSION["USER_AVATAR"]."'class='avatar' alt='".$_SESSION["USER_DISPLAYNAME"]." avatar'></a></div>
        <div class='comment-info'>
          <div class='comment-info-head'>
            <div class='comment-info-head-left'><a href='".$pInfo["P_MAIN"] ."/".$pInfo["P_PROFILE:ID"].$_SESSION["USER_ID"]."'>".UserName($_SESSION["USER_ID"])."</a></div>
            <div class='comment-info-head-right'>
              <ul>
                <li></li>
              </ul>
            </div>
          <div class='clear'> </div>
          <div class='comment-place'>
            <form method='post'>
              <input type='hidden' name='file_id' value='".$_GET["id"]."'>
              <textarea class='comment-new' name='comment_text'></textarea>
              <input type='submit' name='send_comment' value='Přidat komentář' class='comment-new-button'>
            </form>
          </div>
          <div class='clear'> </div>
        </div>
        </div>
        <div class='clear'> </div>
      </div>  
      ";
      echo "</div>"; 
      if(@$_POST["send_comment"] && !EMPTY($_POST["file_id"]) && is_numeric($_POST["file_id"]) && !EMPTY($_POST["comment_text"]))
      {
        $file_get_info = $db->prepare("SELECT * FROM FILE WHERE FILE_ID = ? LIMIT 1");
        $file_get_info->bindValue(1, $_POST["file_id"]);
        $file_get_info->execute();  
        $file_info = $file_get_info->fetch(); 
        if($file_info > 0)
        {
          $comment_add = $db->prepare("INSERT INTO `COMMENT` (`COMMENT_ID`, `FILE_ID`, `USER_ID`, `COMMENT_TEXT`, `COMMENT_DATE`) VALUES (NULL,?,?,?,?)");
          $comment_add->bindValue(1, $_POST["file_id"]);
          $comment_add->bindValue(2, $_SESSION["USER_ID"]);
          $comment_add->bindValue(3, $_POST["comment_text"]);
          $comment_add->bindValue(4, date("m/d/Y H:i"));
          $comment_add->execute(); 
          echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_POST"].$_POST["file_id"]."'>";    
        }
      }
    }
    else echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_WALL"]."'>";  
  }
  else echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_LOGIN"]."'>";
}
else echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_WALL"]."'>";
?>