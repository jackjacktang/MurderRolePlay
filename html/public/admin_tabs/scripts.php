	<script type="text/javascript">
        function add_timeline(id, hour, minute, content) {
            var timeline_area = document.getElementById("timeline_area");
            var div = document.createElement("div");
            div.style.marginTop = "20px";
            timeline_area.appendChild(div);
            if (id == -1) {
                id = max_timeline + 1;
            }
            div.innerHTML = div.innerHTML + '<label style="width: 48px; text-align: right;">时间：&nbsp;</label><input type="number" style="width: 5%;" value="' + hour + '" name="timeline'+ id + '_hour" min="0" max="24" onchange="if(this.value.length==1)this.value=\'0\'+this.value;if(this.value>24)this.value=24;if(this.value<0)this.value=0;">';
            div.innerHTML = div.innerHTML + '：<input type="number" style="width: 5%;" value="' + minute + '" name="timeline'+ id + '_minute" min="0" max="60" onchange="if(this.value.length==1)this.value=\'0\'+this.value;if(this.value>60)this.value=60;if(this.value<0)this.value=0;">';
            div.innerHTML = div.innerHTML + '<label style="width: 78px; text-align: right;">内容：&nbsp;</label><input style="width: 35%;" value="' + content + '" name="timeline'+ id + '_content">';

            if (id > max_timeline) {
                max_timeline = id;
                document.getElementById("max_timeline").value = id;
            }
        }

        function copyHTML(id) {
            document.getElementById("section" + id + "_hide").value = document.getElementById("section" + id + "_show").innerHTML;
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

    <br><br><br>
	<form action="admin_tabs/request.php" method="post">
        <input type="hidden" name="tab" value="scripts">
        <input type="hidden" name="character_id" value="<?php echo $_GET["character_id"]; ?>">
        <input type="hidden" name="chapter" value="<?php echo $_GET["chapter"]; ?>">
        <input type="hidden" name="max_timeline" id="max_timeline" value="0">
		<?php
		$sql = 'SELECT * FROM sections WHERE chapter='.$_GET["chapter"].' ORDER BY sequence ASC';
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			echo '
		<section>
            <div class="container">
                <div class="section-top-border">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-9 col-md-9 col-sm-10">
                            <center>
                                <h3 style="margin-bottom: 20px;">
                                    '.$row["title"];

            // 普通线索
            if ($row["type"] == 1) {
                $sql1 = 'SELECT * FROM character_section WHERE character_id='.$_GET["character_id"].' AND section_id='.$row["id"];
                $result1 = $conn->query($sql1);
                $content = "";
                while ($row1 = $result1->fetch_assoc()) {
                    $content = $row1["content"];
                }
                echo '
                                </h3>
                                <input type="hidden" id="section'.$row["id"].'_hide" name="section'.$row["id"].'_content" value=\''.$content.'\'>
                                <div onclick="copyHTML('.$row["id"].')" style="width: 90%; height: 40px; border: 1px solid #BBBBBB; text-align: left; font-size: 14px;">
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; font-weight: bold;" onclick="document.execCommand(\'bold\',false,null);">B</button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; font-style: italic;" onclick="document.execCommand(\'italic\',false,null);">I</button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; text-decoration: underline;" onclick="document.execCommand(\'underline\',false,null);">U</button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand(\'fontSize\',false,\'4\');">A+</button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="change_font()">A-</button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand(\'justifyLeft\',false,null);"><i class="fa fa-align-left"></i></button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand(\'justifyCenter\',false,null);"><i class="fa fa-align-center"></i></button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand(\'justifyRight\',false,null);"><i class="fa fa-align-right"></i></button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="insertOl()"><i class="fa fa-list-ol"></i></button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="insertUl()"><i class="fa fa-list-ul"></i></button>
                                </div> 
                                <div id="section'.$row["id"].'_show" style="width: 90%; height: 300px; margin-bottom: 20px; border: 1px solid #BBBBBB; text-align: left; overflow: auto; font-size: 14px;" contenteditable="true" onkeyup="copyHTML('.$row["id"].')">'.$content.'</div>';
            }
            // 时间线
            else if ($row["type"] == 2) {
                echo '
                                    <button class="genric-btn info circle small" style="width: 25px; height: 25px; padding: 0px;" type="button" onclick="add_timeline(-1, \'\', \'\', \'\')">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </h3>
                                <div id="timeline_area">
                                    <script type="text/javascript">
                                        var max_timeline = 0;';
                $sql1 = 'SELECT * FROM timelines WHERE character_id='.$_GET["character_id"].' AND chapter='.$_GET["chapter"].' ORDER BY hour ASC, minute ASC';
                $result1 = $conn->query($sql1);
                while ($row1 = $result1->fetch_assoc()) {
                    $hour = ($row1["hour"] < 10? ("0".$row1["hour"]):$row1["hour"]);
                    $minute = ($row1["minute"] < 10? ("0".$row1["minute"]):$row1["minute"]);
                    echo '
                                        add_timeline('.$row1["id"].', "'.$hour.'", "'.$minute.'", "'.$row1["content"].'");';
                }
                echo '
                                    </script>
                                </div>';
            }
            else if ($row["type"] == 3) {
                echo '
                                </h3>';
            }
            else {
                echo '
                                </h3>';
            }

            echo '
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </section>';
		}
		?>

		<section>
            <div class="container">
                <div class="section-top-border">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-9 col-md-9 col-sm-10">
                            <center>
                                <button type="submit" name="submit" class="genric-btn info circle e-large col-10" style="font-size: 14pt; width: 50%;">保存</button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </section>
	</form>