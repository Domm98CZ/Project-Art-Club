<?php
include "_Core/config.php"; 
if(!EMPTY($_SESSION["USER_ID"])) 
{
  if(!EMPTY($_GET["r"]) && !EMPTY($_GET["in"]))
  {
    include "_Core/database.php";
    //echo "<h1>Výsledky hledání pro: ".$_GET["r"]."</h1>";
    //echo "<a href='".$pInfo["P_MAIN"]."/".$pInfo["P_SEARCH:POST"].$_GET["r"]."'><button>Hledat přispěvky</button></a> <a href='".$pInfo["P_MAIN"]."/".$pInfo["P_SEARCH:USER"].$_GET["r"]."'><button>Hledat uživatele</button></a><br /><br />";
    if($_GET["in"] == "posts")
    {
      $file_count = $db->prepare("SELECT * FROM  `FILE` WHERE FILE_SHOW = ? AND FILE_NAME = ? OR FILE_NAME LIKE ? OR FILE_NAME LIKE ? OR FILE_NAME LIKE ? AND FILE_TYPE = ? AND FILE_TYPE = ? AND FILE_TYPE LIKE ? ORDER BY  `FILE`.`FILE_ID` DESC LIMIT 0 , 50");
      $file_count->bindValue(1, "1");
      $file_count->bindValue(2, "%".$_GET["r"]);
      $file_count->bindValue(3, $_GET["r"]."%");
      $file_count->bindValue(4, "%".$_GET["r"]."%");
      $file_count->bindValue(5, $_GET["r"]);
      $file_count->bindValue(6, "image/%");
      $file_count->bindValue(7, "audio/mp3");
      $file_count->bindValue(8, "video/mp4");
      $file_count->execute();
      $files = $file_count->rowCount();
      if($files > 0)
      {
        echo "<div class='explorearea'>";
        $file_search = $db->prepare("SELECT * FROM  `FILE` WHERE FILE_SHOW = ? AND FILE_NAME = ? OR FILE_NAME LIKE ? OR FILE_NAME LIKE ? OR FILE_NAME LIKE ? ORDER BY  `FILE`.`FILE_ID` DESC LIMIT 0 , 50");
        $file_search->bindValue(1, "1");
        $file_search->bindValue(2, "%".$_GET["r"]);
        $file_search->bindValue(3, $_GET["r"]."%");
        $file_search->bindValue(4, "%".$_GET["r"]."%");
        $file_search->bindValue(5, $_GET["r"]);
        $file_search->execute();
        while ($file_info = $file_search->fetch(PDO::FETCH_ASSOC))
        {
          if($file_info["FILE_SHOW"] == 1 && $file_info["FILE_TYPE"] != "video/youtube")
          {
            echo "<div class='post'>";
            if($file_info["FILE_TYPE"] == "image/png" || $file_info["FILE_TYPE"] == "image/jpeg" || $file_info["FILE_TYPE"] == "image/jpg" || $file_info["FILE_TYPE"] == "image/gif")
            { 
              echo "<a href='".$pInfo["P_MAIN"]."/".$pInfo["P_POST"].$file_info["FILE_ID"]."'><img src='".$pInfo["F_DIR:EDITED"].$file_info["FILE_PATH"]."' class='image'></a>";
            }
            else if($file_info["FILE_TYPE"] == "audio/mp3") echo "<a href='".$pInfo["P_MAIN"]."/".$pInfo["P_POST"].$file_info["FILE_ID"]."'><audio src='".$pInfo["F_DIR:ORIGINAL"].$file_info["FILE_PATH"]."' controls preload class='image'></audio></a>";
            else if($file_info["FILE_TYPE"] == "video/mp4") echo "<a href='".$pInfo["P_MAIN"]."/".$pInfo["P_POST"].$file_info["FILE_ID"]."'><video src='".$pInfo["F_DIR:ORIGINAL"].$file_info["FILE_PATH"]."' width='300' height='200' controls preload class='image'></video></a>";
            //else if($explore_info["FILE_TYPE"] == "video/youtube") echo "<a href='".$pInfo["P_MAIN"]."/".$pInfo["P_POST"].$explore_info["FILE_ID"]."'><iframe class='image' width='300' height='200' src='//www.youtube.com/embed/".$explore_info["FILE_PATH"]."' frameborder='0' allowfullscreen></iframe></a>"; 
            echo "</div>";
          }
        }
        echo "</div>";
      }
    }
    else if($_GET["in"] == "user")
    {   
      echo "<div class='subavatararea'>";
      $user_count = $db->prepare("SELECT * FROM  `USER` WHERE USER_DISPLAYNAME = ? OR USER_DISPLAYNAME = ? OR USER_DISPLAYNAME LIKE ? OR USER_DISPLAYNAME LIKE ? ORDER BY  `USER`.`USER_ID` DESC LIMIT 0 , 50");
      $user_count->bindValue(1, "%".$_GET["r"]);
      $user_count->bindValue(2, $_GET["r"]."%");
      $user_count->bindValue(3, "%".$_GET["r"]."%");
      $user_count->bindValue(4, $_GET["r"]);
      $user_count->execute();
      $users = $user_count->rowCount();
      if($users > 0)
      { 
        $user_search = $db->prepare("SELECT * FROM  `USER` WHERE USER_DISPLAYNAME = ? OR USER_DISPLAYNAME = ? OR USER_DISPLAYNAME LIKE ? OR USER_DISPLAYNAME LIKE ? ORDER BY  `USER`.`USER_ID` DESC LIMIT 0 , 50");
        $user_search->bindValue(1, "%".$_GET["r"]);
        $user_search->bindValue(2, $_GET["r"]."%");
        $user_search->bindValue(3, "%".$_GET["r"]."%");
        $user_search->bindValue(4, $_GET["r"]);
        $user_search->execute();
        while ($user_info = $user_search->fetch(PDO::FETCH_ASSOC))
        {
          echo "<div class='subavatarBG'><a href='".$pInfo["P_MAIN"] ."/".$pInfo["P_PROFILE:ID"].$user_info["USER_ID"]."'><img title='".$user_info["USER_DISPLAYNAME"]."' src='".$user_info["USER_AVATAR"]."' class='avatar' alt='".$user_info["USER_DISPLAYNAME"]." avatar'></a></div>";
        }
        echo "</div>";
      }     
    }
    else echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_WALL"]."'>";
  }
  else
  {
    echo "<div class='subavatararea'>";
    echo "<center>
    <h1 style='color:#fff;'>Vyhledávání</h1>
    <form method='post' id='search'>
    <input class='searcharea_velka' type='text' name='hledany_vyraz'><br /><br />
    <input type='submit' name='hledej_post' class='vyhledat_btn' value='Hledej příspěvky'> <input type='submit' name='hledej_uziv' class='vyhledat_btn' value='Hledej uživatele'>  
    </center></form>
    ";
    echo "</div>";
  
    if(@$_POST["hledej_post"] && !EMPTY($_POST["hledany_vyraz"])) echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_SEARCH:POST"].$_POST["hledany_vyraz"]."'>";
    else if(@$_POST["hledej_uziv"] && !EMPTY($_POST["hledany_vyraz"])) echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_SEARCH:USER"].$_POST["hledany_vyraz"]."'>";
  }
}
else echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_HOME"]."'>";
?>