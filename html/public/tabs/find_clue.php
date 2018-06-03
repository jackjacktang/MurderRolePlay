    <?php
    if (isset($_GET["share"])) {
        $sql = 'UPDATE players SET points=points-'.$_GET["amount"].' WHERE username="'.$_SESSION["username"].'"';
        $conn->query($sql);
        $sql = 'UPDATE players SET points=points+'.$_GET["amount"].' WHERE username="'.$_GET["share_target"].'"';
        $conn->query($sql);
        header("Location: home.php?tab=find_clue");
    }

    if (isset($_GET["clue_id"])) {
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
        window.location = "home.php?tab=find_clue";
    </script>';
            }
            $conn->commit();
            echo '
    <div id="myModal2" class="modal" style="top: 20%; display: block;">
        <div class="modal-content col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-sm-10 offset-sm-1">
            <div class="modal-header">
                <h4 id="modal_title" class="modal-title">'.replace_text($pairs, $clue["location_c"]).'：'.$clue["position"].'</h4>
                <span class="close" style="float: right;" onclick="window.location=\'home.php?tab=find_clue\'">&times;</span>
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
        window.location = "home.php?tab=find_clue";
    </script>';
        }
    }
    ?>

    <section class="section-gap" style="display: <?php echo ((isset($_GET["clue_id"]))? "none":"block"); ?>;">
        <div class="container">
            <h4>你的剩余行动点：<?php echo $user_information["points"]; ?>&nbsp;&nbsp;&nbsp;<button class="genric-btn info circle small" onclick="open_share_modal()">赠送</button></h4>
            <br>
            <div class="row">
                <?php
                $sql = 'SELECT L.location_e, L.location_c, C.id, C.position, C.points FROM clues AS C LEFT JOIN location AS L ON C.location=L.location_e WHERE C.murder='.$murder.' ORDER BY L.order ASC, C.id ASC';
                $result = $conn->query($sql);
                $temp = "";
                while ($row = $result->fetch_assoc()) {
                    if ($row["location_e"] != $temp) {
                        if ($temp != "") {
                            echo '
                    </div>
                </div>';
                        }
                        echo '
                <div class="single-testimonial item col-lg-3 col-md-3 col-sm-6 col-6">
                    <h5>'.($row["location_e"]==$_SESSION["username"]? "你的房间":replace_text($pairs, $row["location_c"])).'</h5>
                    <div class="row">';
                    }
                    $temp = $row["location_e"];

                    echo '
                        <div class="col-6" style="padding: 15px 0px 0px 0px;">';
                    $sql1 = "SELECT * FROM player_clue WHERE clue=".$row["id"];
                    $result1 = $conn->query($sql1);
                    if ($result1->num_rows == 0) {
                        if ($row["location_e"] == $_SESSION["username"]) {
                            echo '<a style="color: #BBBBBB;">'.$row["position"].'</a>';
                        }
                        else {
                            echo
                            '<a href="#" onclick="open_modal('.$row["id"].', \''.replace_text($pairs, $row["location_c"])."：".$row["position"].'\', '.$row["points"].')">'.$row["position"].'</a>';
                        }
                    }
                    else {
                        echo '
                            <a style="color: #BBBBBB; text-decoration: line-through;">'.$row["position"].'</a>';
                    }
                        echo '
                        </div>';
                }
            ?>
            </div>
        </div>
    </section>

    <div id="myModal1" class="modal" style="top: 30%;">
        <div class="modal-content col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-10 offset-1">
            <div class="modal-header">
                <h4 id="modal_title" class="modal-title"></h4>
                <span class="close" style="float: right;" onclick="close_modal1()">&times;</span>
            </div>
            <div class="modal-body">
                <p id="modal_content"></p>
            </div>
            <div class="modal-footer justify-content-center" style="font-size: 14pt;">
                <form method="get" action="home.php?">
                    <input type="hidden" name="tab" value="find_clue">
                    <input type="hidden" name="clue_id" id="clue_id">
                    <button class="genric-btn info circle e-large" id="modal_confirm">确定</button>
                </form>
                <button class="genric-btn info circle e-large" onclick="close_modal1()">关闭</button>
            </div>
        </div>
    </div>

    <div id="share_modal" class="modal" style="top: 10%;">
        <div class="modal-content col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-sm-10 offset-sm-1">
            <form method="get" action="home.php?">
                <div class="modal-header">
                    <h4 class="modal-title">赠送行动点</h4>
                    <span class="close" style="float: right;" onclick="close_share_modal()">&times;</span>
                </div>
                <div class="modal-body">
                    <p>
                        赠送点数：
                        <input type="number" name="amount" min="1" max="<?php echo $user_information["points"]; ?>">
                    </p>
                    <p>
                        将行动点赠送给：<br>
                    <?php
                    $sql = "SELECT username, chinese_name FROM players";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        if ($row["username"] != $_SESSION["username"]) {
                            echo '
                        <input required type="radio" name="share_target" value="'.$row["username"].'" style="margin-top: 10px;"> 【'.$row["chinese_name"].'】<br>';
                        }
                    }
                    ?>
                    </p>
                </div>
                <div class="modal-footer justify-content-center" style="font-size: 14pt;">
                    <input type="hidden" name="tab" value="find_clue">
                    <button type="submit" class="genric-btn info circle e-large" name="share">确定</button>
                    <button type="button" class="genric-btn info circle e-large" onclick="close_share_modal()">取消</button>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        var modal = document.getElementById("myModal1");
        var modal_title = document.getElementById("modal_title");
        var modal_content = document.getElementById("modal_content");
        var remain_points = <?php echo $user_information["points"]; ?>;
        function open_modal(id, position, points) {
            modal.style.display = "block";
            modal_title.innerHTML = position;
            if (points > remain_points) {
                document.getElementById("modal_confirm").style.display = "none";
                modal_content.innerHTML = "你剩余 " + remain_points + " 个行动点，这个线索将耗费你 " + points + " 个行动点。你的行动点不足。";
            }
            else {
                document.getElementById("clue_id").value = id;
                document.getElementById("modal_confirm").style.display = "block";
                modal_content.innerHTML = "你剩余 " + remain_points + " 个行动点，这个线索将耗费你 " + points + " 个行动点。是否继续？";
            }
        }
        function close_modal1() {
            modal.style.display = "none";
        }
        function close_modal2() {
            document.getElementById("myModal2").style.display = "none";
        }
        function open_share_modal() {
            share_modal.style.display = "block";
        }
        function close_share_modal() {
            share_modal.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
