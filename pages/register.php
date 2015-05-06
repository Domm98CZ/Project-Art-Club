<?php 
include "_Core/config.php"; 
if(EMPTY($_SESSION["USER_ID"])) 
{
  include "_Core/database.php";
  echo "
  <form method='post'>
  <DIV class='row'><DIV class='iconBG'><P>Nick</p></DIV><INPUT class='pole' type='text' name='nick'></DIV>
  <DIV class='row'><DIV class='iconBG'><P>Heslo</p></DIV><INPUT class='pole' type='password' name='pass1'></DIV>
  <DIV class='row'><DIV class='iconBG'><P>Heslo znova</p></DIV><INPUT class='pole' type='password' name='pass2'></DIV> 
  <DIV class='row'><DIV class='iconBG'><P>Jmeno</p></DIV><INPUT class='pole' type='text' name='jmeno'></DIV> 
  <DIV class='row'><DIV class='iconBG'><P>Prijmeni</p></DIV><INPUT class='pole' type='text' name='prijmeni'></DIV> 
  <DIV class='row'><DIV class='iconBG'><P>E-Mail</p></DIV><INPUT class='pole' type='text' name='mail'></DIV>       
  <DIV class='row'><input type='submit' name='register' value='Zaregistrovat' class='btn'></DIV> 
  </form>";
  echo "<P class='info2'>Registrací automaticky souhlasíte s <a class='podminky_link' href='http://art-club.cz/index.php?page=podminky' target='_blank'>podmínkami</a> tohoto webu.</P>";

  if(@$_POST["register"])
  {
    if(!EMPTY($_POST["nick"]) && !EMPTY($_POST["pass1"]) && !EMPTY($_POST["pass2"]) && !EMPTY($_POST["jmeno"]) && !EMPTY($_POST["prijmeni"]) && !EMPTY($_POST["mail"]))
    {
      if(filter_var($_POST["mail"], FILTER_VALIDATE_EMAIL)) 
      {
        if($_POST["pass1"] == $_POST["pass2"])
        {
          if(strlen($_POST["nick"]) > 2 && preg_match("/^[_a-zA-Z0-9-]+$/", $_POST["nick"]))
          {
            if(strlen($_POST["pass1"]) > 5)
            {
              $user = $db->prepare("SELECT * FROM USER WHERE USER_NICKNAME = ? LIMIT 1");
              $user->bindValue(1, $_POST["nick"]);
              $user->execute();
              $user_info = $user->fetch(); 
              if($user_info == 0)
              {
                $query = $db->prepare("INSERT INTO `USER`(`USER_ID`, `USER_NICKNAME`, `USER_DISPLAYNAME`, `USER_PASS`, `USER_NAME`, `USER_SURNAME`, `USER_MAIL`, `USER_AVATAR`, `USER_ROLE`, `USER_REGDATE`, `USER_REGTYPE`, `USER_LASTLOGIN`, `USER_IP`, `USER_LASTACTIVE`) VALUES (NULL,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                $query->bindValue(1, $_POST["nick"]);
                $query->bindValue(2, $_POST["jmeno"]." ".$_POST["prijmeni"]);
                $query->bindValue(3, hash("sha512", $_POST["pass1"]));
                $query->bindValue(4, $_POST["jmeno"]);
                $query->bindValue(5, $_POST["prijmeni"]);
                $query->bindValue(6, $_POST["mail"]);
                $query->bindValue(7, "http://files.domm98.cz/noav.png");
                $query->bindValue(8, "USER");
                $query->bindValue(9, date("m/d/Y H:i"));
                $query->bindValue(10, "Web");
                $query->bindValue(11, "NONE");
                $query->bindValue(12, $_SERVER['REMOTE_ADDR']);
                $query->bindValue(13, "NONE");
                $query->execute();  
                $user_query = $db->prepare("SELECT * FROM `USER` WHERE USER_NICKNAME = ? LIMIT 1");
                $user_query->bindValue(1, $_POST["nick"]);
                $user_query->execute();  
                $user_info = $user_query->fetch();

                $sub_query = $db->prepare("INSERT INTO `SUBS` (`ID` ,`USER_ID` ,`SUB_ID` ,`DATE`)VALUES (NULL ,?,?,?), (NULL,?,?,?);");
                $sub_query->bindValue(1, $user_info["USER_ID"]);
                $sub_query->bindValue(2, '1');
                $sub_query->bindValue(3, date("m/d/Y H:i"));    
                $sub_query->bindValue(4, $user_info["USER_ID"]);
                $sub_query->bindValue(5, $user_info["USER_ID"]);
                $sub_query->bindValue(6, date("m/d/Y H:i"));
                $sub_query->execute();  

                echo "<P class='info2'>Registrace dokončena! Nyní se můžeš přihlásit.</P>";
                echo "<meta http-equiv='refresh' content='2;url=".$pInfo["P_MAIN"]."/".$pInfo["P_LOGIN"] ."'>";
              }
              else echo "<P class='info2'>Uživatelské jméno jejiž zabrané!</P>";
            }
            else echo "<P class='info2'>Vaše heslo musí mít minimálně 6 znaků!</P>";
          }
          else echo "<P class='info2'>Vaše jméno musí obsahovat minimálně 3 písmena a nesmí obsahovat diakritiku!</P>";
        }
        else echo "<P class='info2'>Zadaná hesla nesouhlasí!</P>";
      }
      else echo "<P class='info2'>Byl zadán neplatný mail!</P>";
    }
    else echo "<P class='info2'>Nebyla vyplněna všechna pole!</P>";
  }
}
else echo "<P class='info'>Již jsi přihlášen!</P>";
?>	