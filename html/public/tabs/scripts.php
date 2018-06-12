	<br><br><br>

	<?php
	$sql = 'SELECT * FROM sections WHERE chapter='.$_GET["chapter"].' ORDER BY sequence ASC';
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		echo 
		'<section>
            <div class="container">
                <div class="section-top-border">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-8 col-md-9 col-sm-10">
                            <center>
                                <h3 style="margin-bottom: 20px;">'.$row["title"].'</h3>
                            </center>';

            // 普通线索
            if ($row["type"] == 1) {
                $sql1 = 'SELECT * FROM character_section WHERE character_id='.$character_id.' AND section_id='.$row["id"];
                $result1 = $conn->query($sql1);
                $content = "";
                while ($row1 = $result1->fetch_assoc()) {
                    $content = $row1["content"];
                }
                echo '
                            <p class="col-lg-9 col-md-9 col-sm-10">'.$content.'</p>';
            }
            // 时间线
            else if ($row["type"] == 2) {
                $sql1 = 'SELECT * FROM timelines WHERE character_id='.$character_id.' AND chapter='.$_GET["chapter"].' ORDER BY hour ASC, minute ASC';
                $result1 = $conn->query($sql1);
                while ($row1 = $result1->fetch_assoc()) {
                	$hour = ($row1["hour"] < 10? ("0".$row1["hour"]):$row1["hour"]);
                    $minute = ($row1["minute"] < 10? ("0".$row1["minute"]):$row1["minute"]);
                    echo '
                    		<p><b>'.$hour.":".$minute.'</b>，'.$row1["content"].'</p>';
                }
            }
            else if ($row["type"] == 3) {
                echo '
                                ';
            }
            else {
                echo '
                                ';
            }
        echo '
                        </div>
                    </div>
                </div>
            </div>
        </section>';
	}
	?>