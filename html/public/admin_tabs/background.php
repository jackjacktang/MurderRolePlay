    <?php
    if (isset($_GET["submit"])) {
        $sql = 'INSERT INTO background(id, content) VALUES(0, "'.$_GET["bg_story"].'") ON DUPLICATE KEY UPDATE content="'.$_GET["bg_story"].'"';
        $conn->query($sql);

        $counter = 1;
        while (isset($_GET["player_name".$counter])) {
            $sql = 'INSERT INTO players(id, username) VALUES('.$counter.', "'.$_GET["player_desc".$counter].'") ON DUPLICATE KEY UPDATE description="'.$_GET["player_desc".$counter].'"';
            $conn->query($sql);
            $counter = $counter + 1;
        }
    }

    $sql = "SELECT * FROM background";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $bg_story = $row["content"];
    }
    ?>
    
    <sectio>
        <div class="container">
            <div class="section-top-border">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-9 col-md-9 col-sm-10">
                        <img src="img/cover.jpg" style="width: 100%;"/>
                        <br><br>
                        <form action="admin.php" method="get">
                            <input type="hidden" name="tab" value="background">
                            <center>
                                <h4>添加/修改故事背景</h4><br><textarea name="bg_story" style="width: 90%; height: 300px; margin-bottom: 20px;"><?php echo $bg_story; ?></textarea>
                                <h4>添加/修改人物</h4><a onclick="add_role()">+</a>
                                <br>
                                <div id="role_area">
                                    <?php
                                    $sql = "SELECT * FROM players";
                                    $result = $conn->query($sql);
                                    $counter = 0;
                                    // $exist = 
                                    while ($row = $result->fetch_assoc()) {
                                        if ($counter > 0) {
                                            echo '
                                    <input name="player_name'.$counter.'" style="width: 10%;" value="'.$row["chinese_name"].'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="player_desc'.$counter.'" style="width: 60%;" value="'.$row["description"].'"><br>';
                                        }
                                        $counter = $counter + 1;
                                    }
                                    ?>
                                </div>
                                <button type="submit" name="submit" class="genric-btn info circle e-large col-10" style="font-size: 14pt; width: 50%;margin-top: 20px;">提交</button>
                            </center>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <script type="text/javascript">
        var exist = <?php echo $result->num_rows; ?>;
        function add_role() {
            var role_area = document.getElementById("role_area");
            role_area.innerHTML = role_area.innerHTML + '<input placeholder="人物" style="width: 10%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input placeholder="简介" style="width: 60%;"><br>';
        }
    </script>
