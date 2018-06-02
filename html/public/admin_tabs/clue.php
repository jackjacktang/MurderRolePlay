    <?php
    if (isset($_GET["submit"])) {
        $sql = 'INSERT INTO background(id, content) VALUES(0, "'.$_GET["bg_story"].'") ON DUPLICATE KEY UPDATE content="'.$_GET["bg_story"].'"';
        $conn->query($sql);
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
                                <h4>添加/修改故事背景</h4><br><textarea name="bg_story" style="width: 80%; height: 300px;"><?php echo $bg_story; ?></textarea>
                                <div class="mt-10">
                                <center>
                                    <button type="submit" name="submit" class="genric-btn info circle e-large col-10" style="font-size: 14pt;width: 50%;">提交</button>
                                </center>
                            </center>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>
