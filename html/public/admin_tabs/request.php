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

    for ($id = 0; $id <= $_POST["max_character"]; $id++) {
        if (isset($_POST["character".$id."_username"])) {
            $username =  $_POST["character".$id."_username"];
            $password = $_POST["character".$id."_password"];
            $name = $_POST["character".$id."_name"];
            $preferred_name = $_POST["character".$id."_preferred_name"];
            $description = $_POST["character".$id."_description"];
            $points = $_POST["points"];
            $sql = 'INSERT INTO characters(id, username, password, name, preferred_name, description, points) VALUES('.$id.', "'.$username.'", "'.$password.'", "'.$name.'", "'.$preferred_name.'", "'.$description.'", '.$points.') ON DUPLICATE KEY UPDATE username="'.$username.'", password="'.$password.'", name="'.$name.'", preferred_name="'.$preferred_name.'", description="'.$description.'", points='.$points;
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

if ($_POST["tab"] == "sections") {
    for ($id = 1; $id <= $_POST["max_section"]; $id++) {
        if (isset($_POST["section".$id."_chapter"])) {
            $sequence = $_POST["section".$id."_sequence"];
            $type = $_POST["section".$id."_type"];
            $title = $_POST["section".$id."_title"];
            $chapter = $_POST["section".$id."_chapter"];
            $sql = 'INSERT INTO sections(id, sequence, type, title, chapter) VALUES('.$id.', '.$sequence.', '.$type.', "'.$title.'", '.$chapter.') ON DUPLICATE KEY UPDATE id='.$id.', sequence='.$sequence.', type='.$type.', title="'.$title.'", chapter='.$chapter;
            $conn->query($sql);
        }
    }

    if (isset($_POST["delete"])) {
        $sql = 'DELETE FROM sections WHERE id='.$_POST["delete"];
        $conn->query($sql);
    }
    $conn->close();
    header("Location: ../admin.php?tab=sections");
}
?>