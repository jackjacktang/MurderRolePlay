    <div id="myModal" class="modal" style="top: 20%;">
        <div class="modal-content col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-sm-10 offset-sm-1">
            <div class="modal-header">
                <h4 id="modal_title" class="modal-title"></h4>
                <span class="close" style="float: right;">&times;</span>
            </div>
            <div class="modal-body">
                <img src="" class="col-lg-8 offset-lg-2 col-md-10 offset-md-1" id="modal_img">
                <br><br>
                <p id="modal_content"></p>
            </div>            
        </div>
    </div>
    
    <script type="text/javascript">
    var modal = document.getElementById("myModal");
    var modal_title = document.getElementById("modal_title");
    var modal_content = document.getElementById("modal_content");
    var span = document.getElementsByClassName("close")[0];
    var img = document.getElementById("modal_img");
    function open_modal(id, position, content) {
        modal.style.display = "block";
        modal_title.innerHTML = "你的房间：" + position;
        img.src = "files/clues/" + id + ".jpg";
        modal_content.innerHTML = content;
        hide_background();
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
    span.onclick = function() {
        modal.style.display = "none";
        show_background();
    }
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
            show_background();
        }
    }
    </script>

    <br><br><br>
    <section id="background2">
        <div class="container">
            <div class="section-top-border">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-9 col-md-9 col-sm-10">
                        <img src="img/cover.jpg" style="width: 100%;"/>
                        <br><br>
                        <p>
                            <?php
                            $sql = "SELECT * FROM background WHERE murder=2";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                echo replace_text($pairs, $row["content"]);
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="story2">
        <div class="container">
            <div class="section-top-border">
                <div class="row d-flex justify-content-center">
                    <h2 class="col-lg-9 col-md-9 col-sm-10">你的故事：</h2>
                    <br><br><br>
                    <p class="col-lg-9 col-md-9 col-sm-10">
                        <?php echo replace_text($pairs, $user_information["story2"])."\n"; ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="timeline2">
        <div class="container">
            <div classv class="section-top-border">
                <div class="row d-flex justify-content-center">
                    <h2 class="col-lg-9 col-md-9 col-sm-10">你的时间线：</h2>
                    <br><br><br>
                    <p class="col-lg-9 col-md-9 col-sm-10">
                        <?php
                        $sql = 'SELECT * FROM timeline WHERE murder=2 AND person="'.$user_information["username"].'" ORDER BY hour ASC, minute ASC';
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            $minute = $row["minute"];
                            echo $row["hour"].":".($minute<10? "0".$minute:$minute)."，".replace_text($pairs, $row["content"])."。<br>\n";
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="clues2">
        <div class="container">
            <div classv class="section-top-border">
                <div class="row d-flex justify-content-center">
                    <h2 class="col-lg-9 col-md-9 col-sm-10">你房间的线索：</h2>
                    <br><br><br>
                    <?php
                    $sql = 'SELECT id, position, content1 FROM clues WHERE murder=2 AND location="'.$_SESSION["username"].'" ORDER BY id ASC';
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()) {
                        echo '
                    <div class="col-lg-5 col-md-5 col-sm-10">
                        <div class="single-offer d-flex flex-row pb-30">
                            <div class="icon">';
                            if (!file_exists("files/clues/".$row["id"].".jpg")) {
                                echo '
                                <img style="width: 95px; height: 80px;" src="files/clues/alt.png">';
                            }
                            else {
                                echo '
                                <img onclick="open_modal('.$row["id"].', \''.$row["position"].'\', \''.$row["content1"].'\')" style="width: 95px; height: 80px; cursor: pointer;" src="files/clues/'.$row["id"].'.jpg">';
                            }
                            echo '
                            </div>
                            <div class="desc">
                                <h4>'.$row["position"].'</h4>
                                <p>'.replace_text($pairs, $row["content1"]).'。</p>
                            </div>
                        </div>
                    </div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <section id="objectives2">
        <div class="container">
            <div classv class="section-top-border">
                <div class="row d-flex justify-content-center">
                    <h2 class="col-lg-9 col-md-9 col-sm-10">你的任务：</h2>
                    <br><br><br>
                    <ul class="col-lg-8 col-md-9 col-sm-10 unordered-list">
                        <?php
                        $sql = 'SELECT * FROM objectives WHERE murder=2 AND person="'.$user_information["username"].'" ORDER BY id ASC';
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            echo "<li><span>".replace_text($pairs, $row["content"]);
                            if ($row["points"] > 0) {
                                echo "（".$row["points"]."分）。";
                            }
                            echo "</span></li>\n";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>
