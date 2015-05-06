<?php
include "_Core/config.php";
if(!EMPTY($_SESSION["USER_ID"])) 
{
  include "_Core/database.php";
  if(!EMPTY($_GET["id"]) && is_numeric($_GET["id"]))
  {
    $user_info = UserInfo($_GET["id"]);
    if(!EMPTY($user_info))
    {
      echo "
      <div class='profil_avatarBG'>
        <img title='".$user_info["USER_DISPLAYNAME"]."' src='".$user_info["USER_AVATAR"]."'class='avatar' alt='".$user_info["USER_DISPLAYNAME"]." avatar'>
      </div> 
      <div class='line_name'></div>
      <div class='user_name'>
        <p class='name'>".$user_info["USER_NAME"]."</p>
      </div> 
      <div class='user_name2'>
        <p class='name'>".$user_info["USER_SURNAME"]."</p>
      </div>
      <div class='user_info'>
            <div id='subscribe'>
      ";
      if($_GET["id"] != $_SESSION["USER_ID"]) 
      {
        $sub_query = $db->prepare("SELECT * FROM `SUBS` WHERE `USER_ID` = ? AND `SUB_ID` = ? LIMIT 1");
        $sub_query->bindValue(1, $_SESSION["USER_ID"]);
        $sub_query->bindValue(2, $_GET["id"]);
        $sub_query->execute();
        $sub_info = $sub_query->fetch();
        if($sub_info["SUB_ID"] == $_GET["id"]) echo "<a href='".$pInfo["P_MAIN"]."/".$pInfo["P_SUB_USER:OFF"].$_GET["id"]."'><img src='images/krizek.png' width='12px' height='16px' title='Zrušit odběr'><p>Zrušit odběr</p></a>";
        else echo "<a href='".$pInfo["P_MAIN"]."/".$pInfo["P_SUB_USER:ON"].$_GET["id"]."'><img src='images/plus.png' title='Přidat odběr'><p>Odebírat</p></a>";
      }
      echo "
      </div>
      <table>
           <tr><th>Email:</th><td><a href='mailto:".$user_info["USER_MAIL"]."'>".$user_info["USER_MAIL"]."</a></td></tr>
           <tr><th>Registrován:</th><td>".$user_info["USER_REGDATE"]."</td></tr>
           <tr><th>Přihlášen:</th><td>".$user_info["USER_LASTLOGIN"]."</td></tr>";           
           if($_GET["id"] != $_SESSION["USER_ID"])  echo "<tr><th> </th><td><a href='".$pInfo["P_MAIN"]."/".$pInfo["P_REPORT:USER"].$_GET["id"]."'><img src='images/report_flag_white.png' width='12px' height='16px' title='Nahlásit uživatele'></a></td></tr>";    
      echo "
      </table>  
      <div class='line_name2'></div>  
      </div>    
      ";
      echo "</div>";
      $file_query = $db->prepare("SELECT * FROM `FILE` WHERE FILE_SHOW = ? AND USER_ID = ? ORDER BY `FILE`.`FILE_ID` DESC LIMIT 0 , 20");
      $file_query->bindValue(1, "1");
      $file_query->bindValue(2, $_GET["id"]);
      $file_query->execute();  
      $str = NULL;
      while ($file_info = $file_query->fetch(PDO::FETCH_ASSOC))
      { 
        $str .= "<div class='imgpost'>";
        if($file_info["FILE_TYPE"] == "image/png" || $file_info["FILE_TYPE"] == "image/jpeg" || $file_info["FILE_TYPE"] == "image/jpg" || $file_info["FILE_TYPE"] == "image/gif")
        { 
          $str .= "<div class='grid'><figure class='effect-apollo'>";
          $str .= "<a href='".$pInfo["P_MAIN"]."/".$pInfo["P_POST"].$file_info["FILE_ID"]."'><img src='".$pInfo["F_DIR:EDITED"].$file_info["FILE_PATH"]."' class='image'></a>";
          $str .= "<figcaption><h2>".$file_info["FILE_NAME"]."</h2></figcaption>";
          $str .= "</figure></div>";
        }
        else if($file_info["FILE_TYPE"] == "audio/mp3") $str .= "<audio src='".$pInfo["F_DIR:ORIGINAL"].$file_info["FILE_PATH"]."' controls preload class='image'></audio>";
        else if($file_info["FILE_TYPE"] == "video/mp4") $str .= "<video src='".$pInfo["F_DIR:ORIGINAL"].$file_info["FILE_PATH"]."' width='320' height='200' controls preload class='image'></video>";  
        else if($file_info["FILE_TYPE"] == "video/youtube") $str .= "<iframe class='image' width='320' height='200' src='//www.youtube.com/embed/".$file_info["FILE_PATH"]."' frameborder='0' allowfullscreen></iframe>";  
        $str .= "</div>";  
        $str .= "<div class='postbar'><p><span class='prispeveklevo'><a href='".$pInfo["P_MAIN"]."/".$pInfo["P_REPORT:POST"].$file_info["FILE_ID"]."'><img src='images/report_flag_white.png' width='12px' height='16px' title='Nahlásit příspěvek'></a></span><span class='prispevekpravo'><a href='".$pInfo["P_MAIN"]."/".$pInfo["P_POST"].$file_info["FILE_ID"]."' class='postfunkce'>Zobrazit více</a></span></p></div> 
        <p class='cas'>".$file_info["FILE_DATE"]."</p><hr><br /><br />";
      }   
      if(!EMPTY($str)) echo $str; 
      $user_info = NULL;
    }
    else echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_PROFILE"]."'>";
  }
  else 
  {
    $user_info = UserInfo($_SESSION["USER_ID"]); 
    echo "
    <div class='imgpost'>
    <h1>Můj profil</h1>
    <a href='".$pInfo["P_MAIN"]."/".$pInfo["P_PROFILE:ID"].$_SESSION["USER_ID"]."'>Zobrazit profil jako veřejnost</a><br />
    <a href='".$pInfo["P_MAIN"]."/".$pInfo["P_LOGOUT"]."'>Odhlásit se</a><br />
    </div><br />";
    
    echo "
    <div class='imgpost'>
    <h1>Nastavení zobrazovaného jména</h1>
    <form method='post'>
    Aktuální zobrazované jméno: <input type='text' value='".$_SESSION["USER_DISPLAYNAME"]."' readonly><br />
    Nastavit zobrazované jméno pomocí návrhu:
    <select name='navrh'>
    <option selected disabled></option>
    <option value='1'>".$_SESSION["USER_NAME"]." ".$_SESSION["USER_SURNAME"]."</option>
    <option value='2'>".$_SESSION["USER_SURNAME"]." ".$_SESSION["USER_NAME"]."</option>";
    if($_SESSION["USER_REGTYPE"] != "Facebook") echo "<option value='3'>".$_SESSION["USER_NICKNAME"]."</option>";
    echo "</select>
    <input type='submit' name='select_navrh' value='Použít návrh'><br />
    Nebo si napište vlastní: <input type='text' name='display_name'>
    <input type='submit' name='select_dname' value='Použít vlastní'><br />
    </form>";
    if(@$_POST["select_navrh"] && !EMPTY($_POST["navrh"]))
    {
      $update_query = $db->prepare("UPDATE `USER` SET `USER_DISPLAYNAME`= ? WHERE USER_ID = ?");
      if($_POST["navrh"] == 1 || $_POST["navrh"] == "1")
      {
        $update_query->bindValue(1, $_SESSION["USER_NAME"]." ".$_SESSION["USER_SURNAME"]);
        $_SESSION["USER_DISPLAYNAME"] = $_SESSION["USER_NAME"]." ".$_SESSION["USER_SURNAME"];
      }
      else if($_POST["navrh"] == 2 || $_POST["navrh"] == "2") 
      {
        $update_query->bindValue(1, $_SESSION["USER_SURNAME"]." ".$_SESSION["USER_NAME"]);
        $_SESSION["USER_DISPLAYNAME"] = $_SESSION["USER_SURNAME"]." ".$_SESSION["USER_NAME"];
      }
      else if($_SESSION["USER_REGTYPE"] != "Facebook" && $_POST["navrh"] == 3 || $_POST["navrh"] == "3" && $_SESSION["USER_REGTYPE"] != "Facebook")  
      {
        $update_query->bindValue(1, $_SESSION["USER_NICKNAME"]);
        $_SESSION["USER_DISPLAYNAME"] = $_SESSION["USER_NICKNAME"];
      }
      $update_query->bindValue(2, $_SESSION["USER_ID"]);
      $update_query->execute();
      echo "Zobrazované jméno bylo úspěšně změněno."; 
      echo "<meta http-equiv='refresh' content='2;url=".$pInfo["P_MAIN"]."/".$pInfo["P_PROFILE"]."'>";  
    }
    else if(@$_POST["select_dname"] && !EMPTY($_POST["display_name"]))
    {
      $update_query = $db->prepare("UPDATE `USER` SET `USER_DISPLAYNAME`= ? WHERE USER_ID = ?");
      $update_query->bindValue(1, strip_tags($_POST["display_name"]));
      $update_query->bindValue(2, $_SESSION["USER_ID"]);
      $update_query->execute();
      $_SESSION["USER_DISPLAYNAME"] = strip_tags($_POST["display_name"]);
      echo "Zobrazované jméno bylo úspěšně změněno."; 
      echo "<meta http-equiv='refresh' content='2;url=".$pInfo["P_MAIN"]."/".$pInfo["P_PROFILE"]."'>";        
    }
    echo "</div><br />";
    
    echo "
    <div class='imgpost'>
    <h1>Nastavení uživatelského hesla</h1>
    <form method='post'>
    Staré heslo: <input type='password' name='old_pass'><br /> 
    Nové heslo: <input type='password' name='new_pass1'><br /> 
    Nové heslo (Znovu): <input type='password' name='new_pass2'><br /> 
    <input type='submit' name='change_pass' value='Změnit heslo'>
    </form>";
    if(@$_POST["change_pass"] && !EMPTY($_POST["old_pass"]) && !EMPTY($_POST["new_pass1"]) && !EMPTY($_POST["new_pass2"]))
    {  
      if(hash("sha512", $_POST["old_pass"]) == $_SESSION["USER_PASS"])
      {
        if($_POST["new_pass1"] == $_POST["new_pass2"])
        {
          $update_query = $db->prepare("UPDATE `USER` SET `USER_PASS`= ? WHERE USER_ID = ?");
          $update_query->bindValue(1, hash("sha512", $_POST["new_pass2"]));
          $update_query->bindValue(2, $_SESSION["USER_ID"]);
          $update_query->execute();
          $_SESSION["USER_PASS"] = hash("sha512", $_POST["new_pass2"]);
          echo "Heslo změněno.";
          echo "<meta http-equiv='refresh' content='2;url=".$pInfo["P_MAIN"]."/".$pInfo["P_PROFILE"]."'>"; 
        }
        else echo "Zadaná hesla nesouhlasí.";
      }  
      else echo "Špatně zadáno aktuální heslo.";    
    }
    echo "</div><br />";
    
    echo "
    <div class='imgpost'>
    <h1>Nastavení uživatelského emailu</h1>
    <form method='post'>
    Aktuální email <input type='text' value='".$_SESSION["USER_MAIL"]."' readonly><br /> 
    Nový email: <input type='text' name='new_mail'><br /> 
    <input type='submit' name='change_mail' value='Změnit email'>
    </form>";
    if(@$_POST["change_mail"] && !EMPTY($_POST["new_mail"]))
    {  
      if(filter_var($_POST["new_mail"], FILTER_VALIDATE_EMAIL)) 
      {
        $update_query = $db->prepare("UPDATE `USER` SET `USER_MAIL`= ? WHERE USER_ID = ?");
        $update_query->bindValue(1, strip_tags($_POST["new_mail"]));
        $update_query->bindValue(2, $_SESSION["USER_ID"]);
        $update_query->execute();
        $_SESSION["USER_MAIL"] = strip_tags($_POST["new_mail"]);
        echo "Email změněn.";
        echo "<meta http-equiv='refresh' content='2;url=".$pInfo["P_MAIN"]."/".$pInfo["P_PROFILE"]."'>"; 
      }  
      else echo "Toto není platný email.";    
    }
    echo "</div><br />";
    
    echo "
    <div class='imgpost'>
    <h1>Nastavení avatara</h1>
    <p>Nahrávejte pouze obrázky v rozlišení 90x90 nebo větší!</p>
    <form method='post' enctype='multipart/form-data'>
    Aktuální avatar <img src='".$_SESSION["USER_AVATAR"]."' width='".$pInfo["F_AVATAR:WIDTH"]."px' height='".$pInfo["F_AVATAR:HEIGHT"]."px'><br /> 
    Avatara můžete nastavit obrázkem z URL adresy: <input type='text' name='avatar_url'><br />
    Nebo také nahráním souboru s avatarem: <input type='file' name='file'><br />";
    echo "
    <input type='submit' name='change_avatar' value='Nastavit avatara'>
    </form>";
    if(@$_POST["change_avatar"] && !EMPTY($_POST["avatar_url"]))
    {  
      if(filter_var($_POST["avatar_url"], FILTER_VALIDATE_URL) && StringFind($_POST["avatar_url"], ".png") || 
         filter_var($_POST["avatar_url"], FILTER_VALIDATE_URL) && StringFind($_POST["avatar_url"], ".jpg") || 
         filter_var($_POST["avatar_url"], FILTER_VALIDATE_URL) && StringFind($_POST["avatar_url"], ".jpeg") || 
         filter_var($_POST["avatar_url"], FILTER_VALIDATE_URL) && StringFind($_POST["avatar_url"], ".gif"))
      {  
        list($width, $height) = getimagesize($_POST["avatar_url"]);
        if($width >= 90 || $height >= 90)
        {
          //createavatar($img_name,$type,$new_name)
          $type = NULL;
          $new_filename = NULL;
          
          if(StringFind($_POST["avatar_url"], ".png"))  $type = "image/png";
          else if(StringFind($_POST["avatar_url"], ".jpg") || StringFind($_POST["avatar_url"], ".jpeg")) $type = "image/jpg";
          else if(StringFind($_POST["avatar_url"], ".gif")) $type = "image/gif";
            
          $new_filename = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).hash("sha512", md5($_POST["avatar_url"])).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).".png"; 
          $new_filename = md5($new_filename).".png";
          createavatar($_POST["avatar_url"],$type,$new_filename);       
          
          $update_query = $db->prepare("UPDATE `USER` SET `USER_AVATAR`= ? WHERE USER_ID = ?");
          $update_query->bindValue(1, $pInfo["F_AVATAR"].$new_filename);
          $update_query->bindValue(2, $_SESSION["USER_ID"]);
          $update_query->execute();
          $_SESSION["USER_AVATAR"] = $pInfo["F_AVATAR"].$new_filename;    
          echo "Avatar změněn.";
          echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_PROFILE"]."'>";
        } 
        else echo "Avatar je příliš malý!";
      }  
      else echo "Toto není platná url adresa.";    
    }
    else if(@$_POST["change_avatar"] && !EMPTY($_FILES["file"]))
    {
      if ($_FILES["file"]["error"] > 0) echo "Chyba: ".$_FILES["file"]["error"]."<br>";
      else
      {
        $max_velikost = 50 * 1024 * 1024;         
        $f = Explode(".", $_FILES['file']['name']);
        $new_filename = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).hash("sha512", md5($f[0])).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).".".$f[1]; 
        $new_filename = md5($new_filename).".png";
        if ($_FILES['file']['size'] <= $max_velikost && EMPTY($f[2]))
        {
          if($_FILES["file"]["type"] == "image/png" || $_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/jpg" || $_FILES["file"]["type"] == "image/gif")
          {
            list($width, $height) = getimagesize($_FILES['file']['tmp_name']);
            if($width >= 90 || $height >= 90)
            {
              createavatar($_FILES['file']['tmp_name'],$_FILES['file']['type'],$new_filename); 
              $update_query = $db->prepare("UPDATE `USER` SET `USER_AVATAR`= ? WHERE USER_ID = ?");
              $update_query->bindValue(1, $pInfo["F_AVATAR"].$new_filename);
              $update_query->bindValue(2, $_SESSION["USER_ID"]);
              $update_query->execute();
              $_SESSION["USER_AVATAR"] = $pInfo["F_AVATAR"].$new_filename;
              echo "Avatar změněn.";
              echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_PROFILE"]."'>"; 
            }
            else echo "Avatar je příliš malý!";
          }
          else echo "Nepodporovaný typ souboru.";
        }
        else echo "Avatar je příliš velký.";
      }
    }
    echo "</div>";
  }
}
else echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_LOGIN"]."'>";
?>
