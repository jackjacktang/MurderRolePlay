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

    for ($id = 0; $id <= $_POST["max_map"]; $id++) {
        if (isset($_POST["map".$id."_description"])) {
            $extension = pathinfo($_FILES["map".$id."_image"]["name"], PATHINFO_EXTENSION);
            $description = $_POST["map".$id."_description"];
            $target = '../../scripts/'.$_SESSION["script_id"].'/maps/'.$id.'.'.$extension;
            $file_path = '../scripts/'.$_SESSION["script_id"].'/maps/'.$id.'.'.$extension;
            if ($extension == "jpg" || $extension == "png") {
                if (move_uploaded_file($_FILES["map".$id."_image"]["tmp_name"], $target)) {
                    $sql = 'SELECT file_path FROM maps WHERE id='.$id;
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        if ($row["file_path"] != $file_path) {
                            unlink("../".$row["file_path"]);
                        }
                    }
                    $sql = 'INSERT INTO maps(id, description, file_path) VALUES('.$id.', "'.$description.'", "'.$file_path.'") ON DUPLICATE KEY UPDATE description="'.$description.'", file_path="'.$file_path.'"';
                    $conn->query($sql);
                }
            }
            $sql = 'UPDATE maps SET description="'.$description.'" WHERE id='.$id;
            $conn->query($sql);
        }
    }

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
            if ($id != 1) {
                $location_name = "【".$name."】的房间";
                $sql = 'INSERT INTO locations(id, name) VALUES('.(-$id).', "'.$location_name.'") ON DUPLICATE KEY UPDATE name="'.$location_name.'"';
                $conn->query($sql);
            }
        }
    }

    if (isset($_POST["delete_character"])) {
        $sql = 'DELETE FROM characters WHERE id='.$_POST["delete_character"];
        $conn->query($sql);
    }
    if (isset($_POST["delete_map"])) {
        $sql = 'SELECT * FROM maps WHERE id='.$_POST["delete_map"];
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            unlink("../".$row["file_path"]);
        }
        $sql = 'DELETE FROM maps WHERE id='.$_POST["delete_map"];
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

if ($_POST["tab"] == "scripts") {
    $character_id = $_POST["character_id"];
    $chapter = $_POST["chapter"];
    $sql = "SELECT * FROM sections WHERE chapter=".$chapter;
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $section_id = $row["id"];
        if ($row["type"] == 1) {
            if (isset($_POST["section".$section_id."_content"])) {
                $content = $_POST["section".$section_id."_content"];
                $sql1 = 'SELECT * FROM character_section WHERE character_id='.$character_id.' AND section_id='.$section_id;
                $result1 = $conn->query($sql1);
                if ($result1->num_rows == 0) {
                    $sql2 = "INSERT INTO character_section(character_id, section_id, content) VALUES(".$character_id.", ".$section_id.", '".$content."')";
                    $conn->query($sql2);
                }
                else {
                    $sql1 = "UPDATE character_section SET content='".$content."' WHERE character_id=".$character_id." AND section_id=".$section_id;
                    $conn->query($sql1);
                }
            }
        }
        if ($row["type"] == 2) {
            for ($id = 1; $id <= $_POST["max_timeline"]; $id++) {
                if (isset($_POST["timeline".$id."_hour"])) {
                    $hour = $_POST["timeline".$id."_hour"];
                    $minute = $_POST["timeline".$id."_minute"];
                    $content = $_POST["timeline".$id."_content"];
                    $sql1 = 'INSERT INTO timelines(id, character_id, chapter, hour, minute, content) VALUES('.$id.', '.$character_id.', '.$chapter.', '.$hour.', '.$minute.', "'.$content.'") ON DUPLICATE KEY UPDATE hour='.$hour.', minute='.$minute.', content="'.$content.'"';
                    $conn->query($sql1);
                }
            }
        }
        if ($row["type"] == 4) {
            for ($id = 1; $id <= $_POST["max_objective"]; $id++) {
                if (isset($_POST["objective".$id."_content"])) {
                    $content = $_POST["objective".$id."_content"];
                    $points = $_POST["objective".$id."_points"];
                    $sql1 = 'INSERT INTO objectives(id, character_id, chapter, content, points) VALUES('.$id.', '.$character_id.', '.$chapter.', "'.$content.'", '.$points.') ON DUPLICATE KEY UPDATE content="'.$content.'", points='.$points;
                    $conn->query($sql1);
                }
            }
        }
    }

    if (isset($_POST["delete_timeline"])) {
        $sql = 'DELETE FROM timelines WHERE id='.$_POST["delete_timeline"];
        $conn->query($sql);
    }
    if (isset($_POST["delete_objective"])) {
        $sql = 'DELETE FROM objectives WHERE id='.$_POST["delete_objective"];
        $conn->query($sql);
    }
    $conn->close();
    header("Location: ../admin.php?tab=scripts&character_id=".$character_id.'&chapter='.$chapter);
}

if ($_POST["tab"] == "locations") {
    for ($id = 1; $id <= $_POST["max_location"]; $id++) {
        if (isset($_POST["location".$id."_name"])) {
            $location_name = $_POST["location".$id."_name"];
            $sql = 'INSERT INTO locations(id, name) VALUES('.$id.', "'.$location_name.'") ON DUPLICATE KEY UPDATE name="'.$location_name.'"';
            $conn->query($sql);
        }
    }

    if (isset($_POST["delete"])) {
        $sql = 'DELETE FROM locations WHERE id='.$_POST["delete"];
        $conn->query($sql);
    }
    $conn->close();
    header("Location: ../admin.php?tab=locations");
}

if ($_POST["tab"] == "clues") {
    if (isset($_POST["submit1"])) {
        $chapter = $_POST["chapter"];
        $location_id = $_POST["location_id"];
        $position = $_POST["position"];
        $points = $_POST["points"];
        $self_description = $_POST["self_description"];
        $description = $_POST["description"];
        $unlock_id = $_POST["unlock_id"];
        $unlock_characters = "";
        if (isset($_POST["unlock_characters"])) {
            foreach ($_POST["unlock_characters"] as $unlock_character) {
                $unlock_characters = $unlock_characters.$unlock_character.",";
            }
        }
        if ($_POST["id"] == -1) {
            $sql = 'INSERT INTO clues(chapter, location_id, position, points, self_description, description, unlock_id, unlock_characters) VALUES('.$chapter.', '.$location_id.', "'.$position.'", '.$points.', "'.$self_description.'", "'.$description.'", '.$unlock_id.', "'.$unlock_characters.'")';
            $conn->query($sql);
            $id = $conn->insert_id;
        }
        else {
            $id = $_POST["id"];
        }
        $extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $target = '../../scripts/'.$_SESSION["script_id"].'/clues/'.$id.'.'.$extension;
        $file_path = '../scripts/'.$_SESSION["script_id"].'/clues/'.$id.'.'.$extension;
        if ($extension == "jpg" || $extension == "png") {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target)) {
                $sql = 'SELECT file_path FROM clues WHERE id='.$id;
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    if ($row["file_path"] != $file_path) {
                        unlink("../".$row["file_path"]);
                    }
                }
                $sql = 'UPDATE clues SET file_path="'.$file_path.'" WHERE id='.$id;
                $conn->query($sql);
            }
        }
        $sql = 'UPDATE clues SET position="'.$position.'", points='.$points.', self_description="'.$self_description.'", description="'.$description.'", unlock_id='.$unlock_id.', unlock_characters="'.$unlock_characters.'" WHERE id='.$id;
        $conn->query($sql);
    }

    $conn->close();
    header("Location: ../admin.php?tab=clues&chapter=".$chapter);
}
?>
