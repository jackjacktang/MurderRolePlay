	<br><br><br>

	<?php
	foreach ($sections[$_GET["chapter"] - 1] as $section) {
        echo '
    <section id="section'.$section["id"].'">
        <div class="container">
            <div class="section-top-border">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-8 col-md-9 col-sm-10">
                        <center>
                            <h3 style="margin-bottom: 20px;">'.$section["title"].'</h3>
                        </center>';
        // 普通线索
        if ($section["type"] == 1) {
            $sql = 'SELECT * FROM character_section WHERE character_id='.$character_id.' AND section_id='.$section["id"];
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo $row["content"];
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
                		<p><b>'.$hour.":".$minute.'</b>，'.$row["content"].'</p>';
            }
        }
        // 房间线索
        else if ($section["type"] == 3) {
            echo '
                        ';
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
                            <li>'.$row["content"].($points>0? ("（".$points."分）"):"").'。</li>';
            }
            echo '
                        </ul>';
        }
        echo '
                    </div>
                </div>
            </div>
        </div>
    </section>';
	}
	?>
