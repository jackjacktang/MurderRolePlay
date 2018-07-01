    <?php
    $character_id = $_SESSION["character_id"];
    $sql = 'SELECT * FROM character_clue AS CC LEFT JOIN clues AS C ON CC.clue_id=C.id LEFT JOIN locations AS L ON C.location_id=L.id WHERE L.character_id='.$admin.' AND CC.character_id='.$character_id;
    $result = $conn->query($sql);
    if ($result->num_rows == 0) $secret = False;
    else $secret = True;
    $sql = 'SELECT * FROM clues WHERE chapter=1 AND script_id='.$script_id;
    $result = $conn->query($sql);
    if ($result->num_rows == 0) $chapter_1_clue = False;
    else $chapter_1_clue = True;
    $sql = 'SELECT * FROM clues WHERE chapter=2 AND script_id='.$script_id;
    $result = $conn->query($sql);
    if ($result->num_rows == 0) $chapter_2_clue = False;
    else $chapter_2_clue = True;
    if (isset($_GET["chapter"])) {
        $chapter = $_GET["chapter"];
    }
    else {
        if ($chapter_1_clue) $chapter = 1;
        else $chapter = 2;
    }
    ?>

    <script type="text/javascript">
        var clue_information = [];
        <?php
        $sql = 'SELECT C.id, C.location_id, L.name, C.position, C.points, C.description, C.file_path, C.unlock_id, C.unlock_characters FROM character_clue AS CC LEFT JOIN clues AS C ON CC.clue_id=C.id LEFT JOIN locations AS L ON C.location_id=L.id WHERE C.chapter='.$chapter.' AND CC.character_id='.$character_id;
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo '
        clue_information['.$row["id"].'] = [\''.$row["name"].'\', \''.$row["position"].'\', '.$row["points"].', \''.$row["description"].'\', \''.$row["file_path"].'\', '.$row["unlock_id"].', \''.$row["unlock_characters"].'\', '.$row["location_id"].'];';
        }
        ?>
    </script>

    <br><br><br>
    <section style="display: <?php echo (($chapter_1_clue&&$chapter_2_clue)? "block":"none");?>">
        <div class="container">
            <div class="section-top-border">
                <div class="row" style="justify-content: center;">
                    <button class="genric-btn <?php echo ($chapter==1? "disable":"info"); ?> circle e-large col-5" style="font-size: 14pt;" onclick="window.location='home.php?tab=your_clue&chapter=1'">第一幕</button>
                    <div class="offset-1"></div>
                    <button class="genric-btn <?php echo ($chapter==2? "disable":"info"); ?> circle e-large col-5" style="font-size: 14pt;" onclick="window.location='home.php?tab=your_clue&chapter=2'">第二幕</button>
                </div>
            </div>
        </div>
    </section>

    <section style="display: <?php echo ($secret? "block":"none");?>">
        <div class="container">
            <div class="section-top-border">
                <div class="row d-flex justify-content-center">
                    <center class="col-12">
                        <h3 style="margin-bottom: 20px;">秘密线索</h3>
                    </center>
                    <?php
                    $sql1 = 'SELECT CC.owner, C.id, L.name, C.position, C.description, C.file_path, C.unlock_id, C.unlock_characters FROM character_clue AS CC LEFT JOIN clues AS C ON CC.clue_id=C.id LEFT JOIN locations AS L ON C.location_id=L.id WHERE C.chapter='.$chapter.' AND CC.character_id='.$character_id.' AND L.character_id='.$admin.' ORDER BY L.character_id ASC, L.section_id ASC, C.id ASC';
                    $result1 = $conn->query($sql1);
                    $counter = 0;
                    while ($row1 = $result1->fetch_assoc()) {
                        $file_path = $row1["file_path"];

                        if ($counter % 2 == 0) $offset = 0;
                        else $offset = 1;
                        echo '
                    <div class="col-lg-5 offset-lg-'.$offset.' col-md-5 offset-md-'.$offset.' col-sm-10 offset-sm-1">
                        <div class="row" style="border: 1px solid #BBBBBB; margin-top: 20px; padding: 5px 0px 5px 0px;">
                            <div class="col-3" style="padding: 0px 0px 0px 5px; cursor: pointer;" onclick="open_img_modal('.$row1["id"].')">';
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
                            <div class="col-8" style="border-right: 1px solid #BBBBBB; cursor: pointer;" onclick="open_img_modal('.$row1["id"].')">
                                <h4>'.$row1["position"].'</h4>
                                <p>'.replace_text($pairs, $row1["description"]).'</p>
                            </div>
                            <div class="col-1" style="padding: 0px 0px 0px 0px; text-align: center;">';
                        if ($row1["owner"] == 0) {
                            echo '
                                <button type="button" class="genric-btn danger circle small" style="width: 25px; height: 25px; padding: 0px;" onclick="open_delete_modal('.$row1["id"].')">
                                    <i class="fa fa-minus"></i>
                                </button>';
                        }
                        echo '
                                <button class="genric-btn info circle small" style="width: 25px; height: 25px; padding: 0px; margin-top: 7px;" onclick="open_share_modal('.$row1["id"].')">
                                    <i class="fa fa-envelope"></i>
                                </button>';
                        echo '
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
                    ?>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="section-top-border">
                <div class="row d-flex justify-content-center">
                    <center class="col-12">
                        <h3 style="margin-bottom: 20px;">线索</h3>
                    </center>
                    <?php
                    $sql1 = 'SELECT CC.owner, C.id, L.name, C.position, C.description, C.file_path, C.unlock_id, C.unlock_characters FROM character_clue AS CC LEFT JOIN clues AS C ON CC.clue_id=C.id LEFT JOIN locations AS L ON C.location_id=L.id WHERE C.chapter='.$chapter.' AND CC.character_id='.$character_id.' AND (L.character_id IS NULL OR L.character_id<>'.$admin.') ORDER BY L.character_id ASC, L.section_id ASC, C.id ASC';
                    $result1 = $conn->query($sql1);
                    $counter = 0;
                    while ($row1 = $result1->fetch_assoc()) {
                        $file_path = $row1["file_path"];

                        if ($counter % 2 == 0) $offset = 0;
                        else $offset = 1;
                        echo '
                    <div class="col-lg-5 offset-lg-'.$offset.' col-md-5 offset-md-'.$offset.' col-sm-10 offset-sm-1">
                        <div class="row" style="border: 1px solid #BBBBBB; margin-top: 20px; padding: 5px 0px 5px 0px;">
                            <div class="col-3" style="padding: 0px 0px 0px 5px; cursor: pointer;" onclick="open_img_modal('.$row1["id"].')">';
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
                            <div class="col-8" style="border-right: 1px solid #BBBBBB; cursor: pointer;" onclick="open_img_modal('.$row1["id"].')">
                                <h4>'.replace_text($pairs, $row1["name"])."：".$row1["position"].'</h4>
                                <p>'.replace_text($pairs, $row1["description"]).'</p>
                            </div>
                            <div class="col-1" style="padding: 0px 0px 0px 0px; text-align: center;">';
                        if ($row1["owner"] == 0) {
                            echo '
                                <button type="button" class="genric-btn danger circle small" style="width: 25px; height: 25px; padding: 0px;" onclick="open_delete_modal('.$row1["id"].')">
                                    <i class="fa fa-minus"></i>
                                </button>';
                        }
                        echo '
                                <button class="genric-btn info circle small" style="width: 25px; height: 25px; padding: 0px; margin-top: 7px;" onclick="open_share_modal('.$row1["id"].')">
                                    <i class="fa fa-envelope"></i>
                                </button>';
                        if ($row1["unlock_id"] != 0) {
                            $unlock_characters = explode(",", $row1["unlock_characters"]);
                            foreach ($unlock_characters as $unlock_character) {
                                if ($unlock_character == $_SESSION["character_id"]) {
                                    $sql2 = 'SELECT * FROM character_clue WHERE character_id='.$_SESSION["character_id"].' AND clue_id='.$row1["unlock_id"];
                                    $result2 = $conn->query($sql2);
                                    if ($result2->num_rows == 0) $unlocked = False;
                                    else $unlocked = True;
                                    $sql2 = 'SELECT C.id, C.points FROM clues AS C LEFT JOIN locations AS L ON C.location_id=L.id WHERE C.id='.$row1["unlock_id"];
                                    $result2 = $conn->query($sql2);
                                    while ($row2 = $result2->fetch_assoc()) {
                                        if ($unlocked) {
                                            echo '
                                <button class="genric-btn info circle small" style="width: 25px; height: 25px; padding: 0px; margin-top: 7px;" onclick="open_img_modal('.$row1["unlock_id"].')">
                                    <i class="fa fa-unlock"></i>
                                </button>';
                                        }
                                        else {
                                            echo '
                                <button class="genric-btn info circle small" style="width: 25px; height: 25px; padding: 0px; margin-top: 7px;" onclick="open_unlock_modal('.$row1["id"].', '.$row2["id"].', '.$row2["points"].')">
                                    <i class="fa fa-unlock"></i>
                                </button>';
                                        }
                                    }
                                }
                            }
                        }
                        echo '
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
                    ?>
                </div>
            </div>
        </div>
    </section>

    <div id="img_modal" class="modal" style="top: 8%;">
        <div class="modal-content col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-sm-10 offset-sm-1">
            <div class="modal-header">
                <h4 id="img_modal_title" class="modal-title"></h4>
                <span class="close" style="float: right;" onclick="close_img_modal()">&times;</span>
            </div>
            <div class="modal-body">
                <img src="" class="col-lg-6 offset-lg-3 col-md-8 offset-md-2" id="img_modal_img">
                <br><br>
                <p id="img_modal_content"></p>
            </div>
        </div>
    </div>

    <div id="delete_modal" class="modal" style="top: 30%;">
        <div class="modal-content col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-sm-10 offset-sm-1">
            <div class="modal-header">
                <h4 id="delete_modal_title" class="modal-title"></h4>
                <span class="close" style="float: right;" onclick="close_delete_modal()">&times;</span>
            </div>
            <div class="modal-body">
                <p>这个线索将会被删除, 是否继续？</p>
            </div>
            <div class="modal-footer justify-content-center" style="font-size: 14pt;">
                <form method="post" action="home.php?">
                    <input type="hidden" name="tab" value="your_clue">
                    <input type="hidden" name="clue_id" id="delete_clue_id">
                    <input type="hidden" name="chapter" value="<?php echo $chapter; ?>">
                    <button class="genric-btn info circle e-large" id="modal_confirm" name="delete">确定</button>
                </form>
                <button class="genric-btn info circle e-large" onclick="close_delete_modal()">取消</button>
            </div>
        </div>
    </div>

    <div id="share_modal" class="modal" style="top: 15%;">
        <div class="modal-content col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-sm-10 offset-sm-1">
            <form method="post" action="home.php?">
                <div class="modal-header">
                    <h4 id="share_modal_title" class="modal-title"></h4>
                    <span class="close" style="float: right;" onclick="close_share_modal()">&times;</span>
                </div>
                <div class="modal-body">
                    <p>将这条线索分享给：</p>
                    <p style="cursor: pointer;" onclick="select_all()">全选</p>
                    <?php
                    $sql = "SELECT id, name FROM characters WHERE script_id=".$script_id;
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        if ($row["id"] != $_SESSION["character_id"] && $row["id"] != $admin) {
                            echo '
                    <input class="checkbox" type="checkbox" name="share_targets[]" value="'.$row["id"].'" style="margin-bottom: 10px;"> 【'.replace_text($pairs, $row["name"]).'】<br>';
                        }
                    }
                    ?>
                </div>
                <div class="modal-footer justify-content-center" style="font-size: 14pt;">
                    <input type="hidden" name="tab" value="your_clue">
                    <input type="hidden" name="clue_id" id="share_clue_id">
                    <input type="hidden" name="chapter" value="<?php echo $chapter; ?>">
                    <button type="submit" class="genric-btn info circle e-large" id="modal_confirm" name="share">确定</button>
                    <button type="button" class="genric-btn info circle e-large" onclick="close_share_modal()">取消</button>
                </div>
            </form>
        </div>
    </div>

    <div id="unlock_modal" class="modal" style="top: 30%;">
        <div class="modal-content col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-10 offset-1">
            <div class="modal-header">
                <h4 id="unlock_modal_title" class="modal-title"></h4>
                <span class="close" style="float: right;" onclick="close_unlock_modal()">&times;</span>
            </div>
            <div class="modal-body">
                <p id="unlock_modal_content"></p>
            </div>
            <div class="modal-footer justify-content-center" style="font-size: 14pt;">
                <form method="post" action="home.php?">
                    <input type="hidden" name="tab" value="your_clue">
                    <input type="hidden" name="clue_id" id="clue_id">
                    <input type="hidden" name="chapter" value="<?php echo $chapter; ?>">
                    <button class="genric-btn info circle e-large" id="unlock_confirm" name="unlock">确定</button>
                </form>
                <button class="genric-btn info circle e-large" onclick="close_unlock_modal()">关闭</button>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var img_modal = document.getElementById("img_modal");
        var delete_modal = document.getElementById("delete_modal");
        var share_modal = document.getElementById("share_modal");
        var unlock_modal = document.getElementById("unlock_modal");
        var remain_points = <?php echo $user_information["points"]; ?>;
        function open_img_modal(id) {
            img_modal.style.display = "block";
            if (clue_information[id][7] == 0) {
                document.getElementById("img_modal_title").innerHTML = clue_information[id][1];
            }
            else {
                document.getElementById("img_modal_title").innerHTML = clue_information[id][0] + "：" + clue_information[id][1];
            }
            if (clue_information[id][4] == "") {
                document.getElementById("img_modal_img").src = "img/alt.png";
            }
            else {
                document.getElementById("img_modal_img").src = clue_information[id][4];
            }
            document.getElementById("img_modal_content").innerHTML = clue_information[id][3];
        }
        function close_img_modal() {
            img_modal.style.display = "none";
        }
        function open_delete_modal(id) {
            document.getElementById("delete_modal_title").innerHTML = clue_information[id][0] + "：" + clue_information[id][1];
            document.getElementById("delete_clue_id").value = id;
            delete_modal.style.display = "block";
        }
        function close_delete_modal() {
            delete_modal.style.display = "none";
        }
        function open_share_modal(id) {
            document.getElementById("share_modal_title").innerHTML = clue_information[id][0] + "：" + clue_information[id][1];
            document.getElementById("share_clue_id").value = id;
            share_modal.style.display = "block";
        }
        function close_share_modal() {
            share_modal.style.display = "none";
        }
        function open_unlock_modal(id, unlock_id, points) {
            document.getElementById("unlock_modal_title").innerHTML = clue_information[id][0] + "：" + clue_information[id][1];
            if (points > remain_points) {
                document.getElementById("unlock_confirm").style.display = "none";
                document.getElementById("unlock_modal_content").innerHTML = "解锁这个线索需要 " + points + " 个额外行动点。你的行动点不足。";
            }
            else {
                document.getElementById("unlock_modal_content").innerHTML = "解锁这个线索需要 " + points + " 个额外行动点。是否继续？";
            }
            document.getElementById("clue_id").value = unlock_id;
            unlock_modal.style.display = "block";
        }
        function close_unlock_modal() {
            unlock_modal.style.display = "none";
        }
        function hide_background() {
            var sections = document.getElementsByTagName("section");
            for (var i = 0; i < sections.length; i++) {
                sections[i].style.visibility = "hidden";
            }
        }
        function show_background() {
            var sections = document.getElementsByTagName("section");
            for (var i = 0; i < sections.length; i++) {
                sections[i].style.visibility = "visible";
            }
        }
        window.onclick = function(event) {
            if (event.target == img_modal) {
                img_modal.style.display = "none";
            }
            if (event.target == delete_modal) {
                delete_modal.style.display = "none";
            }
            if (event.target == share_modal) {
                share_modal.style.display = "none";
            }
        }

        var is_select_all = false;
        function select_all() {
            checkboxes = document.getElementsByClassName("checkbox");
            console.log(checkboxes);
            for(var i=0, n=checkboxes.length;i<n;i++) {
                if (is_select_all) {
                    checkboxes[i].checked = false;
                }
                else {
                    checkboxes[i].checked = true;
                }
            }
            if (is_select_all) is_select_all = false;
            else is_select_all = true;
        }
    </script>
