    <br><br><br>
    <section>
        <div class="container">
            <div class="section-top-border">
                <div class="row d-flex justify-content-between">
                    <div class="col-lg-10 offset-lg-1 col-md-10 offset-md-1 col-sm-10 offset-sm-1">
                        <h2 style="margin-bottom: 20px;">第一幕：</h2>
                        <?php
                        $sql = 'SELECT * FROM objectives WHERE murder=1 AND person="'.$_SESSION["username"].'"';
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            if ($row["points"] >0 ) {
                                echo '
                            <input type="checkbox"> '.replace_text($pairs, $row["content"])."<br>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>