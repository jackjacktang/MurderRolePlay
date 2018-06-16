	<br><br><br>

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

        function open_modal1(id, location_id) {
            var modal1 = document.getElementById("myModal1");
            var modal2 = document.getElementById("myModal2");
            modal1.style.display = "block";
            modal2.style.display = "none";
            if (location_id == 0) {
                document.getElementById("secret_clue").style.display = "none";
            }
            else {
                document.getElementById("secret_clue").style.display = "block";
            }
            document.getElementById("id1").value = id;
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
            var modal1 = document.getElementById("myModal1");
            var modal2 = document.getElementById("myModal2");
            modal1.style.display = "none";
            modal2.style.display = "block";
            document.getElementById("id2").value = id;
            document.getElementById("modal_content2").innerHTML = clue_information[id][0] + "：" + clue_information[id][1];
        }

        function close_modal2() {
            var modal2 = document.getElementById("myModal2");
            modal2.style.display = "none";
        }

        window.onclick = function(event) {
            var modal1 = document.getElementById("myModal1");
            var modal2 = document.getElementById("myModal2");
            if (event.target == modal1) {
                modal1.style.display = "none";
            }
            if (event.target == modal2) {
                modal2.style.display = "none";
            }
        }

        function preview(input) {
            document.getElementById("image").src = URL.createObjectURL(input.files[0]);
        }

        function copyHTML1() {
            document.getElementById("self_description_hidden").value = document.getElementById("self_description_show").innerHTML;
        }

        function copyHTML2() {
            document.getElementById("description_hidden").value = document.getElementById("description_show").innerHTML;
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
	
    <?php
    $locations = array();
    array_push($locations, array("id"=>0, "name"=>"秘密线索"));
	$sql = 'SELECT * FROM locations WHERE id<0 ORDER BY id DESC';
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		array_push($locations, array("id"=>$row["id"], "name"=>$row["name"]));
	}
	$sql = 'SELECT * FROM locations WHERE id>0 ORDER BY id ASC';
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		array_push($locations, array("id"=>$row["id"], "name"=>$row["name"]));
	}
    
	foreach ($locations as $location) {
		echo '
	<section>
        <div class="container">
            <div class="section-top-border">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-9 col-md-9 col-sm-10">
                        <center>
                            <h3 style="margin-bottom: 20px;">
                            '.$location["name"].'
                                <button class="genric-btn info circle small" style="width: 25px; height: 25px; padding: 0px;" type="button" onclick="open_modal1(-1, '.$location["id"].')">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </h3>
                        </center>
                    </div>';
        $sql = 'SELECT C.id, C.location_id, L.name, C.position, C.points, C.self_description, C.description, C.file_path, C.unlock_id, C.unlock_characters FROM clues AS C LEFT JOIN locations AS L ON C.location_id=L.id WHERE C.chapter='.$_GET["chapter"].' AND L.id='.$location["id"];
        $result = $conn->query($sql);
        $counter = 0;
        while ($row = $result->fetch_assoc()) {
            if ($counter % 2 == 0) $offset = 0;
            else $offset = 1;
            echo '
                    <div class="col-lg-5 offset-lg-'.$offset.' col-md-5 offset-md-'.$offset.' col-sm-10 offset-sm-1">
                        <div class="row" style="border-top: 1px solid #BBBBBB; border-bottom: 1px solid #BBBBBB; margin-top: 20px; padding: 5px 0px 5px 0px;">
                            <div class="col-3" style="padding: 0px 0px 0px 5px; border-left: 1px solid #BBBBBB; cursor: pointer;" onclick="open_modal1('.$row["id"].', '.$row["location_id"].')">';
            if (!file_exists($row["file_path"])) {
                echo '
                                <img style="width: 100%;" src="img/alt.png">';
            }
            else {
                echo '
                                <img style="width: 100%;" src="'.$row["file_path"].'">';
            }
            echo '
                            </div>
                            <div class="col-8" style="border-right: 1px solid #BBBBBB; cursor: pointer;" onclick="open_modal1('.$row["id"].', '.$row["location_id"].')">
                                <h4>'.$row["position"].'</h4>
                                <p>'.$row["description"].'</p>
                            </div>
                            <div class="col-1" style="padding: 0px 0px 0px 0px; border-right: 1px solid #BBBBBB; text-align: center;">
                                <button class="genric-btn danger circle small" style="width: 25px; height: 25px; padding: 0px;" onclick="open_modal2('.$row["id"].')">
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
        echo '
                </div>
            </div>
        </div>
    </section>';
    }
    ?>

    <form action="admin_tabs/request.php" method="post" enctype="multipart/form-data" style="width: 100%;">
        <input type="hidden" name="tab" value="clues">
        <input type="hidden" name="chapter" value="<?php echo $_GET["chapter"]; ?>">
        <div id="myModal1" class="modal" style="top: 5%; width: 100%;">
            <div class="modal-content col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-10 offset-1">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title1"></h4>
                    <span class="close" style="float: right;" onclick="close_modal1()">&times;</span>
                </div>
                <div class="modal-body">
                    <div>
                        <input type="hidden" name="id" id="id1">
                        <input type="hidden" name="location_id" id="location_id">
                        <label style="text-align: left; width: 120px;">位置</label><input name="position" id="position" style="width: 200px;" type="text" maxlength="20"><br>
                        <label style="text-align: left; width: 120px;">所需行动点：</label><input style="width: 200px;" name="points" id="points" type="number" value=1><br>
                        <label style="text-align: left; width: 120px;">自己看到的描述：</label>
                        <!-- <input style="width: 200px;" name="self_description" id="self_description" type="text" maxlength="300"><br> -->
                        <input type="hidden" id="self_description_hidden" name="self_description">
                        <div onclick="copyHTML1()" style="width: 100%; height: 40px; border: 1px solid #BBBBBB; text-align: left; font-size: 14px;">
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; font-weight: bold;" onclick="document.execCommand('bold',false,null);">B</button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; font-style: italic;" onclick="document.execCommand('italic',false,null);">I</button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; text-decoration: underline;" onclick="document.execCommand('underline',false,null);">U</button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand('justifyLeft',false,null);"><i class="fa fa-align-left"></i></button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand('justifyCenter',false,null);"><i class="fa fa-align-center"></i></button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand('justifyRight',false,null);"><i class="fa fa-align-right"></i></button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="insertOl()"><i class="fa fa-list-ol"></i></button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="insertUl()"><i class="fa fa-list-ul"></i></button>
                        </div> 
                        <div id="self_description_show" style="width: 100%; height: 100px; margin-bottom: 20px; border: 1px solid #BBBBBB; text-align: left; overflow: auto; font-size: 14px;" contenteditable="true" onkeyup="copyHTML1()"></div>
                        <label style="text-align: left; width: 120px;">描述：</label>
                        <!-- <input style="width: 200px;" name="description" id="description" type="text" maxlength="300"><br> -->
                        <input type="hidden" id="description_hidden" name="description" value=''>
                        <div onclick="copyHTML2()" style="width: 100%; height: 40px; border: 1px solid #BBBBBB; text-align: left; font-size: 14px;">
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; font-weight: bold;" onclick="document.execCommand('bold',false,null);">B</button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; font-style: italic;" onclick="document.execCommand('italic',false,null);">I</button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; text-decoration: underline;" onclick="document.execCommand('underline',false,null);">U</button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand('justifyLeft',false,null);"><i class="fa fa-align-left"></i></button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand('justifyCenter',false,null);"><i class="fa fa-align-center"></i></button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand('justifyRight',false,null);"><i class="fa fa-align-right"></i></button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="insertOl()"><i class="fa fa-list-ol"></i></button>
                            <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="insertUl()"><i class="fa fa-list-ul"></i></button>
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
    </form>

    <form action="admin_tabs/request.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="tab" value="clues">
        <input type="hidden" name="chapter" value="<?php echo $_GET["chapter"]; ?>">
        <input type="hidden" name="id" id="id2">
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
    </form>

    <form action="admin.php" method="get">
        <input type="hidden" name="tab" value="clues">
        <input type="hidden" name="chapter" value="<?php echo $_GET["chapter"]; ?>">