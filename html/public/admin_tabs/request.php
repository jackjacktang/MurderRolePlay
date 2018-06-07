<?php
session_start();

$db_host = "localhost";
$db_user = "root";
$db_password = "Lu636593";
$db = "rp_".$_SESSION["script_id"];
$conn = new mysqli($db_host, $db_user, $db_password, $db);
if (mysqli_connect_errno()) {
    echo mysqli_connect_error();
}
$conn->set_charset("utf8");

if ($_POST["tab"] == "background") {
    $sql = "INSERT INTO background(id, content) VALUES(0, '".$_POST["bg_story"]."') ON DUPLICATE KEY UPDATE content='".$_POST["bg_story"]."'";
    $conn->query($sql);

    for ($i = 1; $i < 200; $i++) {
        if (isset($_POST["character".$i."_username"])) {
            $username =  $_POST["character".$i."_username"];
            $password = $_POST["character".$i."_password"];
            $name = $_POST["character".$i."_name"];
            $preferred_name = $_POST["character".$i."_preferred_name"];
            $description = $_POST["character".$i."_description"];
            $points = $_POST["points"];
            $sql = 'INSERT INTO characters(id, username, password, name, preferred_name, description, points) VALUES('.$i.', "'.$username.'", "'.$password.'", "'.$name.'", "'.$preferred_name.'", "'.$description.'", '.$points.') ON DUPLICATE KEY UPDATE username="'.$username.'", password="'.$password.'", name="'.$name.'", preferred_name="'.$preferred_name.'", description="'.$description.'", points='.$points;
            $conn->query($sql);
        }
    }

    if (isset($_POST["delete"])) {
        $sql = 'DELETE FROM characters WHERE id='.$_POST["delete"];
        $conn->query($sql);
    }
    $conn->close();
    header("Location: ../admin.php?tab=background");
}
?>