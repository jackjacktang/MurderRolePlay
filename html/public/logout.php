<?php
session_start();
unset($_SESSION["character_id"]);
header("Location: login.php");
?>
