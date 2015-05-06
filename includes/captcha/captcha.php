<?php
  session_start();
  header("Content-Type: image/png");                                
  $ttf = "franconi.ttf";    
  $_SESSION["captcha_id"] = NULL;                                                                       
  $rand = rand(20000,50000);
  $_SESSION["captcha_id"] = $rand;                                                      
  $bg = imagecreatefrompng("bg.png");                             
  $color = imagecolorallocate($bg, 255, 255, 255);              
  imagettftext($bg, 11, 10, 5, 20, $color, $ttf, $_SESSION["captcha_id"]); 
  imagepng($bg);                                                 
?>