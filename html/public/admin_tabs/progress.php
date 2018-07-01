    <br><br><br>

    <?php
    $sql = 'SELECT * FROM status WHERE script_id='.$script_id.' AND name=1';
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $value = $row["value"];
    }
    ?>


    <!-- <button type="submit" class="genric-btn info circle e-large col-10" style="font-size: 14pt; width: 30%; margin-bottom: 30px;">保存</button> -->

    <form action="admin_tabs/request.php" method="post">
        <input type="hidden" name="tab" value="progress">
        <section>
            -<div class="container">
                <div class="section-top-border">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-9 col-md-9 col-sm-10">
                            <center>
                                <input type="range" min="1" max="7" value="<?php echo $value; ?>" name="value" oninput="change_value()" id="slider">
                            </center>
                            <p id="display"></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            -<div class="container">
                <div class="section-top-border">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-9 col-md-9 col-sm-10">
                            <center>
                                <button type="button" onclick="open_modal1()" class="genric-btn danger circle e-large col-10" style="font-size: 14pt; width: 30%; margin-bottom: 30px;">重置剧本</button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script type="text/javascript">
            change_value();
            function change_value() {
                var value = document.getElementById("slider").value;
                if (value == 1) {
                    document.getElementById("display").innerHTML = "初始";
                }
                else if (value == 2) {
                    document.getElementById("display").innerHTML = "第一幕搜证";
                }
                else if (value == 3) {
                    document.getElementById("display").innerHTML = "第二幕开启";
                }
                else if (value == 4) {
                    document.getElementById("display").innerHTML = "第二幕搜证";
                }
                else if (value == 5) {
                    document.getElementById("display").innerHTML = "投票";
                }
                else if (value == 6) {
                    document.getElementById("display").innerHTML = "投票结果";
                }
                else if (value == 7) {
                    document.getElementById("display").innerHTML = "真相";
                }
            }
        </script>

        <div id="resetPopUp" class="modal" style="top: 30%;">
            <div class="modal-content col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-10 offset-1">
                <div class="modal-header">
                    <h4 id="modal_title" class="modal-title">是否重置剧本</h4>
                    <span class="close" style="float: right;" onclick="close_modal1()">&times;</span>
                </div>
                <div class="modal-footer justify-content-center" style="font-size: 14pt;">
                    <button class="genric-btn info circle e-large" name="reset">确定</button>
                    <button type="button" class="genric-btn info circle e-large" onclick="close_modal1()">取消</button>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            var modal1 = document.getElementById("resetPopUp");
            function open_modal1(id, position, points) {
                modal1.style.display = "block";
                modal2.style.display = "none";
            }
            function close_modal1() {
                modal1.style.display = "none";
            }
        </script>