    <?php
    $sql = "SELECT * FROM background";
    $result = $conn->query($sql);
    $background = "";
    while ($row = $result->fetch_assoc()) {
        $background = $row["content"];
    }
    ?>
    
    <script type="text/javascript">
        function add_role(id, username, password, name, preferred_name, description, organizor) {
            var role_area = document.getElementById("role_area");
            var li = document.createElement("li");
            li.style.textAlign = "left";
            li.style.marginBottom = "20px";
            role_area.appendChild(li);
            if (id == -1) {
                id = max_character + 1;
            }
            if (organizor) {
                li.innerHTML = li.innerHTML + '<label style="width: 10%; text-align: right;">用户名：&nbsp;</label><input readonly style="width: 10%; border: 0px;" value="' + username + '" name="character1_username">';
            }
            else {
                li.innerHTML = li.innerHTML + '<label style="width: 10%; text-align: right;">用户名：&nbsp;</label><input style="width: 10%;" value="' + username + '" name="character'+ id + '_username" id="character'+ id + '_username" maxlength=20>';
            }
            li.innerHTML = li.innerHTML + '<label style="width: 10%; text-align: right;">密码：&nbsp;</label><input style="width: 10%;" value="' + password + '" name="character'+ id + '_password" maxlength=20>';
            li.innerHTML = li.innerHTML + '<label style="width: 10%; text-align: right;">姓名：&nbsp;</label><input style="width: 10%;" value="' + name + '" name="character'+ id + '_name" id="character'+ id + '_name" maxlength=20>';
            li.innerHTML = li.innerHTML + '<label style="width: 15%; text-align: right;">推荐名称：&nbsp;</label><input style="width: 10%;" value="' + preferred_name + '" name="character'+ id + '_preferred_name" maxlength=20>';
            if (!organizor) {
                li.innerHTML = li.innerHTML + '<button type="button" onclick="open_modal(' + id + ', \'character\')" class="genric-btn danger circle small" style="width: 25px; height: 25px; padding: 0px; margin-left: 10%;"><i class="fa fa-minus"></i></button>';
            }
            else {
                li.innerHTML = li.innerHTML + '<div style="width: 15%;"></div>';
            }
            li.innerHTML = li.innerHTML + '<label style="width: 10%; text-align: right;">简介：&nbsp;</label><input style="width: 75%;" value="' + description + '" name="character'+ id + '_description" maxlength=100>';
            if (id > max_character) {
                max_character = id;
                document.getElementById("max_character").value = max_character;
            }
        }

        function add_map(id, description, file_path) {
            var map_area = document.getElementById("map_area");
            var div = document.createElement("div");
            div.style.textAlign = "left";
            div.style.marginTop = "20px";
            map_area.appendChild(div);
            if (id == -1) {
                id = max_map + 1;
            }
            div.innerHTML = div.innerHTML + '<label style="width: 10%; text-align: right;">描述：&nbsp;</label><input style="width: 30%;" value="' + description + '" name="map'+ id + '_description" maxlength=20 id="map'+ id + '_description">';
            div.innerHTML = div.innerHTML + '<label style="width: 10%; text-align: right;">图片：&nbsp;</label><input type="file" name="map'+ id + '_image" onchange="preview(this, ' + id + ')">';
            div.innerHTML = div.innerHTML + '<button type="button" onclick="open_modal(' + id + ', \'map\')" class="genric-btn danger circle small" style="width: 25px; height: 25px; padding: 0px;"><i class="fa fa-minus"></i></button>';
            div.innerHTML = div.innerHTML + '<center style="margin-top: 20px;"><img src="'+ file_path + '" id="map' + id + '_preview"></center>';
            if (id > max_map) {
                max_map = id;
                document.getElementById("max_map").value = max_map;
            }
        }

        function preview(input, id) {
            document.getElementById("map" + id + "_preview").src = URL.createObjectURL(input.files[0]);
        }

        function copyHTML() {
            document.getElementById("background_hide").value = document.getElementById("background_show").innerHTML;
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

        function open_modal(id, section) {
            var modal = document.getElementById("myModal");
            modal.style.display = "block";
            if (section == "character") {
                document.getElementById("modal_title").innerHTML = "确认要删除这名玩家么？";
                var username = document.getElementById("character" + id + "_username").value;
                var name = document.getElementById("character" + id + "_name").value;
                document.getElementById("modal_content").innerHTML = '用户名：' + username + '<br>姓名：' + name;
            }
            else{
                document.getElementById("modal_title").innerHTML = "确认要删除这张图片么？";
                var description = document.getElementById("map" + id + "_description").value;
                document.getElementById("modal_content").innerHTML = description;
            }
            document.getElementById("modal_confirm").value = id;
            document.getElementById("modal_confirm").setAttribute("name", "delete_" + section);
        }

        function close_modal() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            var modal = document.getElementById("myModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

    <form action="admin_tabs/request.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="tab" value="background">
        <input type="hidden" name="max_character" value="1" id="max_character">
        <input type="hidden" name="max_map" value="0" id="max_map">
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
                    <button class="genric-btn info circle e-large" id="modal_confirm">确定</button>
                    <button class="genric-btn info circle e-large" type="button" onclick="close_modal()">关闭</button>
                </div>
            </div>
        </div>
        <section>
            <div class="container">
                <div class="section-top-border">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-9 col-md-9 col-sm-10">
                            <img src="img/cover.jpg" style="width: 100%;"/>
                            <br><br>
                            
                            <center>
                                <h3>添加/修改故事背景</h3><br>
                                <input type="hidden" id="background_hide" name="bg_story" value='<?php echo $background; ?>'>
                                <div onclick="copyHTML()" style="width: 90%; height: 40px; border: 1px solid #BBBBBB; text-align: left; font-size: 14px;">
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; font-weight: bold;" onclick="document.execCommand('bold',false,null);">B</button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; font-style: italic;" onclick="document.execCommand('italic',false,null);">I</button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; text-decoration: underline;" onclick="document.execCommand('underline',false,null);">U</button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand('fontSize',false,'4');">A+</button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="change_font()">A-</button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand('justifyLeft',false,null);"><i class="fa fa-align-left"></i></button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand('justifyCenter',false,null);"><i class="fa fa-align-center"></i></button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand('justifyRight',false,null);"><i class="fa fa-align-right"></i></button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="insertOl()"><i class="fa fa-list-ol"></i></button>
                                    <button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="insertUl()"><i class="fa fa-list-ul"></i></button>
                                </div> 
                                <div id="background_show" style="width: 90%; height: 300px; margin-bottom: 20px; border: 1px solid #BBBBBB; text-align: left; overflow: auto; font-size: 14px;" contenteditable="true" onkeyup="copyHTML()"><?php echo $background; ?></div>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="section-top-border">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-9 col-md-9 col-sm-10">
                            <center>
                                <h3>添加/修改人物
                                    <button class="genric-btn info circle small" style="width: 25px; height: 25px; padding: 0px;" type="button" onclick="add_role(-1,'','','','','',false)">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </h3>
                                <br>
                                <div>
                                    <ol class="ordered-list" id="role_area" start="0">
                                    <?php
                                    $sql = "SELECT * FROM characters";
                                    $result = $conn->query($sql);
                                    echo '
                                        <script type="text/javascript">
                                            var max_character = 1;';
                                    while ($row = $result->fetch_assoc()) {
                                        $points = $row["points"];
                                        echo '
                                            add_role('.$row["id"].', "'.$row["username"].'", "'.$row["password"].'", "'.$row["name"].'", "'.$row["preferred_name"].'", "'.$row["description"].'", '.($row["id"]==1? "true":"false").');';
                                    }
                                    echo '
                                        </script>';
                                    ?>
                                    </ol>
                                </div>
                                <h3>每个角色的初始行动点</h3>
                                <input type="number" name="points" style="margin-top: 20px; margin-bottom: 20px;" value="<?php echo $points; ?>"><br>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="section-top-border">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-9 col-md-9 col-sm-10">
                            <center>
                                <h3>添加/修改图片（地图、案发现场图等）
                                    <button class="genric-btn info circle small" style="width: 25px; height: 25px; padding: 0px;" type="button" onclick="add_map(-1, '', '')">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </h3>
                                <div id="map_area">
                                    <?php
                                    $sql = "SELECT * FROM maps";
                                    $result = $conn->query($sql);
                                    echo '
                                        <script type="text/javascript">
                                            var max_map = 0;';
                                    while ($row = $result->fetch_assoc()) {
                                        echo '
                                            add_map('.$row["id"].', "'.$row["description"].'", "'.$row["file_path"].'");';
                                    }
                                    echo '
                                        </script>';
                                    ?>
                                </div>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </section>


