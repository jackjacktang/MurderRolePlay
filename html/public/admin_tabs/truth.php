	<br><br><br>

    <style type="text/css">
        table, th, td {
            border: 1px solid #BBBBBB;
            border-collapse: collapse;
        }
    </style>

    <script type="text/javascript">
        function copyHTML() {
            document.getElementById("truth_hide").value = document.getElementById("truth_show").innerHTML;
        }

        function change_font() {
            document.execCommand('fontSize',false,'7');
            var fontElements = document.getElementsByTagName("font");
            for (var i = 0, len = fontElements.length; i < len; ++i) {
                if (fontElements[i].size == "7") {
                    fontElements[i].removeAttribute("size");
                    fontElements[i].style.fontSize = "14px";
                }
            }
        }

        function insertOl() {
            document.execCommand('insertOrderedList',false,null);
            var olElements = document.getElementsByTagName("ol");
            for (var i = 0, len = olElements.length; i < len; ++i) {
                olElements[i].setAttribute("class", "ordered-list");
            }
        }

        function insertUl() {
            document.execCommand('insertUnorderedList',false,null);
            var ulElements = document.getElementsByTagName("ul");
            for (var i = 0, len = ulElements.length; i < len; ++i) {
                if (ulElements[i].className.indexOf("nav-menu") == -1) {
                    ulElements[i].setAttribute("class", "unordered-list");
                }
            }
        }
    </script>

    <form action="admin_tabs/request.php" method="post">
        <input type="hidden" name="tab" value="truth">

        <section>
            <div class="container">
                <div class="section-top-border">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-9 col-md-9 col-sm-10">
                            <img src="img/cover.jpg" style="width: 100%;"/>
                            <br><br>
                            <?php
                            $content = "";
                            $sql = 'SELECT * FROM background WHERE id=1';
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                $content = $row["content"];
                            }
                            ?>
                            <center>
                                <h3>添加/修改真相</h3><br>
                                <input type="hidden" id="truth_hide" name="truth" value='<?php echo $content; ?>'>
                                <div onclick="copyHTML()" style="width: 90%; height: 40px; border: 1px solid #BBBBBB; text-align: left; font-size: 14px;">
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; font-weight: bold;" onclick="document.execCommand('bold',false,null);" tabindex="-1">B</button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; font-style: italic;" onclick="document.execCommand('italic',false,null);" tabindex="-1">I</button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; text-decoration: underline;" onclick="document.execCommand('underline',false,null);" tabindex="-1">U</button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand('fontSize',false,'4');" tabindex="-1">A+</button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="change_font()" tabindex="-1">A-</button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand('justifyLeft',false,null);" tabindex="-1"><i class="fa fa-align-left"></i></button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand('justifyCenter',false,null);" tabindex="-1"><i class="fa fa-align-center"></i></button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand('justifyRight',false,null);" tabindex="-1"><i class="fa fa-align-right"></i></button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="insertOl()" tabindex="-1"><i class="fa fa-list-ol"></i></button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="insertUl()" tabindex="-1"><i class="fa fa-list-ul"></i></button>
                                </div> 
                                <div id="truth_show" style="width: 90%; height: 300px; margin-bottom: 20px; border: 1px solid #BBBBBB; text-align: left; overflow: auto; font-size: 14px;" contenteditable="true" onkeyup="copyHTML()"><?php echo $content; ?></div>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    	<?php
    	$chapters = array();
    	$sql = 'SELECT * FROM sections WHERE type=2 AND chapter=1';
    	$result = $conn->query($sql);
    	if ($result->num_rows > 0) array_push($chapters, 1);
    	$sql = 'SELECT * FROM sections WHERE type=2 AND chapter=2';
    	$result = $conn->query($sql);
    	if ($result->num_rows > 0) array_push($chapters, 2);
    	foreach ($chapters as $chapter) {
    		if (sizeof($chapters) == 1) {
    			$title = "时间线";
    		}
    		else {
    			if ($chapter == 1) $title = "时间线（第一幕）";
    			else $title = "时间线（第二幕）";
    		}
    		echo '
    	<section>
            <div class="container">
                <div class="section-top-border">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-8 col-md-9 col-sm-10">
                            <center>
                                <h3 style="margin-bottom: 20px;">'.$title.'</h3>
                            </center>
                        </div>
                        <table style="width: 100%;">
                            <tr>
                            	<th></th>';
            $character_ids = array();
            $sql = 'SELECT * FROM characters';
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
            	if ($row["id"] != 1) {
            		array_push($character_ids, $row["id"]);
            		echo '
            					<th style="min-width: 150px; text-align: center;">'.$row["name"].'</th>';
            	}
            }
            echo '
                            </tr>';

            $sql = 'SELECT hour, minute FROM timelines WHERE chapter='.$chapter.' GROUP BY hour, minute ORDER BY hour ASC, minute ASC';
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
            	$hour = $row["hour"];
            	$minute = $row["minute"];
            	echo '
            				<tr>
            					<td>'.($hour<10? ("0".$hour):$hour).":".($minute<10? ("0".$minute):$minute).'</td>';

            	foreach ($character_ids as $character_id) {
            		$content = "";
            		$sql1 = 'SELECT content FROM timelines WHERE chapter='.$chapter.' AND character_id='.$character_id.' AND hour='.$hour.' AND minute='.$minute;
            		$result1 = $conn->query($sql1);
            		while ($row1 = $result1->fetch_assoc()) {
            			$content = $row1["content"];
            		}
            		echo '
            					<td>'.$content.'</td>';
            	}

            	echo '
            				</tr>';
            }

            echo '
                        </table>
                    </div>
                </div>
            </div>
        </section>';
    	}
        ?>

    