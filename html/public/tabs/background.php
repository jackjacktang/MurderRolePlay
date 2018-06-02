    <sectio>
        <div class="container">
            <div class="section-top-border">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-9 col-md-9 col-sm-10">
                        <img src="img/cover.jpg" style="width: 100%;"/>
                        <br><br>
                        <p>
                            <?php
                            $sql = "SELECT * FROM background WHERE murder=1";
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

    <section>
        <div class="container">
            <div class="section-top-border">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-9 col-md-9 col-sm-10">
                        <center>
                            <h3>角色剧本</h3>
                            （注：民国前出生的角色的年龄皆是“ 虚岁”，以“ 出生年” 为一岁。）
                        </center>
                        <br>
                        <ul class="ordered-list">
                            <?php
                            $sql = "SELECT chinese_name, description FROM players ORDER BY sequence ASC";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                echo '
                            <li> '.$row["chinese_name"]."：".$row["description"].'</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
