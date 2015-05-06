<?php
/* WEB FUNCTIONS v0.0.8 */
function PageHead()
{
  $pInfo = $GLOBALS["pInfo"];
  $str = NULL;
  $str .= "<!DOCTYPE>\n";
  $str .= "<html>\n";
  $str .= "<head>\n";
  $str .= "<meta http-equiv='content-type' content='text/html; charset=".$pInfo["M_CHARSET"]."'>\n";
  $str .= "<title>".$pInfo["M_TITLE"]."</title>\n";
  $str .= "<script src='//api.html5media.info/1.1.8/html5media.min.js'></script>\n"; 
  $str .= "<script src='//code.jquery.com/jquery-1.10.2.js'></script>\n";
  $str .= "<script type='text/javascript' src='//art-club.cz/includes/js/styles.js'></script>\n";
  //$str .= "<script type='text/javascript' src='//art-club.cz/includes/js/hledani.js'></script>\n";
  $str .= "<link rel='shortcut icon' href='".$pInfo["M_FAVICON"]."'>\n";
  $str .= "<link rel='stylesheet' type='text/css' href='".$pInfo["M_MAIN_CSS"]."'>\n";
  $str .= "<link rel='stylesheet' type='text/css' href='".$pInfo["M_SIDE_CSS"]."'>\n";
  $str .= "<link rel='stylesheet' type='text/css' href='".$pInfo["M_USER_CSS"]."'>\n";
  $str .= "<link rel='stylesheet' type='text/css' href='".$pInfo["M_IMAGE_CSS"]."'>\n";
  $str .= "<link rel='stylesheet' type='text/css' href='".$pInfo["M_CMT_CSS"]."'>\n";
  $str .= "<meta name='robots' content='index,follow' />\n";
  $str .= "<meta name='author' content='".$pInfo["M_AUTHOR"]."' />\n";
  $str .= "<meta name='description' content='".$pInfo["M_DESCRIPT"]."' />\n";
  $str .= "<meta name='keywords' content='".$pInfo["M_KEYWORDS"]."' />\n";
  $str .= "<meta name='identifier-url' content='".$pInfo["P_MAIN"]."' />\n";
  $str .= "<meta name='generator' content='".$pInfo["SYSTEM"]."' />\n";
  $str .= "<meta property='og:url' content='".$pInfo["P_MAIN"]."' />\n";
  $str .= "<meta property='og:image' content='".$pInfo["M_IMAGE"]."' />\n";
  $str .= "<meta property='og:title' content='".$pInfo["M_TITLE"]."' />\n";
  $str .= "<meta property='og:site_name' content='".$pInfo["M_TITLE"]."' />\n";
  $str .= "<meta property='og:type' content='website' />\n";
  $str .= "<script>\n";
  $str .= " (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){ \n";
  $str .= " (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),\n";
  $str .= " m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)\n";
  $str .= " })(window,document,'script','//www.google-analytics.com/analytics.js','ga');\n";
  $str .= "\n";
  $str .= " ga('create', 'UA-46006794-3', 'auto');\n";
  $str .= " ga('send', 'pageview');\n";
  $str .= "</script>\n";
  $str .= "</head>\n";
  return $str;
}

function PageFooter()
{
  $str = NULL;
  $str .= "</html>";
  return $str;
}

function ShowPage($page)
{
  $str = NULL;
  if($page == "posts") include "pages/home.php"; 
  if(file_exists("pages/".$page.".php")) include "pages/".$page.".php";
  else include "pages/home.php"; 
  $str = "\n";
  return $str;
}

function StrMagic($string)
{
  $string = str_replace("\n", "<br />", $string);
  $string = preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","<br /><iframe title='YouTube Player' width='300' height='200' src='http://www.youtube.com/embed/$1?autoplay=0' class='youtube_player' frameborder='0' allowfullscreen></iframe><br />",$string);
  $string = preg_replace("#(^|[\n\s>])([\w]+?://[^\s\"\n\r\t<]*)#is", "\\1<a target='_blank' href=\"\\2\">\\2</a>", $string);
  $string = preg_replace("/([\w-?&;#~=\.\/]+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?))/i","<a href=\"mailto:$1\">$1</a>", $string);
  return $string;
}

function UserInfo($user_id)
{
  $db = $GLOBALS["db"];
  $user_query = $db->prepare("SELECT * FROM `USER` WHERE USER_ID = ? LIMIT 1");
  $user_query->bindValue(1, $user_id);
  $user_query->execute();  
  $user_info = $user_query->fetch();
  $user_query = NULL;
  if($user_info > 0) return $user_info;
  else return null;
}

function UserName($user_id)
{
  $db = $GLOBALS["db"];
  $user_query = $db->prepare("SELECT * FROM `USER` WHERE USER_ID = ? LIMIT 1");
  $user_query->bindValue(1, $user_id);
  $user_query->execute();  
  $user_info = $user_query->fetch();
  $user_query = NULL;
  return $user_info["USER_DISPLAYNAME"];
}

function ShowUserPostOptions($user_id, $post_id, $post_type, $post_path)
{
  $db = $GLOBALS["db"];
  $pInfo = $GLOBALS["pInfo"];
  $str = NULL;
  $file_query = $db->prepare("SELECT * FROM FILE WHERE FILE_ID = ? AND USER_ID = ? LIMIT 1");
  $file_query->bindValue(1, $post_id);
  $file_query->bindValue(2, $user_id);
  $file_query->execute();
  $file_info = $file_query->rowCount();
  
  $str .= "<div class='postbar'><p>";
  $str .= "<span class='prispeveklevo'>";
  $str .= "<a href='".$pInfo["P_MAIN"]."/".$pInfo["P_REPORT:POST"].$post_id."'><img src='images/report_flag_white.png' width='12px' height='16px' title='Nahlásit příspěvek'></a>";
  if($_SESSION["USER_ROLE"] == "MODERATOR" || $_SESSION["USER_ROLE"] == "ADMIN" || $_SESSION["USER_ROLE"] == "MAIN_ADMIN" || $file_info == 1) $str .= " <a href='".$pInfo["P_MAIN"]."/".$pInfo["P_POST:DELETE"].$post_id."'><img src='images/trash_white.png' width='16px' height='16px' title='Smazat Příspěvek'></a>";
  $str .= "</span>";
  $str .= "<span class='prispevekpravo'>";
  if($post_type == "image/png" || $post_type == "image/jpeg" || $post_type == "image/jpg" || $post_type == "image/gif") $str .= "<a href='".$pInfo["F_DIR:ORIGINAL"].$post_path."' target='_blank' class='postfunkce'>Zobrazit v plné velikosti</a>";
  else if($post_type == "audio/mp3") $str .= "";
  else if($post_type == "video/mp4") $str .= "";
  else if($post_type == "video/youtube") $str .= "<a href='https://www.youtube.com/watch?v=".$post_path."' target='_blank' class='postfunkce'>Zobrazit na youtube</a>";
  $str .= "</span>";
  $str .= "</div><br />";
  return $str;
}

function PostName($file_id)
{
  $db = $GLOBALS["db"];
  $file_query = $db->prepare("SELECT * FROM `FILE` WHERE FILE_ID = ? LIMIT 1");
  $file_query->bindValue(1, $file_id);
  $file_query->execute();  
  $file_info = $file_query->fetch();
  $file_query = NULL;
  return $file_info["FILE_NAME"];
}

function GetPageUrl($header = false) { 
    static $pure_url = null; 
    static $html_url = null; 
     
    if (!$pure_url) { 
        $url = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://'); 
        $url .= $_SERVER['SERVER_NAME']; 
        $port = explode(':', $_SERVER['HTTP_HOST']); 
        if (!empty($port[1])) { 
            $url .= ':'.$port[1]; 
        } 
        $url .= $_SERVER['REQUEST_URI']; 
        $pure_url = $url; 
        $html_url = str_replace('&', '&amp;', $pure_url); 
    } 
     
    return $header ? $pure_url : $html_url; 
}

function GenerateUserPass($user, $pass)
{
  $salt = NULL;
  $passhash = NULL;
  $salt = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).$user.rand(0,9).rand(0,9).":".rand(0,9).$pass.rand(0,9).rand(0,9).rand(0,9).rand(0,9);
  $salt = hash("sha512", $salt);
  $passhash = hash("sha512", $pass);
  $salt = hash("sha512", $pass.$salt);
  return $salt; 
}

function imagecrop($img_name,$newname,$type,$modwidth,$modheight)
{
  $pInfo = $GLOBALS["pInfo"];
  $slozka_edit = $pInfo["F_PATH:EDITED"];
  list($width, $height) = getimagesize($img_name) ;
   
  $tn = imagecreatetruecolor($modwidth, $modheight); 
  if(!strcmp("image/png", $type))
  {
    imagealphablending($tn, false); 
    imagesavealpha($tn, true);  
  }   
  if(!strcmp("image/jpg", $type) || !strcmp("image/jpeg",$type)) $src_img = imagecreatefromjpeg($img_name);
  if(!strcmp("image/png", $type)) $src_img = imagecreatefrompng($img_name);
  if(!strcmp("image/gif", $type)) $src_img = imagecreatefromgif($img_name); 
  imagecopyresampled($tn, $src_img, 0, 0, 0, 0, $modwidth, $modheight, $width, $height);    
  if(!strcmp("image/png", $type))  
  {
    imagesavealpha($src_img, true);
    imagepng($tn, $slozka_edit.$newname);
  }
  else if(!strcmp("image/gif",$type)) imagegif($tn, $slozka_edit.$newname);
  else imagejpeg($tn, $slozka_edit.$newname);
} 

function createavatar($img_name,$type,$new_name)
{
  $pInfo = $GLOBALS["pInfo"];
  $slozka_avatar = $pInfo["F_PATH:AVATAR"];
  list($width, $height) = getimagesize($img_name);
  
  if(strcmp("image/jpg", $type) || strcmp("image/jpeg",$type)) $myImage = imagecreatefromjpeg($img_name);
  else if(strcmp("image/png", $type))
  { 
    $myImage = imagecreatefrompng($img_name);
    imagealphablending($myImage, false); 
    imagesavealpha($myImage, true);  
  }
  else if(strcmp("image/gif", $type)) $myImage = imagecreatefromgif($img_name); 
  
  if ($width > $height) {
    $y = 0;
    $x = ($width - $height) / 2;
    $smallestSide = $height;
  } else {
    $x = 0;
    $y = ($height - $width) / 2;
    $smallestSide = $width;
  }

  $avatar_size = 90;
  $avatar = imagecreatetruecolor($avatar_size, $avatar_size);
  imagecopyresampled($avatar, $myImage, 0, 0, $x, $y, $avatar_size, $avatar_size, $smallestSide, $smallestSide);
  if(strcmp("image/jpg", $type) || strcmp("image/jpeg",$type)) imagejpeg($avatar,$slozka_avatar.$new_name); 
  else if(strcmp("image/png", $type)) imagepng($avatar,$slozka_avatar.$new_name); 
  else if(strcmp("image/gif", $type)) imagegif($avatar,$slozka_avatar.$new_name); 
}

function StringFind($haystack, $needle) 
{ 
    $pos = strpos($haystack, $needle); 
    if ($pos !== false) return $pos; 
    else return -1; 
} 
?>