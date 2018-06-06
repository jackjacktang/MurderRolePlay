<?php
session_start();
unset($_SESSION["script_id"]);
unset($_SESSION["script_name"]);
header("Location: login.php");
?>
