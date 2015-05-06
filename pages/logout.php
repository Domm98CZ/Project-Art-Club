<?php
session_unset($_SESSION);
echo "<meta http-equiv='refresh' content='0;url=".$pInfo["P_MAIN"]."/".$pInfo["P_HOME"] ."'>";
?>