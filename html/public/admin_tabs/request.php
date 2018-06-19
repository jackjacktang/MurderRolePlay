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

function remove_quote($text) {
    $text = str_replace('"', '\"', $text);
    $text = str_replace("'", '&apos;', $text);
    return $text;
}

if ($_POST["tab"] == "background") {
    $bg_story = remove_quote($_POST["bg_story"]);
    $sql = 'INSERT INTO background(id, content) VALUES(0, "'.$bg_story.'") ON DUPLICATE KEY UPDATE content="'.$bg_story.'"';
    $conn->query($sql);

    for ($i = 0; $i < count($_POST["map_ids"]); $i++) {
        $id = $_POST["map_ids"][$i];
        $extension = pathinfo($_FILES["map_images"]["name"][$i], PATHINFO_EXTENSION);
        $description = remove_quote($_POST["map_descriptions"][$i]);

        if ($id < 0) {
            $sql = 'INSERT INTO maps(description, file_path) VALUES("'.$description.'", "")';
            $conn->query($sql);
            $id = $conn->insert_id;
        }
        else {
            $sql = 'UPDATE maps SET description="'.$description.'" WHERE id='.$id;
            $conn->query($sql);
        }

        $target = '../../scripts/'.$_SESSION["script_id"].'/maps/'.$id.'.'.$extension;
        $file_path = '../scripts/'.$_SESSION["script_id"].'/maps/'.$id.'.'.$extension;
        if ($extension == "jpg" || $extension == "png") {
            if (move_uploaded_file($_FILES["map_images"]["tmp_name"][$i], $target)) {
                $sql = 'SELECT file_path FROM maps WHERE id='.$id;
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    if ($orw["file_path"] != "" && $row["file_path"] != $file_path) {
                        unlink("../".$row["file_path"]);
                    }
                }
                $sql = 'UPDATE maps SET file_path="'.$file_path.'" WHERE id='.$id;
                $conn->query($sql);
            }
        }
    }

    for ($i = 0; $i < count($_POST["character_ids"]); $i++) {
        $id = $_POST["character_ids"][$i];
        $username = remove_quote($_POST["character_usernames"][$i]);
        $password = remove_quote($_POST["character_passwords"][$i]);
        $name = remove_quote($_POST["character_names"][$i]);
        $preferred_name = remove_quote($_POST["character_preferred_names"][$i]);
        $description = remove_quote($_POST["character_descriptions"][$i]);
        if ($id > 0) {
            $sql = 'UPDATE characters SET username="'.$username.'", password="'.$password.'", name="'.$name.'", preferred_name="'.$preferred_name.'", description="'.$description.'" WHERE id='.$id;
            $conn->query($sql);
            $sql = 'UPDATE locations SET name="【'.$name.'】的房间" WHERE id='.(-$id);
            $conn->query($sql);
        }
        else {
            $sql = 'INSERT INTO characters(username, password, name, preferred_name, description) VALUES("'.$username.'", "'.$password.'", "'.$name.'", "'.$preferred_name.'", "'.$description.'")';
            $conn->query($sql);
            $id = $conn->insert_id;
            $sql = 'INSERT INTO locations(id, name) VALUES('.(-$id).', "【'.$name.'】的房间")';
            $conn->query($sql);
        }
    }

    if ($_POST["delete_section"] == "character") {
        $sql = 'DELETE FROM characters WHERE id='.$_POST["delete_id"];
        $conn->query($sql);
        $sql = 'DELETE FROM locations WHERE id='.(-$_POST["delete_id"]);
        $conn->query($sql);
    }
    else if ($_POST["delete_section"] == "map") {
        $sql = 'SELECT * FROM maps WHERE id='.$_POST["delete_id"];
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            unlink("../".$row["file_path"]);
        }
        $sql = 'DELETE FROM maps WHERE id='.$_POST["delete_id"];
        $conn->query($sql);
    }
    $conn->close();
    header("Location: ../admin.php?tab=background");
}

if ($_POST["tab"] == "sections") {
    for ($i = 0; $i < count($_POST["section_ids"]); $i++) {
        $id = $_POST["section_ids"][$i];
        $sequence = $_POST["section_sequences"][$i];
        $type = $_POST["section_types"][$i];
        $title = remove_quote($_POST["section_titles"][$i]);
        $chapter = $_POST["section_chapters"][$i];
        if ($id < 0) {
            $sql = 'INSERT INTO sections(sequence, type, title, chapter) VALUES('.$sequence.', '.$type.', "'.$title.'", '.$chapter.')';
            $conn->query($sql);
        }
        else {
            $sql = 'UPDATE sections SET sequence='.$sequence.', type='.$type.', title="'.$title.'", chapter='.$chapter.' WHERE id='.$id;
            $conn->query($sql);
        }
        
    }
        
    $sql = 'DELETE FROM sections WHERE id='.$_POST["delete_id"];
    $conn->query($sql);

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
                $content = remove_quote($_POST["section".$section_id."_content"]);
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
            for ($i = 0; $i < count($_POST["timeline_ids"]); $i++) {
                $id = $_POST["timeline_ids"][$i];
                $hour = $_POST["timeline_hours"][$i];
                $minute = $_POST["timeline_minutes"][$i];
                $content = remove_quote($_POST["timeline_contents"][$i]);
                if ($id > 0) {
                    $sql1 = 'UPDATE timelines SET hour='.$hour.', minute='.$minute.', content="'.$content.'" WHERE id='.$id;
                    $conn->query($sql1);
                }
                else {
                    $sql1 = 'INSERT INTO timelines(character_id, chapter, hour, minute, content) VALUES('.$character_id.', '.$chapter.', '.$hour.', '.$minute.', "'.$content.'")';
                    $conn->query($sql1);
                    echo $sql1;
                }
            }
        }
        if ($row["type"] == 4) {
            for ($i = 0; $i < count($_POST["objective_ids"]); $i++) {
                $id = $_POST["objective_ids"][$i];
                $content = remove_quote($_POST["objective_contents"][$i]);
                $points = $_POST["objective_pointses"][$i];
                if ($id > 0) {
                    $sql1 = 'UPDATE objectives SET points='.$points.', content="'.$content.'" WHERE id='.$id;
                    $conn->query($sql1);
                }
                else {
                    $sql1 = 'INSERT INTO objectives(character_id, chapter, content, points) VALUES('.$character_id.', '.$chapter.', "'.$content.'", '.$points.')';
                    $conn->query($sql1);
                }
            }
        }
    }

    if (isset($_POST["submit1"])) {
        $location_id = $_POST["location_id"];
        $position = remove_quote($_POST["position"]);
        $points = $_POST["points"];
        $self_description = remove_quote($_POST["self_description"]);
        $description = remove_quote($_POST["description"]);
        $unlock_id = $_POST["unlock_id"];
        $unlock_characters = "";
        if (isset($_POST["unlock_characters"])) {
            foreach ($_POST["unlock_characters"] as $unlock_character) {
                $unlock_characters = $unlock_characters.$unlock_character.",";
            }
        }
        if ($_POST["clue_id"] == -1) {
            $sql = 'INSERT INTO clues(chapter, location_id, position, points, self_description, description, unlock_id, unlock_characters) VALUES('.$chapter.', '.$location_id.', "'.$position.'", '.$points.', "'.$self_description.'", "'.$description.'", '.$unlock_id.', "'.$unlock_characters.'")';
            $conn->query($sql);
            $id = $conn->insert_id;
        }
        else {
            $id = $_POST["clue_id"];
        }
        print_r($_FILES);
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

    if (isset($_POST["submit2"])) {
        $sql = 'SELECT file_path FROM clues WHERE id='.$_POST["clue_id"];
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            unlink("../".$row["file_path"]);
        }
        $sql = 'DELETE FROM clues WHERE id='.$_POST["clue_id"];
        $conn->query($sql);
    }

    if ($_POST["delete_section"] == "timeline") {
        $sql = 'DELETE FROM timelines WHERE id='.$_POST["delete_id"];
        $conn->query($sql);
    }
    else if ($_POST["delete_section"] == "objective") {
        $sql = 'DELETE FROM objectives WHERE id='.$_POST["delete_id"];
        $conn->query($sql);
        echo $sql;
    }

    $conn->close();
    header("Location: ../admin.php?tab=scripts&character_id=".$character_id.'&chapter='.$chapter);
}

if ($_POST["tab"] == "locations") {
    for ($id = 1; $id <= $_POST["max_location"]; $id++) {
        if (isset($_POST["location".$id."_name"])) {
            $location_name = remove_quote($_POST["location".$id."_name"]);
            $sql = 'INSERT INTO locations(id, name) VALUES('.$id.', "'.$location_name.'") ON DUPLICATE KEY UPDATE name="'.$location_name.'"';
            $conn->query($sql);
        }
    }

    if (isset($_POST["duplicate"])) {
        $duplicate = 1;
    }
    else {
        $duplicate = 0;
    }
    $sql = 'UPDATE status SET value='.$duplicate.' WHERE id=2';
    $conn->query($sql);

    if (isset($_POST["delete"])) {
        $sql = 'DELETE FROM locations WHERE id='.$_POST["delete"];
        $conn->query($sql);
    }
    $conn->close();
    header("Location: ../admin.php?tab=locations");
}

if ($_POST["tab"] == "clues") {
    $chapter = $_POST["chapter"];
    if (isset($_POST["submit1"])) {
        $location_id = $_POST["location_id"];
        $position = remove_quote($_POST["position"]);
        $points = $_POST["points"];
        $self_description = remove_quote($_POST["self_description"]);
        $description = remove_quote($_POST["description"]);
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

    if (isset($_POST["submit2"])) {
        $sql = 'SELECT file_path FROM clues WHERE id='.$_POST["id"];
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            unlink("../".$row["file_path"]);
        }
        $sql = 'DELETE FROM clues WHERE id='.$_POST["id"];
        $conn->query($sql);
    }

    $conn->close();
    header("Location: ../admin.php?tab=clues&chapter=".$chapter);
}
?>
