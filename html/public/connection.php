<?php
session_start();

// Link to database
$db_host = "localhost";
$db_user = "root";
$db_password = "Lu636593";
$db1 = "rp";
$conn = new mysqli($db_host, $db_user, $db_password, $db1);
if (mysqli_connect_errno()) {
    echo mysqli_connect_error();
}
$conn->set_charset("utf8");
?>