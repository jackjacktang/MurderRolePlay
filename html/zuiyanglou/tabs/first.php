    <br><br><br>
    <section id="story">
        <div class="container">
            <div class="section-top-border">
                <div class="row d-flex justify-content-center">
                    <h2 class="col-lg-9 col-md-9 col-sm-10">人物背景：</h2>
                    <br><br><br>
                    <p class="col-lg-9 col-md-9 col-sm-10">
                        <?php echo replace_text($pairs, $user_information["story"])."\n"; ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="recent">
        <div class="container">
            <div classv class="section-top-border">
                <div class="row d-flex justify-content-center">
                    <h2 class="col-lg-9 col-md-9 col-sm-10">最近的事情：</h2>
                    <br><br><br>
                    <p class="col-lg-9 col-md-9 col-sm-10">
                        <?php echo replace_text($pairs, $user_information["recent"]); ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="today1">
        <div class="container">
            <div classv class="section-top-border">
                <div class="row d-flex justify-content-center">
                    <h2 class="col-lg-9 col-md-9 col-sm-10">今天（时逢立秋）：</h2>
                    <br><br><br>
                    <p class="col-lg-9 col-md-9 col-sm-10">
                        <?php echo replace_text($pairs, $user_information["today1"]); ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="people">
        <div class="container">
            <div classv class="section-top-border">
                <div class="row d-flex justify-content-center">
                    <h2 class="col-lg-9 col-md-9 col-sm-10">你已经知道的其他人：</h2>
                    <br><br><br>
                    <ul class="col-lg-8 col-md-9 col-sm-10 ordered-list">
                        <?php
                        $sql = 'SELECT * FROM people WHERE person="'.$user_information["username"].'" ORDER BY sequence ASC';
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            echo "<li><span>".replace_text($pairs, $row["content"])."。</span></li>\n";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="performance">
        <div class="container">
            <div classv class="section-top-border">
                <div class="row d-flex justify-content-center">
                    <h2 class="col-lg-9 col-md-9 col-sm-10">你的表现：</h2>
                    <p class="col-lg-9 col-md-9 col-sm-10" style="margin-top:20px;"><u>以下“表现”，你在此阶段必须这样做，你需要隐瞒没有被被人发现的真相</u></p>
                    <br><br><br>
                    <ul class="col-lg-8 col-md-9 col-sm-10 ordered-list">
                        <?php
                        $sql = 'SELECT * FROM performance WHERE person="'.$user_information["username"].'" ORDER BY sequence ASC';
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            echo "<li><span>".replace_text($pairs, $row["content"])."。</span></li>\n";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="objectives1">
        <div class="container">
            <div classv class="section-top-border">
                <div class="row d-flex justify-content-center">
                    <h2 class="col-lg-9 col-md-9 col-sm-10">你的目的（一）：</h2>
                    <br><br><br>
                    <ul class="col-lg-8 col-md-9 col-sm-10 ordered-list">
                        <?php
                        $sql = 'SELECT * FROM objectives WHERE murder=1 AND person="'.$user_information["username"].'" ORDER BY id ASC';
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
