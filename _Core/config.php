<?php
global $pInfo;
$pInfo["SYSTEM"] = "Art-Club Engine";
/* MAIN SETTINGS */
$pInfo["M_TITLE"]     = "Art Club";
$pInfo["M_CHARSET"]   = "utf-8";
$pInfo["M_DEV_MODE"]  = false;
$pInfo["M_VERSION"]   = "v1.0.0";
$pInfo["M_FAVICON"]   = "images/logo.png";
$pInfo["M_MAIN_CSS"]  = "styles/main.css";
$pInfo["M_SIDE_CSS"]  = "styles/sidebar.css";
$pInfo["M_USER_CSS"]  = "styles/profil.css";
$pInfo["M_IMAGE_CSS"] = "styles/image.css";
$pInfo["M_CMT_CSS"]   = "styles/comments.css";
$pInfo["M_IMAGE"]     = "images/artclub_logo.png";
$pInfo["M_DESCRIPT"]  = "Začínající sociální síť, zaměřená na umění.";
$pInfo["M_KEYWORDS"]  = "Social, Web, Art, Club, ArtClub";
$pInfo["M_AUTHOR"]    = "Domm";
$pInfo["M_MAIL"]      = "noreply@art-club.cz";
/* PAGES */
$pInfo["P_MAIN"]          = "http://art-club.cz";
$pInfo["P_HOME"]          = "index.php?page=home";
$pInfo["P_LOGIN"]         = "index.php?page=login";
$pInfo["P_PASSWORD"]      = "index.php?page=password";
$pInfo["P_LOGIN:FB"]      = "login/facebook.php";
$pInfo["P_LOGOUT"]        = "index.php?page=logout";
$pInfo["P_REGISTER"]      = "index.php?page=register";
$pInfo["P_WALL"]          = "index.php?page=wall";
$pInfo["P_WALL:IMG"]      = "index.php?page=wall&kategorie=obrazky";
$pInfo["P_WALL:MUSIC"]    = "index.php?page=wall&kategorie=hudba";
$pInfo["P_WALL:VIDEO"]    = "index.php?page=wall&kategorie=videa";
$pInfo["P_POST"]          = "index.php?page=post&id=";
$pInfo["P_POST:DELETE"]   = "index.php?page=post&action=delete&id=";
$pInfo["P_PROFILE"]       = "index.php?page=profile";
$pInfo["P_PROFILE:ID"]    = "index.php?page=profile&id=";
$pInfo["P_PODMINKY"]      = "index.php?page=podminky";
$pInfo["P_REPORT:POST"]   = "index.php?page=report&post=";
$pInfo["P_REPORT:USER"]   = "index.php?page=report&user=";
$pInfo["P_MY_SUBS"]       = "index.php?page=subs";
$pInfo["P_EXPLORE"]       = "index.php?page=explore";
$pInfo["P_SUB_USER:OFF"]  = "index.php?page=sub&sub=off&user=";
$pInfo["P_SUB_USER:ON"]   = "index.php?page=sub&sub=on&user=";
$pInfo["P_SEARCH"]        = "index.php?page=search";
$pInfo["P_SEARCH:POST"]   = "index.php?page=search&in=posts&r=";
$pInfo["P_SEARCH:USER"]   = "index.php?page=search&in=user&r=";
/* ADMINISTRATION */
$pInfo["A_MAIN"]          = "http://admin.art-club.cz/is_admin.php";
$pInfo["A_REMOVE_C"]      = "index.php?page=fast_admin&action=comment_remove&id=";
/* FILES */
$pInfo["F_DIR:ORIGINAL"]  = "http://art-club-files.art-club.cz/original/";
$pInfo["F_DIR:EDITED"]    = "http://art-club-files.art-club.cz/edited/";
$pInfo["F_PATH:ORIGINAL"] = "../../subdom/art-club-files/original/";
$pInfo["F_PATH:EDITED"]   = "../../subdom/art-club-files/edited/";
$pInfo["F_IMAGE:WIDTH"]   = 610;
$pInfo["F_IMAGE:HEIGHT"]  = 300;
$pInfo["F_AVATAR"]        = "http://art-club-files.art-club.cz/avatars/";
$pInfo["F_PATH:AVATAR"]   = "../../subdom/art-club-files/avatars/";
$pInfo["F_AVATAR:WIDTH"]  = 90;
$pInfo["F_AVATAR:HEIGHT"] = 90;
/* CAPTHCA */
$pInfo["CAPTCHA"] = "includes/captcha/captcha.php";
?>
