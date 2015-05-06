<?php
include "_Core/database.php";
include "_Core/config.php";
if(EMPTY($_GET["user"]) || EMPTY($_GET["key"]))
{
  echo "
      <form method='post'>
        <p class='info'>Zadejte jméno nebo mail, nové heslo bude zasláno prostřednictvím mailu.</p>
        <DIV class='row'><DIV class='iconBG'><P>Nick</p></DIV><INPUT class='pole' type='text' name='nick'></DIV>
        <DIV class='row'><DIV class='iconBG'><P>Mail</p></DIV><INPUT class='pole' type='text' name='mail'></DIV>
        <DIV class='row'><input type='submit' class='btn' name='send_new_pass' value='Zaslat nové heslo'></DIV>
      </form>
  ";
  
  if(@$_POST["send_new_pass"])
  {  
    $abc1 = range("A", "Z");
    $abc2 = range("a", "z");
    $typ = rand(1, 3);
    $code = NULL;
    if($typ == 1) $code = $abc1[rand(0, count($abc1))].$abc1[rand(0, count($abc1))].$abc1[rand(0, count($abc1))].rand(0,9).rand(0,9).$abc2[rand(0, count($abc2))].rand(0,9).$abc1[rand(0, count($abc1))].$abc2[rand(0, count($abc2))].$abc2[rand(0, count($abc2))].rand(0,9).$abc1[rand(0, count($abc1))].rand(0,9).$abc2[rand(0, count($abc2))].$abc2[rand(0, count($abc2))].rand(0,9).$abc1[rand(0, count($abc1))].rand(0,9);
    else if($typ == 2) $code = rand(0,9).rand(0,9).$abc1[rand(0, count($abc1))].$abc2[rand(0, count($abc2))].rand(0,9).$abc1[rand(0, count($abc1))].rand(0,9).$abc1[rand(0, count($abc1))].rand(0,9).$abc2[rand(0, count($abc2))].$abc2[rand(0, count($abc2))].rand(0,9).$abc1[rand(0, count($abc1))].$abc1[rand(0, count($abc1))].rand(0,9).rand(0,9).$abc2[rand(0, count($abc2))].$abc2[rand(0, count($abc2))];
    else if($typ == 3) $code = $abc2[rand(0, count($abc2))].$abc2[rand(0, count($abc2))].$abc1[rand(0, count($abc1))].rand(0,9).$abc2[rand(0, count($abc2))].$abc2[rand(0, count($abc2))].$abc1[rand(0, count($abc1))].rand(0,9).rand(0,9).$abc2[rand(0, count($abc2))].$abc1[rand(0, count($abc1))].$abc1[rand(0, count($abc1))].$abc1[rand(0, count($abc1))].rand(0,9).rand(0,9).$abc2[rand(0, count($abc2))].$abc1[rand(0, count($abc1))].$abc1[rand(0, count($abc1))];

    if(!EMPTY($_POST["nick"]))
    {
      $query = $db->prepare("SELECT * FROM USER WHERE USER_NICKNAME = ? LIMIT 1");
      $query->bindValue(1, $_POST["nick"]);
      $query->execute();
      $user_info = $query->fetch(); 
      if($user_info > 0)
      {
        $odkaz = $pInfo["P_MAIN"]."/".$pInfo["P_PASSWORD"]."&user=".$user_info["USER_ID"]."&key=".$code;
        $insert_key = $db->prepare("INSERT INTO `ZAPOMENUTE_HESLO`(`ID_PASS`, `USER_ID`, `TIME`, `EXPIRE_TIME`, `KEY`) VALUES (NULL,?,?,?,?)");
        $insert_key->bindValue(1, $user_info["USER_ID"]);
        $insert_key->bindValue(2, time());
        $insert_key->bindValue(3, time()+600);
        $insert_key->bindValue(4, $code);
        $insert_key->execute();
        
        /* EMAIL SETTINGS */
        $user_name = $user_info["USER_DISPLAYNAME"];
        $user_mail = $user_info["USER_MAIL"];
        $from = $pInfo["M_MAIL"];
        $from_name = $pInfo["M_TITLE"];
        $subject = $pInfo["M_TITLE"]." - Zapomenuté heslo";
        
        $message = "
        <!DOCTYPE>
          <html>
            <head>
            <meta http-equiv='content-type' content='text/html; charset=utf-8'>
            </head>
            <body>
            Drahý ".$user_name.",<br />
            Přišla nám žádost o změnu tvého hesla, pokud jej chcete opravdu změnit,<br /> 
            klikněte na následující odkaz a změňte si jej.<br /> 
            <b>Pozor, odkaz má omezenou dobu platnosti, použijte jej co nejdříve.</b><br />
            <a href='".$odkaz."' target='_blank'>".$odkaz."</a><br />
            Pokud jsi ovšem o změnu hesla nepožádal, neklikej na odkaz, a nech<br />
            to být.<br />
            <br />
            Hezký zbytek dne přeje tým ".$pInfo["M_TITLE"].".<br /> 
            </body>
          </html>  
        ";
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'To: '.$user_name.' <'.$user_mail.'>' . "\r\n";
        $headers .= 'From: '.$from_name.' <'.$from.'>' . "\r\n";
        $headers .= 'Cc: '.$from.'' . "\r\n";
        $headers .= 'Bcc: '.$from.'' . "\r\n";
        mail($user_mail, $subject, $message, $headers);   
        echo "<p class='info'>Odkaz pro změnu hesla byl zaslán na mail.</p>";   
      }
      else echo "<p class='info'>Účet s tímto přihlašovacím jménem neexistuje.</p>"; 
    }
    else if(!EMPTY($_POST["mail"]))
    {
      $query = $db->prepare("SELECT * FROM USER WHERE USER_MAIL = ? LIMIT 1");
      $query->bindValue(1, $_POST["mail"]);
      $query->execute();
      $user_info = $query->fetch(); 
      if($user_info > 0)
      {
        $odkaz = $pInfo["P_MAIN"]."/".$pInfo["P_PASSWORD"]."&user=".$user_info["USER_ID"]."&key=".$code;
        
        $insert_key = $db->prepare("INSERT INTO `ZAPOMENUTE_HESLO`(`ID_PASS`, `USER_ID`, `TIME`, `EXPIRE_TIME`, `KEY`) VALUES (NULL,?,?,?,?)");
        $insert_key->bindValue(1, $user_info["USER_ID"]);
        $insert_key->bindValue(2, time());
        $insert_key->bindValue(3, time()+600);
        $insert_key->bindValue(4, $code);
        $insert_key->execute();
        
        /* EMAIL SETTINGS */
        $user_name = $user_info["USER_DISPLAYNAME"];
        $user_mail = $user_info["USER_MAIL"];
        $from = $pInfo["M_MAIL"];
        $from_name = $pInfo["M_TITLE"];
        $subject = $pInfo["M_TITLE"]." - Zapomenuté heslo";
        
        $message = "
        <!DOCTYPE>
          <html>
            <head>
            <meta http-equiv='content-type' content='text/html; charset=utf-8'>
            </head>
            <body>
            Drahý ".$user_name.",<br />
            Přišla nám žádost o změnu tvého hesla, pokud jej chcete opravdu změnit,<br /> 
            klikněte na následující odkaz a změňte si jej.<br /> 
            <b>Pozor, odkaz má omezenou dobu platnosti, použijte jej co nejdříve.</b><br />
            <a href='".$odkaz."' target='_blank'>".$odkaz."</a><br />
            Pokud jsi ovšem o změnu hesla nepožádal, neklikej na odkaz, a nech<br />
            to být.<br />
            <br />
            Hezký zbytek dne přeje tým ".$pInfo["M_TITLE"].".<br /> 
            </body>
          </html>  
        ";
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'To: '.$user_name.' <'.$user_mail.'>' . "\r\n";
        $headers .= 'From: '.$from_name.' <'.$from.'>' . "\r\n";
        $headers .= 'Cc: '.$from.'' . "\r\n";
        $headers .= 'Bcc: '.$from.'' . "\r\n";
        mail($user_mail, $subject, $message, $headers); 
        echo "<p class='info'>Odkaz pro změnu hesla byl zaslán na mail.</p>";        
      } 
      else echo "<p class='info'>Tento mail není spojen s žádným účtem.</p>";  
    }
    else echo "<p class='info'>Musíte vyplnit alespoň jedno pole.</p>";
  }

}
else if(!EMPTY($_GET["user"]) && !EMPTY($_GET["key"]) && is_numeric($_GET["user"]) && preg_match('/[a-zA-Z0-9]/', $_GET["key"]))
{
  $overeni_kodu = $db->prepare("SELECT * FROM `ZAPOMENUTE_HESLO` WHERE `USER_ID` = ? AND `KEY` = ? LIMIT 1");
  $overeni_kodu->bindValue(1, $_GET["user"]);
  $overeni_kodu->bindValue(2, $_GET["key"]);
  $overeni_kodu->execute();
  $over = $overeni_kodu->fetch();
  if($over > 0)
  {
    if($over["EXPIRE_TIME"] > time())
    {
      $user_info = UserInfo($_GET["user"]);
      echo "
      <form method='post'>
        <p class='info'>Změna zapomenutého hesla</p>
        <DIV class='row'><DIV class='iconBG'><P>Nick</p></DIV><INPUT class='pole' type='text' name='nick' readonly value='".$user_info["USER_NICKNAME"]."'></DIV>
        <DIV class='row'><DIV class='iconBG'><P>Heslo</p></DIV><INPUT class='pole' type='password' name='pass'></DIV>
        <DIV class='row'><DIV class='iconBG'><P>Heslo znovu</p></DIV><INPUT class='pole' type='password' name='pass2'></DIV>
        <DIV class='row'><input type='submit' class='btn' name='new_pass' value='Změnit heslo'></DIV>
      </form>
      ";
      if(@$_POST["new_pass"])
      {
        if(!EMPTY($_POST["pass"]) && !EMPTY($_POST["pass2"]))
        {
          if($_POST["pass"] == $_POST["pass2"])
          {
            $setnewPass = $db->prepare("UPDATE `USER` SET `USER_PASS`= ? WHERE USER_ID = ? LIMIT 1");
            $setnewPass->bindValue(1, hash("sha512", $_POST["pass"]));
            $setnewPass->bindValue(2, $_GET["user"]);      
            $setnewPass->execute(); 
            
            $smazklic = $db->prepare("DELETE FROM `ZAPOMENUTE_HESLO` WHERE USER_ID = ?");
            $smazklic->bindValue(1, $_GET["user"]);
            $smazklic->execute();
                        
            echo "<p class='info'>Heslo bylo úspěšně změněno, budete automaticky přesměrování k <a href='".$pInfo["P_MAIN"]."/".$pInfo["P_LOGIN"]."'>přihlášení</a>.</p>";
            echo "<meta http-equiv='refresh' content='2;url=".$pInfo["P_MAIN"]."/".$pInfo["P_LOGIN"]."'>";        
          }
          else echo "<p class='info'>Zadaná hesla musí být stejná.</p>";
        }
        else echo "<p class='info'>Své heslo musíte zadat 2x.</p>";
      }
    }
    else echo "<p class='info'>Odkaz již ztratil platnost, pokud chcete nové heslo, <a href='".$pInfo["P_MAIN"]."/".$pInfo["P_PASSWORD"]."'>vygenerujte si nový</a>.</p>"; 
  }       
  else echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_LOGIN"]."'>";
}
else echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_LOGIN"]."'>";
?>