    <section>
        <div class="container">
            <div class="section-top-border">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-9 col-md-9 col-sm-10">
                        <img src="img/cover.jpg" style="width: 100%;"/>
                        <br><br>
                        <p>
                            <?php
                            $sql = 'SELECT * FROM background WHERE script_id='.$script_id.' AND type=1';
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
                            <h3>角色</h3>
                        </center>
                        <br>
                        <ol class="ordered-list">
                            <?php
                            $sql = 'SELECT id, name, description FROM characters WHERE script_id='.$script_id.' AND id<>'.$admin.' ORDER BY id ASC';
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                if ($row["id"] > 1) {
                                    echo '
                            <li><b>&nbsp;'.replace_text($pairs, $row["name"])."</b>：".replace_text($pairs, $row["description"]).'</li>';
                                }
                            }
                            ?>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </section>
