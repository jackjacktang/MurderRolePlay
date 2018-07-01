	<?php
    if ($status <= 2 && $_GET["chapter"] == 2) {
        header("Location: home.php?tab=scripts&chapter=1");
    }
    ?>

    <br><br><br>

    <script type="text/javascript">
        var clue_information = [];
        <?php
        $sql = 'SELECT C.id, C.unlock_id, C.file_path, C.position, C.self_description FROM clues AS C LEFT JOIN locations AS L ON C.location_id=L.id WHERE L.character_id='.$character_id.' AND C.chapter='.$_GET["chapter"].' ORDER BY C.id ASC';
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            if ($row["unlock_id"] > 0) {
                $sql2 = 'SELECT file_path FROM clues WHERE id='.$row["unlock_id"];
                $result2 = $conn->query($sql2);
                while ($row2 = $result2->fetch_assoc()) {
                    $file_path = $row2["file_path"];
                }
            }
            else {
                $file_path = $row["file_path"];
            }
            echo '
        clue_information['.$row["id"].'] = [\''.$row["position"].'\', \''.replace_text($pairs, $row["self_description"]).'\', \''.$file_path.'\'];';
        }
        ?>

        function open_modal(id) {
            document.getElementById("myModal").style.display = "block";
            modal_title.innerHTML = clue_information[id][0];
            var file_path = clue_information[id][2];
            var img = document.getElementById("modal_img");
            if (file_path == "") {
                img.src = "img/alt.png";
            }
            else {
                img.src = file_path;
            }
            modal_content.innerHTML = clue_information[id][1];
        }

        function close_modal() {
            document.getElementById("myModal").style.display = "none";
        }

        window.onclick = function(event) {
            var modal = document.getElementById("myModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

	<?php
	foreach ($sections[$_GET["chapter"] - 1] as $section) {
        echo '
    <section id="section'.$section["id"].'">
        <div class="container">
            <div class="section-top-border">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-8 col-md-9 col-sm-10">
                        <center>
                            <h3 style="margin-bottom: '.($section["sub_title"] != ""? 0:20).'px;">'.$section["title"].'</h3>';

        if ($section["sub_title"] != "") {
            echo '
                            <p>'.$section["sub_title"].'</p>';
        }

        echo '
                        </center>';

        // 公共
        if ($section["type"] == 0) {
            $sql = 'SELECT * FROM character_section WHERE character_id='.$admin.' AND section_id='.$section["id"];
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo replace_text($pairs, $row["content"]);
                echo '
                    </div>';
            }
        }
        // 普通
        else if ($section["type"] == 1) {
            $sql = 'SELECT * FROM character_section WHERE character_id='.$character_id.' AND section_id='.$section["id"];
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo replace_text($pairs, $row["content"]);
                echo '
                    </div>';
            }
        }
        // 时间线
        else if ($section["type"] == 2) {
            $sql = 'SELECT * FROM timelines WHERE character_id='.$character_id.' AND chapter='.$_GET["chapter"].' ORDER BY hour ASC, minute ASC';
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
            	$hour = ($row["hour"] < 10? ("0".$row["hour"]):$row["hour"]);
                $minute = ($row["minute"] < 10? ("0".$row["minute"]):$row["minute"]);
                echo '
                		<p><b>'.$hour.":".$minute.'</b>，'.replace_text($pairs, $row["content"]).'</p>';
            }
            echo '
                    </div>';
        }
        // 房间线索
        else if ($section["type"] == 3) {
            echo '
                    </div>';
            $sql = 'SELECT C.id, C.unlock_id, C.file_path, C.position, C.self_description FROM clues AS C LEFT JOIN locations AS L ON C.location_id=L.id WHERE L.character_id='.$character_id.' AND C.chapter='.$_GET["chapter"].' AND section_id='.$section["id"].' ORDER BY C.id ASC';
            $result = $conn->query($sql);
            $counter = 0;
            while ($row = $result->fetch_assoc()) {
                if ($row["unlock_id"] > 0) {
                    $sql2 = 'SELECT file_path FROM clues WHERE id='.$row["unlock_id"];
                    $result2 = $conn->query($sql2);
                    while ($row2 = $result2->fetch_assoc()) {
                        $file_path = $row2["file_path"];
                    }
                }
                else {
                    $file_path = $row["file_path"];
                }

                if ($counter % 2 == 0) $offset = 0;
                else $offset = 1;
                echo '
                    <div class="col-lg-5 offset-lg-'.$offset.' col-md-5 offset-md-'.$offset.' col-sm-10 offset-sm-1" onclick="open_modal('.$row["id"].')">
                        <div class="row" style="border: 1px solid #BBBBBB; margin-top: 20px; padding: 5px 0px 5px 0px;">
                            <div class="col-3" style="padding: 0px 0px 0px 5px; cursor: pointer;">';
                if (!file_exists($file_path)) {
                    echo '
                                <img style="width: 100%;" src="img/alt.png">';
                }
                else {
                    echo '
                                <img style="width: 100%;" src="'.$file_path.'">';
                }
                echo '
                            </div>
                            <div class="col-9" style="cursor: pointer;">
                                <h4>'.$row["position"].'</h4>
                                <p>'.replace_text($pairs, $row["self_description"]).'</p>
                            </div>
                        </div>
                    </div>';
                $counter += 1;
            }
            if ($counter % 2 == 1) {
                echo '
                    <div class="col-lg-5 offset-lg-1 col-md-5 offset-md-1 col-0">
                    </div>';
            }
        }
        // 任务
        else {
            $sql = 'SELECT * FROM objectives WHERE character_id='.$character_id.' AND chapter='.$_GET["chapter"].' ORDER BY id ASC';
            $result = $conn->query($sql);
            echo '
                        <ul class="unordered-list">';
            while ($row = $result->fetch_assoc()) {
                $points = $row["points"];
                echo '
                            <li>'.replace_text($pairs, $row["content"]).($points>0? ("（".$points."分）。"):"").'</li>';
            }
            echo '
                        </ul>
                    <div>';
        }
        echo '
                </div>
            </div>
        </div>
    </section>';
	}
	?>

    <div id="myModal" class="modal" style="top: 18%;">
        <div class="modal-content col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-sm-10 offset-sm-1">
            <div class="modal-header">
                <h4 id="modal_title" class="modal-title"></h4>
                <span class="close" style="float: right;" onclick="close_modal()">&times;</span>
            </div>
            <div class="modal-body">
                <img src="" class="col-lg-8 offset-lg-2 col-md-10 offset-md-1" id="modal_img">
                <br><br>
                <p id="modal_content"></p>
            </div>
        </div>
    </div>