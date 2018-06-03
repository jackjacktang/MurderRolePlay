    <?php
    if (isset($_GET["delete"])) {
        $sql = 'DELETE FROM player_clue WHERE person="'.$_SESSION["username"].'" AND clue='.$_GET["clue_id"];
        $conn->query($sql);
        header("Location: home.php?tab=your_clue");
    }
    if (isset($_GET["share"])) {
        foreach ($_GET["share_targets"] as $share_target) {
            $sql = 'INSERT IGNORE INTO player_clue(person, clue) VALUES("'.$share_target.'", '.$_GET["clue_id"].')';
            $conn->query($sql);
            header("Location: home.php?tab=your_clue");
        }
    }
    if (isset($_GET["unlock"])) {
        $clue_id = $_GET["clue_id"];
        try {
            $conn->begin_transaction();
            $sql1 = 'SELECT * FROM player_clue WHERE clue='.$clue_id;
            $result = $conn->query($sql1);
            if ($result->num_rows == 0) {
                $sql2 = 'SELECT C.position, C.content2, C.points, L.location_c FROM clues as C LEFT JOIN location AS L on C.location=L.location_e WHERE id='.$clue_id;
                $result = $conn->query($sql2);
                while ($row = $result->fetch_assoc()) {
                    $clue = $row;
                }
                if ($clue["points"] > $user_information["points"]) {
                    echo '
    <script type="text/javascript">
        alert("作弊有意思么？");
        window.location = "home.php?tab=your_clue";
    </script>';
                }
                else {
                    $sql3 = 'INSERT INTO player_clue(person, clue) VALUES("'.$_SESSION["username"].'", '.$clue_id.')';
                    $conn->query($sql3);
                    $sql3 = 'UPDATE players SET points=points-'.$clue["points"].' WHERE username="'.$_SESSION["username"].'"';
                    $conn->query($sql3);
                }
            }
            else {
                echo '
    <script type="text/javascript">
        alert("不好意思哦，手慢了。请选择其它的线索。");
        window.location = "home.php?tab=your_clue";
    </script>';
            }
            $conn->commit();
            echo '
    <div id="myModal2" class="modal" style="top: 20%; display: block;">
        <div class="modal-content col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-sm-10 offset-sm-1">
            <div class="modal-header">
                <h4 id="modal_title" class="modal-title">'.replace_text($pairs, $clue["location_c"]).'：'.$clue["position"].'</h4>
                <span class="close" style="float: right;" onclick="window.location=\'home.php?tab=your_clue\'">&times;</span>
            </div>
            <div class="modal-body">
                <img src="files/clues/'.(file_exists("files/clues/".$clue_id.".jpg")? ($clue_id.".jpg"):"alt.png").'" class="col-lg-8 offset-lg-2 col-md-10 offset-md-1" id="modal_img">
            </div>
            <div class="modal-footer justify-content-between" id="modal_footer">
                '.replace_text($pairs, $clue["content2"]).'
            </div>
        </div>
    </div>';
        }
        catch (Exception $e) {
            $conn->rollBack();
            echo '
    <script type="text/javascript">
        alert("不好意思哦，手慢了。请选择其它的线索。");
        window.location = "home.php?tab=your_clue";
    </script>';
        }
    }
    ?>

    <br><br><br>
    <section>
        <div class="container">
            <div class="section-top-border">
                <div class="row d-flex justify-content-between">
                    <h2 class="offset-lg-1 offset-md-1 offset-sm-1 col-10">第一幕：</h2>
                    <br><br><br>
                    <?php
                    $sql = 'SELECT C.id, L.location_c, C.position, C.content2, C.content3, C.prerequisite FROM player_clue as PC LEFT JOIN clues as C on PC.clue=C.id LEFT JOIN location AS L ON C.location=L.location_e WHERE (murder=1 OR murder=3) AND PC.person="'.$_SESSION["username"].'" ORDER BY time DESC';
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo '
                    <div class="col-lg-5 offset-lg-1 col-md-5 offset-md-1 col-sm-10 offset-sm-1">
                        <div class="row" style="border-top: 1px solid #BBBBBB; border-bottom: 1px solid #BBBBBB; margin-top: 20px; padding: 5px 0px 5px 0px;">
                            <div class="col-3" style="padding: 0px 0px 0px 5px; border-left: 1px solid #BBBBBB; cursor: pointer;" onclick="open_img_modal('.(file_exists("files/clues/".$row["id"].".jpg")? $row["id"]:0).', \''.$row["location_c"].'\',\''.$row["position"].'\', \''.$row["content2"].'\')">';
                        if (!file_exists("files/clues/".$row["id"].".jpg")) {
                            echo '
                                <img style="width: 100%;" src="files/clues/alt.png">';
                        }
                        else {
                            echo '
                                <img style="width: 100%;" src="files/clues/'.$row["id"].'.jpg">';
                        }
                        echo '
                            </div>
                            <div class="col-8" style="border-right: 1px solid #BBBBBB; cursor: pointer;" onclick="open_img_modal('.(file_exists("files/clues/".$row["id"].".jpg")? $row["id"]:0).', \''.replace_text($pairs, $row["location_c"]).'\',\''.$row["position"].'\', \''.replace_text($pairs, $row["content2"]).'\')">
                                <h4>'.replace_text($pairs, $row["location_c"])."：".$row["position"].'</h4>
                                <p>'.replace_text($pairs, $row["content2"]).'。</p>
                            </div>
                            <div class="col-1" style="padding: 0px 0px 0px 0px; border-right: 1px solid #BBBBBB; text-align: center;">';
                        $sql1 = 'SELECT person FROM player_clue WHERE clue='.$row["id"].' ORDER BY time ASC LIMIT 1';
                        $result1 = $conn->query($sql1);
                        while ($row1 = $result1->fetch_assoc()) {
                            if ($row1["person"] != $_SESSION["username"]) {
                                echo '
                                <button class="genric-btn danger circle small" style="width: 25px; height: 25px; padding: 0px;" onclick="open_delete_modal('.$row["id"].', \''.replace_text($pairs, $row["location_c"]).'\', \''.$row["position"].'\')">
                                    <i class="fa fa-minus"></i>
                                </button>';
                            }
                        }
                        echo '
                                <button class="genric-btn info circle small" style="width: 25px; height: 25px; padding: 0px; margin-top: 7px;" onclick="open_share_modal('.$row["id"].', \''.replace_text($pairs, $row["location_c"]).'\', \''.$row["position"].'\')">
                                    <i class="fa fa-envelope"></i>
                                </button>';
                        if ($row["prerequisite"] > 0 && $row["prerequisite"] == $user_information["skill"]) {
                            $sql1 = 'SELECT * FROM player_clue AS PC LEFT JOIN clues AS C ON PC.clue=C.id WHERE PC.person="'.$_SESSION["username"].'" AND C.id='.$row["content3"];
                            $result1 = $conn->query($sql1);
                            if ($result1->num_rows == 0) {
                                $sql2 = 'SELECT points FROM clues WHERE id='.$row["content3"];
                                $result2 = $conn->query($sql2);
                                while ($row2 = $result2->fetch_assoc()) {
                                    echo '
                                <button class="genric-btn info circle small" style="width: 25px; height: 25px; padding: 0px; margin-top: 7px;" onclick="open_unlock_modal('.$row["content3"].', \''.replace_text($pairs, $row["location_c"]).'\', \''.$row["position"].'\', '.$row2["points"].')">
                                    <i class="fa fa-unlock"></i>
                                </button>';
                                }
                            }
                        }
                        echo '
                            </div>
                        </div>
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
                <div class="row d-flex justify-content-between">
                    <h2 class="offset-lg-1 offset-md-1 offset-sm-1 col-10">第二幕：</h2>
                    <br><br><br>
                    <?php
                    $sql = 'SELECT C.id, L.location_c, C.position, C.content2, C.content3, C.prerequisite FROM player_clue as PC LEFT JOIN clues as C on PC.clue=C.id LEFT JOIN location AS L ON C.location=L.location_e WHERE (murder=2 OR murder=4) AND PC.person="'.$_SESSION["username"].'" ORDER BY time DESC';
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo '
                    <div class="col-lg-5 offset-lg-1 col-md-5 offset-md-1 col-sm-10 offset-sm-1">
                        <div class="row" style="border-top: 1px solid #BBBBBB; border-bottom: 1px solid #BBBBBB; margin-top: 20px; padding: 5px 0px 5px 0px;">
                            <div class="col-3" style="padding: 0px 0px 0px 5px; border-left: 1px solid #BBBBBB; cursor: pointer;" onclick="open_img_modal('.(file_exists("files/clues/".$row["id"].".jpg")? $row["id"]:0).', \''.$row["location_c"].'\',\''.$row["position"].'\', \''.$row["content2"].'\')">';
                        if (!file_exists("files/clues/".$row["id"].".jpg")) {
                            echo '
                                <img style="width: 100%;" src="files/clues/alt.png">';
                        }
                        else {
                            echo '
                                <img style="width: 100%;" src="files/clues/'.$row["id"].'.jpg">';
                        }
                        echo '
                            </div>
                            <div class="col-8" style="border-right: 1px solid #BBBBBB; cursor: pointer;" onclick="open_img_modal('.(file_exists("files/clues/".$row["id"].".jpg")? $row["id"]:0).', \''.replace_text($pairs, $row["location_c"]).'\',\''.$row["position"].'\', \''.replace_text($pairs, $row["content2"]).'\')">
                                <h4>'.replace_text($pairs, $row["location_c"])."：".$row["position"].'</h4>
                                <p>'.replace_text($pairs, $row["content2"]).'。</p>
                            </div>
                            <div class="col-1" style="padding: 0px 0px 0px 0px; border-right: 1px solid #BBBBBB; text-align: center;">';
                        $sql1 = 'SELECT person FROM player_clue WHERE clue='.$row["id"].' ORDER BY time ASC LIMIT 1';
                        $result1 = $conn->query($sql1);
                        while ($row1 = $result1->fetch_assoc()) {
                            if ($row1["person"] != $_SESSION["username"]) {
                                echo '
                                <button class="genric-btn danger circle small" style="width: 25px; height: 25px; padding: 0px;" onclick="open_delete_modal('.$row["id"].', \''.replace_text($pairs, $row["location_c"]).'\', \''.$row["position"].'\')">
                                    <i class="fa fa-minus"></i>
                                </button>';
                            }
                        }
                        echo '
                                <button class="genric-btn info circle small" style="width: 25px; height: 25px; padding: 0px; margin-top: 7px;" onclick="open_share_modal('.$row["id"].', \''.replace_text($pairs, $row["location_c"]).'\', \''.$row["position"].'\')">
                                    <i class="fa fa-envelope"></i>
                                </button>';
                        if ($row["prerequisite"] > 0 && $row["prerequisite"] == $user_information["skill"]) {
                            $sql1 = 'SELECT * FROM player_clue AS PC LEFT JOIN clues AS C ON PC.clue=C.id WHERE PC.person="'.$_SESSION["username"].'" AND C.id='.$row["content3"];
                            $result1 = $conn->query($sql1);
                            if ($result1->num_rows == 0) {
                                $sql2 = 'SELECT points FROM clues WHERE id='.$row["content3"];
                                $result2 = $conn->query($sql2);
                                while ($row2 = $result2->fetch_assoc()) {
                                    echo '
                                <button class="genric-btn info circle small" style="width: 25px; height: 25px; padding: 0px; margin-top: 7px;" onclick="open_unlock_modal('.$row["content3"].', \''.replace_text($pairs, $row["location_c"]).'\', \''.$row["position"].'\', '.$row2["points"].')">
                                    <i class="fa fa-unlock"></i>
                                </button>';
                                }
                            }
                        }
                        echo '
                            </div>
                        </div>
                    </div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <div id="img_modal" class="modal" style="top: 20%;">
        <div class="modal-content col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-sm-10 offset-sm-1">
            <div class="modal-header">
                <h4 id="img_modal_title" class="modal-title"></h4>
                <span class="close" style="float: right;" onclick="close_img_modal()">&times;</span>
            </div>
            <div class="modal-body">
                <img src="" class="col-lg-8 offset-lg-2 col-md-10 offset-md-1" id="img_modal_img">
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
                <form method="get" action="home.php?">
                    <input type="hidden" name="tab" value="your_clue">
                    <input type="hidden" name="clue_id" id="delete_clue_id">
                    <button class="genric-btn info circle e-large" id="modal_confirm" name="delete">确定</button>
                </form>
                <button class="genric-btn info circle e-large" onclick="close_delete_modal()">取消</button>
            </div>
        </div>
    </div>

    <div id="share_modal" class="modal" style="top: 15%;">
        <div class="modal-content col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-sm-10 offset-sm-1">
            <form method="get" action="home.php?">
                <div class="modal-header">
                    <h4 id="share_modal_title" class="modal-title"></h4>
                    <span class="close" style="float: right;" onclick="close_share_modal()">&times;</span>
                </div>
                <div class="modal-body">
                    <p>将这条线索分享给：</p>
                    <?php
                    $sql = "SELECT username, chinese_name FROM players";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        if ($row["username"] != $_SESSION["username"]) {
                            echo '
                    <input type="checkbox" name="share_targets[]" value="'.$row["username"].'" style="margin-bottom: 10px;"> 【'.$row["chinese_name"].'】<br>';
                        }
                    }
                    ?>
                </div>
                <div class="modal-footer justify-content-center" style="font-size: 14pt;">
                    <input type="hidden" name="tab" value="your_clue">
                    <input type="hidden" name="clue_id" id="share_clue_id">
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
                <form method="get" action="home.php?">
                    <input type="hidden" name="tab" value="your_clue">
                    <input type="hidden" name="clue_id" id="clue_id">
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
        function open_img_modal(id, location, position, content) {
            img_modal.style.display = "block";
            document.getElementById("img_modal_title").innerHTML = location + "：" + position;
            if (id == 0) {
                document.getElementById("img_modal_img").src = "files/clues/alt.png";
            }
            else {
                document.getElementById("img_modal_img").src = "files/clues/" + id + ".jpg";
            }
            document.getElementById("img_modal_content").innerHTML = content;
            hide_background();
        }
        function close_img_modal() {
            img_modal.style.display = "none";
            show_background();
        }
        function open_delete_modal(id, location, position) {
            document.getElementById("delete_modal_title").innerHTML = location + "：" + position;
            document.getElementById("delete_clue_id").value = id;
            delete_modal.style.display = "block";
        }
        function close_delete_modal() {
            delete_modal.style.display = "none";
        }
        function open_share_modal(id, location, position) {
            document.getElementById("share_modal_title").innerHTML = location + "：" + position;
            document.getElementById("share_clue_id").value = id;
            share_modal.style.display = "block";
        }
        function close_share_modal() {
            share_modal.style.display = "none";
        }
        function open_unlock_modal(id, location, position, points) {
            document.getElementById("unlock_modal_title").innerHTML = location + "：" + position;
            if (points > remain_points) {
                document.getElementById("unlock_confirm").style.display = "none";
                document.getElementById("unlock_modal_content").innerHTML = "解锁这个线索需要 " + points + " 个额外行动点。你的行动点不足。";
            }
            else {
                document.getElementById("unlock_modal_content").innerHTML = "解锁这个线索需要 " + points + " 个额外行动点。是否继续？";
            }
            document.getElementById("clue_id").value = id;
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
                show_background();
            }
            if (event.target == delete_modal) {
                delete_modal.style.display = "none";
                show_background();
            }
        }
    </script>
