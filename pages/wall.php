<?php
include "_Core/config.php";
if(!EMPTY($_SESSION["USER_ID"])) 
{
  include "_Core/database.php";
  echo "
  <DIV class='more'> 
    <DIV class='inside'>
      <form method='post' enctype='multipart/form-data'>
        <input type='text' class='soubor' name='namee' placeholder='Název díla'><br />
        <input type='file' name='file' class='file'><br>
        <P class='kategorie'><input type='checkbox' name='podminky' value='check'> Souhlasím s <a href='".$pInfo["P_MAIN"]."/".$pInfo["P_PODMINKY"]."' target='_blank'>podmínkami</a> stránky ArtClub.</p>
        <P class='kategorie'><input type='checkbox' name='licence' value='check'> Souhlasím s <a href='http://creativecommons.org/licenses/by-nc-sa/3.0/cz/legalcode' target='_blank'>podmínkami</a> Creative Commons.</p>
        <input type='submit' name='send_file' value='Přidat soubor' class='kategorie'><br /><br /><center><P class='kategorie'>Nebo přidat youtube video</p></center>
        <input type='text' class='soubor' name='youtube' placeholder='URL Youtube Videa'><input type='submit' class='kategorie' name='send_yt' value='Přidat Youtube Video'><br />
      </form>";
  if(@$_POST["send_file"] && !EMPTY($_POST["namee"]))
  {
      if ($_FILES["file"]["error"] > 0) echo "Chyba: ".$_FILES["file"]["error"]."<br>";
      else
      {
        $max_velikost = 100 * 1024 * 1024; 
        $slozka_original = $pInfo["F_PATH:ORIGINAL"];
        $slozka_edit = $pInfo["F_PATH:EDITED"];
        
        $f = Explode(".", $_FILES['file']['name']);
        $new_filename = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).hash("sha512", md5($f[0])).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).".".$f[1]; 
        if ($_FILES['file']['size'] <= $max_velikost && EMPTY($f[2]))
        {
          if($_FILES["file"]["type"] == "image/png" || $_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/jpg" || $_FILES["file"]["type"] == "image/gif" || $_FILES["file"]["type"] == "audio/mp3" || $_FILES["file"]["type"] == "video/mp4")
          {
            if($_FILES["file"]["type"] == "image/png" || $_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/jpg" || $_FILES["file"]["type"] == "image/gif") imagecrop($_FILES['file']['tmp_name'], $new_filename,$_FILES['file']['type'], $pInfo["F_IMAGE:WIDTH"], $pInfo["F_IMAGE:HEIGHT"]);
            move_uploaded_file($_FILES['file']['tmp_name'], $slozka_original.$new_filename); 
            //copy($file, $newfile)
            $query = $db->prepare("INSERT INTO `FILE`(`FILE_ID`, `FILE_NAME`, `USER_ID`, `FILE_PATH`, `FILE_DATE`, `FILE_SHOW`, `FILE_TYPE`) VALUES (NULL,?,?,?,?,?,?)");
            $query->bindValue(1, strip_tags($_POST["namee"]));
            $query->bindValue(2, $_SESSION["USER_ID"]);
            $query->bindValue(3, $new_filename);
            $query->bindValue(4, date("m/d/Y H:i"));
            $query->bindValue(5, "1");
            $query->bindValue(6, $_FILES["file"]["type"]);
            $query->execute();
          }
        }
      }
  } 
  else if(@$_POST["send_yt"] && !EMPTY($_POST["youtube"]))
  {   
      if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $_POST["youtube"], $match)) 
      {
        $video_id = $match[1];
        $videoTitle = file_get_contents("http://gdata.youtube.com/feeds/api/videos/".$video_id."?v=2&fields=title");
        preg_match("/<title>(.+?)<\/title>/is", $videoTitle, $titleOfVideo);
        $videoTitle = $titleOfVideo[1];
        $query = $db->prepare("INSERT INTO `FILE`(`FILE_ID`, `FILE_NAME`, `USER_ID`, `FILE_PATH`, `FILE_DATE`, `FILE_SHOW`, `FILE_TYPE`) VALUES (NULL,?,?,?,?,?,?)");
        $query->bindValue(1, $videoTitle);
        $query->bindValue(2, $_SESSION["USER_ID"]);
        $query->bindValue(3, $video_id);
        $query->bindValue(4, date("m/d/Y H:i"));
        $query->bindValue(5, "1");
        $query->bindValue(6, "video/youtube");
        $query->execute();
      }    
  }                              
  echo "</div></div>";
  echo "<DIV class='sipka'></DIV>";         
  if(EMPTY($_GET["kategorie"])) include "pages/posts.php";
  else if($_GET["kategorie"] == "obrazky") include "pages/posts_img.php";
  else if($_GET["kategorie"] == "hudba") include "pages/posts_music.php";
  else if($_GET["kategorie"] == "videa") include "pages/posts_video.php";   
}
else echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_HOME"]."'>";
?>