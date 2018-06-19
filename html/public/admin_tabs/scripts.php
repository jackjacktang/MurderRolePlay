	<script type="text/javascript">
        var clue_information = [];
        var location_information = [];
        <?php
        $sql = 'SELECT C.id, C.location_id, L.name, C.position, C.points, C.self_description, C.description, C.file_path, C.unlock_id, C.unlock_characters FROM clues AS C LEFT JOIN locations AS L ON C.location_id=L.id WHERE C.chapter='.$_GET["chapter"];
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo '
        clue_information['.$row["id"].'] = [\''.$row["name"].'\', \''.$row["position"].'\', '.$row["points"].', \''.$row["self_description"].'\', \''.$row["description"].'\', \''.$row["file_path"].'\', '.$row["unlock_id"].', \''.$row["unlock_characters"].'\'];';
        }
        $sql = 'SELECT * FROM locations';
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo '
        location_information['.$row["id"].'] = \''.$row["name"].'\';';
        }
        ?>
        clue_information[-1] = ["", "",  1, "", "", "", "", ""];

        var id;
        var counter;
        var section;

        function add_timeline(id, hour, minute, content) {
            var timeline_area = document.getElementById("timeline_area");
            var div = document.createElement("div");
            div.setAttribute("id", "timeline" + timeline_counter);
            div.style.marginTop = "20px";
            timeline_area.appendChild(div);
            div.innerHTML += '<input type="hidden" name="timeline_ids[]" value="' + id + '">';
            div.innerHTML = div.innerHTML + '<label style="width: 48px; text-align: right;">时间：&nbsp;</label><input type="number" style="width: 5%;" value="' + hour + '" name="timeline_hours[]" min="0" max="24" onchange="if(this.value.length==1)this.value=\'0\'+this.value;if(this.value>24)this.value=24;if(this.value<0)this.value=0;" id="timeline'+ timeline_counter + '_hour">';
            div.innerHTML = div.innerHTML + '：<input type="number" style="width: 5%;" value="' + minute + '" name="timeline_minutes[]" min="0" max="60" onchange="if(this.value.length==1)this.value=\'0\'+this.value;if(this.value>60)this.value=60;if(this.value<0)this.value=0;" id="timeline'+ timeline_counter + '_minute">';
            div.innerHTML = div.innerHTML + '<input type="hidden" value=\'' + content + '\' name="timeline_contents[]" id="timeline'+ timeline_counter + '_hide">';
            div.innerHTML = div.innerHTML + '<button type="button" onclick="open_modal(' + id +', ' + timeline_counter + ', \'timeline\')" class="genric-btn danger circle small" style="width: 25px; height: 25px; padding: 0px; margin-left: 5%;" tabindex="-1"><i class="fa fa-minus"></i></button>';
            var div1 = document.createElement("div");
            div.appendChild(div1);
            div1.setAttribute("id", "menu_bar" + timeline_counter);
            div1.style.width = "50%";
            div1.style.height = "40px";
            div1.style.border = "1px solid #BBBBBB";
            div1.style.textAlign = "left";
            div1.style.fontSize = "14px";
            div1.innerHTML = div1.innerHTML + '<button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; font-style: italic;" onclick="document.execCommand(\'italic\',false,null);" tabindex="-1">I</button>';
            div1.innerHTML = div1.innerHTML + '<button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; text-decoration: underline;" onclick="document.execCommand(\'underline\',false,null);" tabindex="-1">U</button>';
            div1.innerHTML = div1.innerHTML + '<button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="insertOl()" tabindex="-1"><i class="fa fa-list-ol"></i></button>';
            div1.innerHTML = div1.innerHTML + '<button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="insertUl()" tabindex="-1"><i class="fa fa-list-ul"></i></button>';
            div.innerHTML = div.innerHTML + '<div id="timeline' + timeline_counter + '_show" style="width: 50%; height: 50px; margin-bottom: 20px; border: 1px solid #BBBBBB; text-align: left; overflow: auto; font-size: 14px;" contenteditable="true" onkeyup="copyHTML(\'timeline\', ' + timeline_counter + ')">' + content + '</div>';
            document.getElementById("menu_bar" + timeline_counter).addEventListener("click", function() {copyHTML("timeline", id)});
            timeline_counter += 1;
        }

        function add_objective(id, content, points) {
            var objective_area = document.getElementById("objective_area");
            var div = document.createElement("div");
            div.style.marginTop = "20px";
            objective_area.appendChild(div);
            div.setAttribute("id", "objective" + objective_counter);
            div.innerHTML += '<input type="hidden" name="objective_ids[]" value="' + id + '">';
            div.innerHTML = div.innerHTML + '<input type="hidden" value=\'' + content + '\' name="objective_contents[]" id="objective'+ objective_counter + '_hide">';
            div.innerHTML = div.innerHTML + '<label style="width: 78px; text-align: right;">分数：&nbsp;</label><input type="number" style="width: 5%;" value="' + points + '" name="objective_pointses[]" id="objective'+ objective_counter + '_points">';
            div.innerHTML = div.innerHTML + '<button type="button" onclick="open_modal(' + id +', ' + objective_counter + ', \'objective\')" class="genric-btn danger circle small" style="width: 25px; height: 25px; padding: 0px; margin-left: 5%;" tabindex="-1"><i class="fa fa-minus"></i></button>';
            var div1 = document.createElement("div");
            div1.setAttribute("id", "menu_bar" + id);
            div.appendChild(div1);
            div1.style.width = "50%";
            div1.style.height = "40px";
            div1.style.border = "1px solid #BBBBBB";
            div1.style.textAlign = "left";
            div1.style.fontSize = "14px";
            div1.innerHTML = div1.innerHTML + '<button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; font-style: italic;" onclick="document.execCommand(\'italic\',false,null);" tabindex="-1">I</button>';
            div1.innerHTML = div1.innerHTML + '<button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; text-decoration: underline;" onclick="document.execCommand(\'underline\',false,null);" tabindex="-1">U</button>';
            div1.innerHTML = div1.innerHTML + '<button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="insertOl()" tabindex="-1"><i class="fa fa-list-ol"></i></button>';
            div1.innerHTML = div1.innerHTML + '<button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="insertUl()" tabindex="-1"><i class="fa fa-list-ul"></i></button>';
            div.innerHTML = div.innerHTML + '<div id="objective' + objective_counter + '_show" style="width: 50%; height: 50px; margin-bottom: 20px; border: 1px solid #BBBBBB; text-align: left; overflow: auto; font-size: 14px;" contenteditable="true" onkeyup="copyHTML(\'objective\', ' + objective_counter + ')">' + content + '</div>';
            document.getElementById("menu_bar" + id).addEventListener("click", function() {copyHTML("objective", id)});
            objective_counter += 1;
        }

        function copyHTML(type, id) {
            document.getElementById(type + id + "_hide").value = document.getElementById(type + id + "_show").innerHTML;
        }

        function copyHTML1() {
            document.getElementById("self_description_hidden").value = document.getElementById("self_description_show").innerHTML;
        }

        function copyHTML2() {
            document.getElementById("description_hidden").value = document.getElementById("description_show").innerHTML;
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

        function preview(input) {
            document.getElementById("image").src = URL.createObjectURL(input.files[0]);
        }

        function open_modal(id, counter, section) {
            var modal = document.getElementById("myModal");
            var modal1 = document.getElementById("myModal1");
            var modal2 = document.getElementById("myModal2");
            modal1.style.display = "none";
            modal2.style.display = "none";
            modal.style.display = "block";
            window.id = id;
            window.counter = counter;
            window.section = section;
            if (section == "timeline") {
                document.getElementById("modal_title").innerHTML = "确认要删除这条时间线么？";
                var hour = document.getElementById("timeline" + counter + "_hour").value;
                var minute = document.getElementById("timeline" + counter + "_minute").value;
                var content = document.getElementById("timeline" + counter + "_show").innerHTML;
                document.getElementById("modal_content").innerHTML = hour + ":" + minute + "，" + content;
            }
            else {
                document.getElementById("modal_title").innerHTML = "确认要删除这个玩家任务么？";
                var content = document.getElementById("objective" + counter + "_show").innerHTML;
                document.getElementById("modal_content").innerHTML = content;
            }
        }

        function close_modal() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
        }

        function open_modal1(id, location_id) {
            var modal = document.getElementById("myModal");
            var modal1 = document.getElementById("myModal1");
            var modal2 = document.getElementById("myModal2");
            modal1.style.display = "block";
            modal2.style.display = "none";
            modal.style.display = "none";
            if (location_id == 0) {
                document.getElementById("secret_clue").style.display = "none";
            }
            else {
                document.getElementById("secret_clue").style.display = "block";
            }
            document.getElementById("clue_id").value = id;
            document.getElementById("location_id").value = location_id;
            document.getElementById("modal_title1").innerHTML = location_information[location_id];
            document.getElementById("position").value = clue_information[id][1];
            document.getElementById("points").value = clue_information[id][2];
            document.getElementById("self_description_hidden").value = clue_information[id][3];
            document.getElementById("self_description_show").innerHTML = clue_information[id][3];
            document.getElementById("description_hidden").value = clue_information[id][4];
            document.getElementById("description_show").innerHTML = clue_information[id][4];
            if (clue_information[id][5] == "") {
                document.getElementById("image").src = "img/alt.png";
            }
            else {
                document.getElementById("image").src = clue_information[id][5];
            }

            var options = document.getElementsByClassName("options");
            for (var i = 0; i < options.length; i++) {
                if (options[i].value == clue_information[id][6]) {
                    options[i].selected = true;
                }
                else {
                    options[i].selected = false;
                }
            }

            var checkboxes = document.getElementsByClassName("checkboxes");
            var unlock_characters = clue_information[id][7].split(",");
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = false;
                for (var j = 0; j < unlock_characters.length; j++) {
                    if (checkboxes[i].value == unlock_characters[j]) {
                        checkboxes[i].checked = true;
                    }
                }
            }
        }

        function close_modal1() {
            var modal1 = document.getElementById("myModal1");
            modal1.style.display = "none";
        }

        function open_modal2(id) {
            var modal = document.getElementById("myModal");
            var modal1 = document.getElementById("myModal1");
            var modal2 = document.getElementById("myModal2");
            modal1.style.display = "none";
            modal2.style.display = "block";
            modal.style.display = "none";
            document.getElementById("clue_id").value = id;
            document.getElementById("modal_content2").innerHTML = clue_information[id][0] + "：" + clue_information[id][1];
        }

        function close_modal2() {
            var modal2 = document.getElementById("myModal2");
            modal2.style.display = "none";
        }

        window.onclick = function(event) {
            var modal = document.getElementById("myModal");
            var modal1 = document.getElementById("myModal1");
            var modal2 = document.getElementById("myModal2");
            if (event.target == modal) {
                modal.style.display = "none";
            }
            if (event.target == modal1) {
                modal1.style.display = "none";
            }
            if (event.target == modal2) {
                modal2.style.display = "none";
            }
        }

        function delete_modal() {
            if (id < 0) {
                var delete_li = document.getElementById(section + counter);
                delete_li.parentNode.removeChild(delete_li);
                close_modal();
            }
            else {
                document.getElementById("delete_section").value = section;
                document.getElementById("delete_id").value = id;
                document.getElementById("myform").submit();
            }
        }
    </script>

    <br><br><br>
	<form id="myform" action="admin_tabs/request.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="tab" value="scripts">
        <input type="hidden" name="character_id" value="<?php echo $_GET["character_id"]; ?>">
        <input type="hidden" name="chapter" value="<?php echo $_GET["chapter"]; ?>">
        <input type="hidden" name="delete_section" id="delete_section" value="">
        <input type="hidden" name="delete_id" id="delete_id" value="-1">
        <input type="hidden" name="clue_id" id="clue_id">
        <div id="myModal" class="modal" style="top: 30%;">
            <div class="modal-content col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-10 offset-1">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title"></h4>
                    <span class="close" style="float: right;" onclick="close_modal()">&times;</span>
                </div>
                <div class="modal-body">
                    <p id="modal_content"></p>
                </div>
                <div class="modal-footer justify-content-center" style="font-size: 14pt;">
                    <button class="genric-btn info circle e-large" type="button" onclick="delete_modal()">确定</button>
                    <button class="genric-btn info circle e-large" type="button" onclick="close_modal()">关闭</button>
                </div>
            </div>
        </div>
        <div id="myModal1" class="modal" style="top: 5%; width: 100%;">
            <div class="modal-content col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-10 offset-1">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title1"></h4>
                    <span class="close" style="float: right;" onclick="close_modal1()">&times;</span>
                </div>
                <div class="modal-body">
                    <div>
                        <input type="hidden" name="location_id" id="location_id">
                        <label style="text-align: left; width: 120px;">位置</label><input name="position" id="position" style="width: 200px;" type="text" maxlength="20"><br>
                        <label style="text-align: left; width: 120px;">所需行动点：</label><input style="width: 200px;" name="points" id="points" type="number" value=1><br>
                        <label style="text-align: left; width: 120px;">自己看到的描述：</label>
                        <!-- <input style="width: 200px;" name="self_description" id="self_description" type="text" maxlength="300"><br> -->
                        <input type="hidden" id="self_description_hidden" name="self_description">
                        <div onclick="copyHTML1()" style="width: 100%; height: 40px; border: 1px solid #BBBBBB; text-align: left; font-size: 14px;">
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; font-weight: bold;" onclick="document.execCommand('bold',false,null);" tabindex="-1">B</button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; font-style: italic;" onclick="document.execCommand('italic',false,null);" tabindex="-1">I</button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; text-decoration: underline;" onclick="document.execCommand('underline',false,null);" tabindex="-1">U</button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand('justifyLeft',false,null);" tabindex="-1"><i class="fa fa-align-left"></i></button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand('justifyCenter',false,null);" tabindex="-1"><i class="fa fa-align-center"></i></button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand('justifyRight',false,null);" tabindex="-1"><i class="fa fa-align-right"></i></button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="insertOl()" tabindex="-1"><i class="fa fa-list-ol"></i></button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="insertUl()" tabindex="-1"><i class="fa fa-list-ul"></i></button>
                        </div> 
                        <div id="self_description_show" style="width: 100%; height: 100px; margin-bottom: 20px; border: 1px solid #BBBBBB; text-align: left; overflow: auto; font-size: 14px;" contenteditable="true" onkeyup="copyHTML1()"></div>
                        <label style="text-align: left; width: 120px;">描述：</label>
                        <!-- <input style="width: 200px;" name="description" id="description" type="text" maxlength="300"><br> -->
                        <input type="hidden" id="description_hidden" name="description">
                        <div onclick="copyHTML2()" style="width: 100%; height: 40px; border: 1px solid #BBBBBB; text-align: left; font-size: 14px;">
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; font-weight: bold;" onclick="document.execCommand('bold',false,null);" tabindex="-1">B</button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; font-style: italic;" onclick="document.execCommand('italic',false,null);" tabindex="-1">I</button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; text-decoration: underline;" onclick="document.execCommand('underline',false,null);" tabindex="-1">U</button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand('justifyLeft',false,null);" tabindex="-1"><i class="fa fa-align-left"></i></button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand('justifyCenter',false,null);" tabindex="-1"><i class="fa fa-align-center"></i></button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand('justifyRight',false,null);" tabindex="-1"><i class="fa fa-align-right"></i></button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="insertOl()" tabindex="-1"><i class="fa fa-list-ol"></i></button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="insertUl()" tabindex="-1"><i class="fa fa-list-ul"></i></button>
                        </div> 
                        <div id="description_show" style="width: 100%; height: 100px; margin-bottom: 20px; border: 1px solid #BBBBBB; text-align: left; overflow: auto; font-size: 14px;" contenteditable="true" onkeyup="copyHTML2()"></div>
                        <label style="text-align: left; width: 120px;">图片：</label><input name="image" type="file" onchange="preview(this)"><br>
                        <img src="" id="image" style="max-width: 100%;">
                    </div>
                    <div style="margin-top: 15px; margin-bottom: 15px; border: 1px solid #EEEEEE;"></div>
                    <div id="secret_clue">
                        <label style="text-align: left; width: 120px; display: inline-block;">秘密线索：</label>
                        <select style="width: 200px; display: inline-block;" name="unlock_id" id="select">
                            <option value="0" selected class="options"></option>
                            <?php
                            $sql = "SELECT * FROM clues WHERE location_id=0";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                echo '
                            <option value="'.$row["id"].'" class="options">'.$row["position"].'</option>';
                            }
                            ?>
                        </select><br>
                        <label style="text-align: left; width: 120px;">可解锁的人：</label><br>
                        <?php
                        $sql = "SELECT * FROM characters WHERE id>1";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            echo '
                        <input type="checkbox" name="unlock_characters[]" value="'.$row["id"].'" class="checkboxes">'.$row["name"].'<br>';
                        }
                        ?>
                    </div>
                </div>
                <div class="modal-footer justify-content-center" style="font-size: 14pt;">
                    <button class="genric-btn info circle e-large" name="submit1">保存</button>
                    <button class="genric-btn info circle e-large" type="button" onclick="close_modal1()">关闭</button>
                </div>
            </div>
        </div>
        <div id="myModal2" class="modal" style="top: 30%;">
            <div class="modal-content col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-10 offset-1">
                <div class="modal-header">
                    <h4 class="modal-title">确认要删除这条线索么？</h4>
                    <span class="close" style="float: right;" onclick="close_modal2()">&times;</span>
                </div>
                <div class="modal-body">
                    <p id="modal_content2"></p>
                </div>
                <div class="modal-footer justify-content-center" style="font-size: 14pt;">
                    <button class="genric-btn info circle e-large" name="submit2">确认</button>
                    <button class="genric-btn info circle e-large" type="button" onclick="close_modal2()">关闭</button>
                </div>
            </div>
        </div>
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
                                <div onclick="copyHTML(\'section\', '.$row["id"].')" style="width: 90%; height: 40px; border: 1px solid #BBBBBB; text-align: left; font-size: 14px;">
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; font-weight: bold;" onclick="document.execCommand(\'bold\',false,null);" tabindex="-1">B</button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; font-style: italic;" onclick="document.execCommand(\'italic\',false,null);" tabindex="-1">I</button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; text-decoration: underline;" onclick="document.execCommand(\'underline\',false,null);" tabindex="-1">U</button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand(\'fontSize\',false,\'4\');" tabindex="-1">A+</button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="change_font()" tabindex="-1">A-</button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand(\'justifyLeft\',false,null);"><i class="fa fa-align-left" tabindex="-1"></i></button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand(\'justifyCenter\',false,null);"><i class="fa fa-align-center" tabindex="-1"></i></button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand(\'justifyRight\',false,null);"><i class="fa fa-align-right" tabindex="-1"></i></button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="insertOl()" tabindex="-1"><i class="fa fa-list-ol"></i></button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="insertUl()" tabindex="-1"><i class="fa fa-list-ul"></i></button>
                                </div> 
                                <div id="section'.$row["id"].'_show" style="width: 90%; height: 300px; margin-bottom: 20px; border: 1px solid #BBBBBB; text-align: left; overflow: auto; font-size: 14px;" contenteditable="true" onkeyup="copyHTML(\'section\', '.$row["id"].')">'.$content.'</div>
                            </center>
                        </div>';
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
                                        var timeline_counter = 0;';
                $sql1 = 'SELECT * FROM timelines WHERE character_id='.$_GET["character_id"].' AND chapter='.$_GET["chapter"].' ORDER BY hour ASC, minute ASC';
                $result1 = $conn->query($sql1);
                while ($row1 = $result1->fetch_assoc()) {
                    $hour = ($row1["hour"] < 10? ("0".$row1["hour"]):$row1["hour"]);
                    $minute = ($row1["minute"] < 10? ("0".$row1["minute"]):$row1["minute"]);
                    echo '
                                        add_timeline('.$row1["id"].', "'.$hour.'", "'.$minute.'", \''.$row1["content"].'\');';
                }
                echo '
                                    </script>
                                </div>
                            </center>
                        </div>';
            }
            else if ($row["type"] == 3) {
                echo '
                                    <button class="genric-btn info circle small" style="width: 25px; height: 25px; padding: 0px;" type="button" onclick="open_modal1(-1, '.(-$_GET["character_id"]).')">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </h3>
                            </center>
                        </div>';
                $sql1 = 'SELECT C.id, C.location_id, L.name, C.position, C.points, C.self_description, C.description, C.file_path, C.unlock_id, C.unlock_characters FROM clues AS C LEFT JOIN locations AS L ON C.location_id=L.id WHERE C.chapter='.$_GET["chapter"].' AND L.id='.(-$_GET["character_id"]);
                $result1 = $conn->query($sql1);
                $counter = 0;
                while ($row1 = $result1->fetch_assoc()) {
                    if ($counter % 2 == 0) $offset = 0;
                    else $offset = 1;
                    echo '
                            <div class="col-lg-5 offset-lg-'.$offset.' col-md-5 offset-md-'.$offset.' col-sm-10 offset-sm-1">
                                <div class="row" style="border: 1px solid #BBBBBB; margin-top: 20px; padding: 5px 0px 5px 0px;">
                                    <div class="col-3" style="padding: 0px 0px 0px 5px; cursor: pointer;" onclick="open_modal1('.$row1["id"].', '.$row1["location_id"].')">';
                    if (!file_exists($row1["file_path"])) {
                        echo '
                                        <img style="width: 100%;" src="img/alt.png">';
                    }
                    else {
                        echo '
                                        <img style="width: 100%;" src="'.$row1["file_path"].'">';
                    }
                    echo '
                                    </div>
                                    <div class="col-8" style="border-right: 1px solid #BBBBBB; cursor: pointer;" onclick="open_modal1('.$row1["id"].', '.$row1["location_id"].')">
                                        <h4>'.$row1["position"].'</h4>
                                        <p>'.$row1["self_description"].'</p>
                                    </div>
                                    <div class="col-1" style="padding: 0px 0px 0px 0px; text-align: center;">
                                        <button type="button" class="genric-btn danger circle small" style="width: 25px; height: 25px; padding: 0px;" onclick="open_modal2('.$row1["id"].')">
                                            <i class="fa fa-minus"></i>
                                        </button>
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
            else if ($row["type"] == 4) {
                echo '
                                    <button class="genric-btn info circle small" style="width: 25px; height: 25px; padding: 0px;" type="button" onclick="add_objective(-1, \'\', -1)">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </h3>
                                <div id="objective_area">
                                    <script type="text/javascript">
                                        var objective_counter = 0;';
                $sql1 = 'SELECT * FROM objectives WHERE character_id='.$_GET["character_id"].' AND chapter='.$_GET["chapter"].' ORDER BY id ASC';
                $result1 = $conn->query($sql1);
                while ($row1 = $result1->fetch_assoc()) {
                    echo '
                                        add_objective('.$row1["id"].', \''.$row1["content"].'\', '.$row1["points"].');';
                }
                echo '
                                    </script>
                                </div>
                            </center>
                        </div>';
            }

            echo '
                    </div>
                </div>
            </div>
        </section>';
		}
		?>