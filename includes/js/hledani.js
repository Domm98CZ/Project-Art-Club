$(document).ready(function() 
{
  $(function()
  {
    $(".vyhledat").click(function()
    { 
      var nazev = document.getElementById("hledany_vyraz");
      var hodnota = nazev.value;
      var hledany_vyraz = document.getElementById("hledany_vyraz").innerHTML += hodnota;
      window.location.replace("http://art-club.cz/index.php?page=search&in=user&r=" + hledany_vyraz);
    }); 
  });
});