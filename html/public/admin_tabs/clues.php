	<br><br><br>

    <script type="text/javascript">
        function open_modal1(id, location_id, location_name, position, points, self_description, description, file_path, unlock_id, unlock_characters) {
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
            document.getElementById("id").value = id;
            document.getElementById("location_id").value = location_id;
            document.getElementById("modal_title1").innerHTML = location_name;
            document.getElementById("position").value = position;
            document.getElementById("points").value = points;
            document.getElementById("self_description").value = self_description;
            document.getElementById("description").value = description;
            if (file_path == "") {
                document.getElementById("image").src = "img/alt.png";
            }
            else {
                document.getElementById("image").src = file_path;
            }

            var options = document.getElementsByClassName("options");
            for (var i = 0; i < options.length; i++) {
                if (options[i].value == unlock_id) {
                    options[i].selected = true;
                }
                else {
                    options[i].selected = false;
                }
            }

            var checkboxes = document.getElementsByClassName("checkboxes");
            var unlock_characters = unlock_characters.split(",");
            console.log(checkboxes);
            console.log(unlock_characters);
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

        function open_modal2(id, location_id, location_name, position, points, self_description, description, file_path, unlock_id, unlock_character) {
            var modal1 = document.getElementById("myModal1");
            var modal2 = document.getElementById("myModal2");
            modal1.style.display = "none";
            modal2.style.display = "block";
            document.getElementById("id").value = id;
            document.getElementById("location_id").value = location_id;
            document.getElementById("modal_title1").innerHTML = location_name;
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
                                <button class="genric-btn info circle small" style="width: 25px; height: 25px; padding: 0px;" type="button" onclick="open_modal1(-1, '.$location["id"].', \''.$location["name"].'\', \'\', 1, \'\', \'\', \'\', 0, \'\')">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </h3>
                        </center><br>';
        $sql = 'SELECT C.id, C.location_id, L.name, C.position, C.points, C.self_description, C.description, C.file_path, C.unlock_id, C.unlock_characters FROM clues AS C LEFT JOIN locations AS L ON C.location_id=L.id WHERE C.chapter='.$_GET["chapter"].' AND L.id='.$location["id"];
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo '
                        <div style="width: 49%; display: inline-block; margin-top: 20px; border: 1px solid #CCCCCC;">
                            <div style="width: 20%; display: inline-block; vertical-align: top; cursor: pointer;" onclick="open_modal1('.$row["id"].', '.$row["location_id"].', \''.$row["name"].'\', \''.$row["position"].'\', '.$row["points"].', \''.$row["self_description"].'\', \''.$row["description"].'\', \''.$row["file_path"].'\', '.$row["unlock_id"].', \''.$row["unlock_characters"].'\')">
                                <img src="'.(is_null($row["file_path"])? "img/alt.png":$row["file_path"]).'" style="width: 100%;">
                            </div>
                            <div style="width: 65%; display: inline-block; vertical-align: top; margin-left: 3%; margin-right: 3%;">
                                <b>'.$row["position"].'：</b><br>
                                '.$row["description"].'
                            </div>
                            <div style="width: 7%; display: inline-block;">
                                <button type="button" onclick="open_modal2()" class="genric-btn danger circle small" style="width: 25px; height: 25px; padding: 0px; margin-top: 10px;">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>';
        }
    	echo '
                    </div>
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
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="location_id" id="location_id">
                        <label style="text-align: left; width: 120px;">位置</label><input name="position" id="position" style="width: 200px;" type="text" maxlength="20"><br>
                        <label style="text-align: left; width: 120px;">所需行动点：</label><input style="width: 200px;" name="points" id="points" type="number" value=1><br>
                        <label style="text-align: left; width: 120px;">自己看到的描述：</label><input style="width: 200px;" name="self_description" id="self_description" type="text" maxlength="300"><br>
                        <label style="text-align: left; width: 120px;">描述：</label><input style="width: 200px;" name="description" id="description" type="text" maxlength="300"><br>
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
        <div id="myModal2" class="modal" style="top: 30%;">
            <div class="modal-content col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-10 offset-1">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title2"></h4>
                    <span class="close" style="float: right;" onclick="close_modal2()">&times;</span>
                </div>
                <div class="modal-body">
                    <p id="modal_content2"></p>
                </div>
                <div class="modal-footer justify-content-center" style="font-size: 14pt;">
                    <button class="genric-btn info circle e-large" name="submit2">保存</button>
                    <button class="genric-btn info circle e-large" type="button" onclick="close_modal2()">关闭</button>
                </div>
            </div>
        </div>
    </form>

    <form action="admin.php" method="get">
        <input type="hidden" name="tab" value="clues">
        <input type="hidden" name="chapter" value="<?php echo $_GET["chapter"]; ?>">