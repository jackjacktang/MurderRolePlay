    <?php
    if ($status == 1) {
        header("Location: home.php?tab=scripts&chapter=1");
    }
    ?>

    <section class="section-gap" style="display: <?php echo ((isset($_GET["clue_id"]))? "none":"block"); ?>;">
        <div class="container">
            <h4>你的剩余行动点：<?php echo $user_information["points"]; ?>&nbsp;&nbsp;&nbsp;<button class="genric-btn info circle small" onclick="open_share_modal()">赠送</button></h4>
            <br>
            <div class="row">
                <?php
                $chapter = ($status <= 3? 1:2);
                $locations = array();
                $sql = 'SELECT location_id, character_id FROM clues AS C LEFT JOIN locations AS L ON C.location_id=L.id WHERE C.script_id='.$script_id.' AND C.chapter='.$chapter.' AND L.character_id IS NOT NULL AND L.character_id<>'.$admin.' GROUP BY L.id HAVING COUNT(L.id)>0 ORDER BY L.character_id DESC';
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    array_push($locations, array($row["location_id"], $row["character_id"]));
                }
                $sql = 'SELECT location_id FROM clues AS C LEFT JOIN locations AS L ON C.location_id=L.id WHERE C.script_id='.$script_id.' AND C.chapter='.$chapter.' AND L.character_id IS NULL AND L.character_id<>'.$admin.' GROUP BY L.id HAVING COUNT(L.id)>0 ORDER BY L.id DESC';
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    array_push($locations, array($row["location_id"], $row["character_id"]));
                }
                foreach ($locations as $location) {
                    $sql = 'SELECT * FROM locations WHERE id='.$location[0];
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $location_chinese = $row["name"];
                    }
                    echo '
                <div class="single-testimonial item col-lg-3 col-md-3 col-sm-6 col-6">
                    <h5>'.($location[1] == $_SESSION["character_id"]? "你的房间":replace_text($pairs, $location_chinese)).'</h5>
                    <div class="row">';

                    $sql = 'SELECT * FROM clues WHERE chapter='.$chapter.' AND location_id='.$location[0];
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo '
                        <div class="col-6" style="padding: 15px 0px 0px 0px;">';

                        if ($duplicate) {
                            $sql1 = 'SELECT * FROM character_clue WHERE character_id='.$_SESSION["character_id"].' AND clue_id='.$row["id"];
                        }
                        else {
                            $sql1 = 'SELECT * FROM character_clue WHERE clue_id='.$row["id"];
                        }
                        $result1 = $conn->query($sql1);
                        if ($location[1] == $_SESSION["character_id"] || $result1->num_rows > 0) {
                            echo '
                            <a style="color: #BBBBBB;">'.$row["position"].'</a>';
                        }
                        else {
                            echo '
                            <a href="#" onclick="open_modal('.$row["id"].', \''.replace_text($pairs, $location_chinese)."：".$row["position"].'\', '.$row["points"].')">'.$row["position"].'</a>';
                        }
                        echo '
                        </div>';
                    }

                    echo '
                    </div>
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
                <form method="post" action="home.php?">
                    <input type="hidden" name="tab" value="find_clue">
                    <input type="hidden" name="clue_id" id="clue_id">
                    <button class="genric-btn info circle e-large" id="modal_confirm" name="find_clue">确定</button>
                </form>
                <button class="genric-btn info circle e-large" onclick="close_modal1()">关闭</button>
            </div>
        </div>
    </div>

    <div id="share_modal" class="modal" style="top: 10%;">
        <div class="modal-content col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-sm-10 offset-sm-1">
            <form method="post" action="home.php?">
                <div class="modal-header">
                    <h4 class="modal-title">赠送行动点</h4>
                    <span class="close" style="float: right;" onclick="close_share_modal()">&times;</span>
                </div>
                <div class="modal-body">
                    <p>
                        赠送点数：
                        <input required type="number" name="amount" min="1" max="<?php echo $user_information["points"]; ?>">
                    </p>
                    <p>
                        将行动点赠送给：<br>
                    <?php
                    $sql = 'SELECT id, name FROM characters WHERE script_id='.$script_id;
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        if ($row["id"] != $_SESSION["character_id"] && $row["id"] != $admin) {
                            echo '
                        <input required type="radio" name="share_target" value="'.$row["id"].'" style="margin-top: 10px;"> 【'.replace_text($pairs, $row["name"]).'】<br>';
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
        var share_modal = document.getElementById("share_modal");
        var modal_title = document.getElementById("modal_title");
        var modal_content = document.getElementById("modal_content");
        var remain_points = <?php echo $user_information["points"]; ?>;
        function open_modal(id, position, points) {
            modal.style.display = "block";
            share_modal.style.display = "none";
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
        function open_share_modal() {
            modal.style.display = "none";
            share_modal.style.display = "block";
        }
        function close_share_modal() {
            share_modal.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
            if (event.target == share_modal) {
                share_modal.style.display = "none";
            }
        }
    </script>
