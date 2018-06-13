    <script type="text/javascript">
        function add_section(chapter, id, sequence, type, title) {
            var section_area = document.getElementById("section_area" + chapter);
            if (id == -1) {
                id = max + 1;
            }
            if (sequence == -1) {
                sequence = maxs[chapter - 1] + 1;
            }
            var div = document.createElement("div");
            div.style.marginTop = "20px";
            section_area.appendChild(div);
            div.innerHTML = div.innerHTML + '<label style="width: 10%; text-align: right;">顺序：&nbsp;</label><input type="number" style="width: 5%;" value="' + sequence + '" name="section'+ id + '_sequence">';
            div.innerHTML = div.innerHTML + '<label style="width: 10%; text-align: right;">标题：&nbsp;</label><input style="width: 20%;" value="' + title + '" name="section'+ id + '_title" id="section'+ id + '_title" maxlength="20">';
            if (type == 1) {
                type_chinese = "普通";
            }
            else if (type == 2) {
                type_chinese = "时间线";
                document.getElementById("button_" + chapter + "_2").style.display = "none";
            }
            else if (type == 3) {
                type_chinese = "房间线索";
                document.getElementById("button_" + chapter + "_3").style.display = "none";
            }
            else if (type == 4) {
                type_chinese = "任务";
                document.getElementById("button_" + chapter + "_4").style.display = "none";
            }
            div.innerHTML = div.innerHTML + '<label style="width: 10%; text-align: right;">类别：&nbsp;</label><label style="width: 10%; text-align: left;">' + type_chinese + '</label>';
            div.innerHTML = div.innerHTML + '<button type="button" onclick="open_modal(' + id +')" class="genric-btn danger circle small" style="width: 25px; height: 25px; padding: 0px; margin-left: 5%;"><i class="fa fa-minus"></i></button>';
            
            div.innerHTML = div.innerHTML + '<input type="hidden" value="' + chapter + '" name="section'+ id + '_chapter">';
            div.innerHTML = div.innerHTML + '<input type="hidden" value="' + type + '" name="section'+ id + '_type">';

            if (id > max) {
                max = id;
                document.getElementById("max_section").value = id;
            }
            if (sequence > maxs[chapter - 1]) {
                maxs[chapter - 1] = sequence;
            }
        }

        function open_modal(id) {
            var modal = document.getElementById("myModal");
            modal.style.display = "block";
            var title = document.getElementById("section" + id + "_title").value;
            document.getElementById("modal_content").innerHTML = '标题：' + title;
            document.getElementById("modal_confirm").value = id;
        }

        function close_modal() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            var modal = document.getElementById("myModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

    <br><br><br>
    <form action="admin_tabs/request.php" method="post">
        <input type="hidden" name="tab" value="sections">
        <input type="hidden" name="max_section" value="0" id="max_section">
        <div id="myModal" class="modal" style="top: 30%;">
            <div class="modal-content col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-10 offset-1">
                <div class="modal-header">
                    <h4 class="modal-title">确认要删除这个章节么？</h4>
                    <span class="close" style="float: right;" onclick="close_modal()">&times;</span>
                </div>
                <div class="modal-body">
                    <p id="modal_content"></p>
                </div>
                <div class="modal-footer justify-content-center" style="font-size: 14pt;">
                    <button class="genric-btn info circle e-large" id="modal_confirm" name="delete">确定</button>
                    <button class="genric-btn info circle e-large" type="button" onclick="close_modal()">关闭</button>
                </div>
            </div>
        </div>
        <section>
            <div class="container">
                <div class="section-top-border">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-9 col-md-9 col-sm-10">
                            <center>
                                <h3 style="margin-bottom: 20px">第一幕</h3>
                                <button type="button" id="button_1_1" onclick="add_section(1, -1, -1, 1, '')" class="genric-btn info-border circle e-large col-10" style="font-size: 10pt; width: 170px;">添加普通章节</button>
                                <button type="button" id="button_1_2" onclick="add_section(1, -1, -1, 2, '')" class="genric-btn info-border circle e-large col-10" style="font-size: 10pt; width: 170px;">添加时间线</button>
                                <button type="button" id="button_1_3" onclick="add_section(1, -1, -1, 3, '')" class="genric-btn info-border circle e-large col-10" style="font-size: 10pt; width: 170px;">添加房间线索</button>
                                <button type="button" id="button_1_4" onclick="add_section(1, -1, -1, 4, '')" class="genric-btn info-border circle e-large col-10" style="font-size: 10pt; width: 170px;">添加任务</button>
                                <div id="section_area1">
                                    <?php
                                    $sql = "SELECT * FROM sections WHERE chapter=1 ORDER BY sequence ASC";
                                    $result = $conn->query($sql);
                                    echo '
                                    <script type="text/javascript">
                                        var max = 0;
                                        var maxs = [0, 0]';
                                    while ($row = $result->fetch_assoc()) {
                                        echo '
                                        add_section(1, '.$row["id"].', '.$row["sequence"].', '.$row["type"].', "'.$row["title"].'");';
                                    }
                                    echo '
                                    </script>';
                                    ?>
                                </div>
                            </center>
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
                                <h3 style="margin-bottom: 20px">第二幕</h3>
                                <button type="button" id="button_2_1" onclick="add_section(2, -1, -1, 1, '')" class="genric-btn info-border circle e-large col-10" style="font-size: 10pt; width: 170px;">添加普通章节</button>
                                <button type="button" id="button_2_2" onclick="add_section(2, -1, -1, 2, '')" class="genric-btn info-border circle e-large col-10" style="font-size: 10pt; width: 170px;">添加时间线</button>
                                <button type="button" id="button_2_3" onclick="add_section(2, -1, -1, 3, '')" class="genric-btn info-border circle e-large col-10" style="font-size: 10pt; width: 170px;">添加房间线索</button>
                                <button type="button" id="button_2_4" onclick="add_section(2, -1, -1, 4, '')" class="genric-btn info-border circle e-large col-10" style="font-size: 10pt; width: 170px;">添加任务</button>
                                <div id="section_area2">
                                    <?php
                                    $sql = "SELECT * FROM sections WHERE chapter=2 ORDER BY sequence ASC";
                                    $result = $conn->query($sql);
                                    echo '
                                    <script type="text/javascript">';
                                    while ($row = $result->fetch_assoc()) {
                                        echo '
                                        add_section(2, '.$row["id"].', '.$row["sequence"].', '.$row["type"].', "'.$row["title"].'");';
                                    }
                                    echo '
                                    </script>';
                                    ?>
                                </div>
                            </center>
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
                                <button type="submit" name="submit" class="genric-btn info circle e-large col-10" style="font-size: 14pt; width: 50%;">保存</button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>