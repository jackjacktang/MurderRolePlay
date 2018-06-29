    <script type="text/javascript">
        var id;
        var counter;

        function add_section(chapter, id, sequence, type, title, content, sub_title) {
            var section_area = document.getElementById("section_area" + chapter);
            if (sequence == -1) {
                sequence = maxs[chapter - 1] + 1;
                maxs[chapter - 1] = sequence;
            }
            else {
                maxs[chapter - 1] = sequence;
            }
            var div = document.createElement("div");
            div.setAttribute("id", "section" + section_counter);
            div.style.marginTop = "20px";
            section_area.appendChild(div);
            div.innerHTML = div.innerHTML + '<input type="hidden" name="section_ids[]" value="' + id + '">';
            div.innerHTML = div.innerHTML + '<label style="width: 10%; text-align: right;">顺序：&nbsp;</label><input type="number" style="width: 5%;" value="' + sequence + '" name="section_sequences[]">';
            div.innerHTML = div.innerHTML + '<label style="width: 10%; text-align: right;">标题：&nbsp;</label><input style="width: 15%;" value=\'' + title + '\' name="section_titles[]" id="section'+ section_counter + '_title" maxlength="30">';
            div.innerHTML = div.innerHTML + '<label style="width: 10%; text-align: right;">副标题：&nbsp;</label><input style="width: 15%;" value=\'' + sub_title + '\' name="section_sub_titles[]" id="section'+ section_counter + '_sub_title" maxlength="100">';
            if (type == 0) {
                type_chinese = "公共";
            }
            if (type == 1) {
                type_chinese = "普通";
            }
            else if (type == 2) {
                type_chinese = "时间线";
                document.getElementById("button_" + chapter + "_2").style.display = "none";
            }
            else if (type == 3) {
                type_chinese = "房间线索";
                // document.getElementById("button_" + chapter + "_3").style.display = "none";
            }
            else if (type == 4) {
                type_chinese = "任务";
                document.getElementById("button_" + chapter + "_4").style.display = "none";
            }
            div.innerHTML = div.innerHTML + '<label style="width: 10%; text-align: right;">类别：&nbsp;</label><label style="width: 10%; text-align: left;">' + type_chinese + '</label>';
            div.innerHTML = div.innerHTML + '<button type="button" onclick="open_modal(' + id +', ' + section_counter + ')" class="genric-btn danger circle small" style="width: 25px; height: 25px; padding: 0px; margin-left: 5%;" tabindex="-1"><i class="fa fa-minus"></i></button>';
            
            div.innerHTML = div.innerHTML + '<input type="hidden" value="' + chapter + '" name="section_chapters[]">';
            div.innerHTML = div.innerHTML + '<input type="hidden" value="' + type + '" name="section_types[]">';
            div.innerHTML = div.innerHTML + '<input type="hidden" id="section' + section_counter + '_hide" value=\'' + content + '\' name="section_contents[]">';

            if (type == 0) {
                var div1 = document.createElement("div");
                div.appendChild(div1);
                div1.setAttribute("id", "menu_bar" + section_counter);
                div1.style.width = "600px";
                div1.style.height = "40px";
                div1.style.border = "1px solid #BBBBBB";
                div1.style.textAlign = "left";
                div1.style.fontSize = "14px";
                div1.innerHTML = div1.innerHTML + '<button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; font-weight: bold;" onclick="document.execCommand(\'bold\',false,null);" tabindex="-1">B</button>';
                div1.innerHTML = div1.innerHTML + '<button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; font-style: italic;" onclick="document.execCommand(\'italic\',false,null);" tabindex="-1">I</button>';
                div1.innerHTML = div1.innerHTML + '<button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px; text-decoration: underline;" onclick="document.execCommand(\'underline\',false,null);" tabindex="-1">U</button>';
                div1.innerHTML = div1.innerHTML + '<button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand(\'fontSize\',false,\'4\');" tabindex="-1">A+</button>';
                div1.innerHTML = div1.innerHTML + '<button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="change_font()" tabindex="-1">A-</button>';
                div1.innerHTML = div1.innerHTML + '<button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand(\'justifyLeft\',false,null);" tabindex="-1"><i class="fa fa-align-left"></i></button>';
                div1.innerHTML = div1.innerHTML + '<button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand(\'justifyCenter\',false,null);" tabindex="-1"><i class="fa fa-align-center"></i></button>';
                div1.innerHTML = div1.innerHTML + '<button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="document.execCommand(\'justifyRight\',false,null);" tabindex="-1"><i class="fa fa-align-right"></i></button>';
                div1.innerHTML = div1.innerHTML + '<button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="insertOl()" tabindex="-1"><i class="fa fa-list-ol"></i></button>';
                div1.innerHTML = div1.innerHTML + '<button type="button" class="genric-btn info-border small" style="width: 26px; height: 26px; padding: 0px; margin-top: 7px; margin-left: 10px;" onclick="insertUl()" tabindex="-1"><i class="fa fa-list-ul"></i></button>';
                div.innerHTML = div.innerHTML + '<div id="section' + section_counter + '_show" style="width: 600px; height: 200px; margin-bottom: 20px; border: 1px solid #BBBBBB; text-align: left; overflow: auto; font-size: 14px;" contenteditable="true" onkeyup="copyHTML(' + section_counter + ')">' + content + '</div>';
                copy_counter = section_counter;
                document.getElementById("menu_bar" + section_counter).addEventListener("click", function() {copyHTML(copy_counter)});
            }

            section_counter += 1;
        }

        function copyHTML(id) {
            document.getElementById("section" + id + "_hide").value = document.getElementById("section" + id + "_show").innerHTML;
        }

        function change_font() {
            document.execCommand('fontSize',false,'7');
            var fontElements = document.getElementsByTagName("font");
            for (var i = 0, len = fontElements.length; i < len; ++i) {
                if (fontElements[i].size == "7") {
                    fontElements[i].removeAttribute("size");
                    fontElements[i].style.fontSize = "14px";
                }
            }
        }

        function insertOl() {
            document.execCommand('insertOrderedList',false,null);
            var olElements = document.getElementsByTagName("ol");
            for (var i = 0, len = olElements.length; i < len; ++i) {
                olElements[i].setAttribute("class", "ordered-list");
            }
        }

        function insertUl() {
            document.execCommand('insertUnorderedList',false,null);
            var ulElements = document.getElementsByTagName("ul");
            for (var i = 0, len = ulElements.length; i < len; ++i) {
                if (ulElements[i].className.indexOf("nav-menu") == -1) {
                    ulElements[i].setAttribute("class", "unordered-list");
                }
            }
        }

        function open_modal(id, counter) {
            var modal = document.getElementById("myModal");
            modal.style.display = "block";
            window.id = id;
            window.counter = counter;
            var title = document.getElementById("section" + counter + "_title").value;
            document.getElementById("modal_content").innerHTML = '标题：' + title;
            document.getElementById("modal_confirm").value = id;
        }

        function close_modal() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
            document.getElementById("delete_id").value = -1;
        }

        window.onclick = function(event) {
            var modal = document.getElementById("myModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        function delete_modal() {
            if (id < 0) {
                var delete_li = document.getElementById("section" + counter);
                delete_li.parentNode.removeChild(delete_li);
                close_modal();
            }
            else {
                document.getElementById("delete_id").value = id;
                document.getElementById("myform").submit();
            }
        }
    </script>

    <br><br><br>
    <form id="myform" action="admin_tabs/request.php" method="post">
        <input type="hidden" name="tab" value="sections">
        <input type="hidden" name="delete_id" id="delete_id" value="-1">
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
                    <button class="genric-btn info circle e-large" type="button" onclick="delete_modal()">确定</button>
                    <button class="genric-btn info circle e-large" type="button" onclick="close_modal()">关闭</button>
                </div>
            </div>
        </div>
        <section>
            <div class="container">
                <div class="section-top-border">
                    <div class="row d-flex justify-content-center">
                        <!-- <div class="col-lg-9 col-md-9 col-sm-10"> -->
                            <center>
                                <h3 style="margin-bottom: 20px">第一幕</h3>
                                <button type="button" id="button_1_0" onclick="add_section(1, -1, -1, 0, '', '', '')" class="genric-btn info-border circle e-large col-10" style="font-size: 10pt; width: 170px;">添加公共章节</button>
                                <button type="button" id="button_1_1" onclick="add_section(1, -1, -1, 1, '', '', '')" class="genric-btn info-border circle e-large col-10" style="font-size: 10pt; width: 170px;">添加普通章节</button>
                                <button type="button" id="button_1_2" onclick="add_section(1, -1, -1, 2, '', '', '')" class="genric-btn info-border circle e-large col-10" style="font-size: 10pt; width: 170px;">添加时间线</button>
                                <button type="button" id="button_1_3" onclick="add_section(1, -1, -1, 3, '', '', '')" class="genric-btn info-border circle e-large col-10" style="font-size: 10pt; width: 170px;">添加房间线索</button>
                                <button type="button" id="button_1_4" onclick="add_section(1, -1, -1, 4, '', '', '')" class="genric-btn info-border circle e-large col-10" style="font-size: 10pt; width: 170px;">添加任务</button>
                                <div id="section_area1">
                                    <?php
                                    $sql = 'SELECT * FROM sections WHERE chapter=1 AND script_id='.$script_id.' ORDER BY sequence ASC';
                                    $result = $conn->query($sql);
                                    echo '
                                    <script type="text/javascript">
                                        var section_counter = 0;
                                        var maxs = [0, 0]';
                                    while ($row = $result->fetch_assoc()) {
                                        $content = "";
                                        $sql1 = 'SELECT * FROM character_section WHERE character_id='.$admin.' AND section_id='.$row["id"];
                                        $result1 = $conn->query($sql1);
                                        while ($row1 = $result1->fetch_assoc()) {
                                            $content = $row1["content"];
                                        }
                                        echo '
                                        add_section(1, '.$row["id"].', '.$row["sequence"].', '.$row["type"].', \''.$row["title"].'\', \''.$content.'\', \''.$row["sub_title"].'\');';
                                    }
                                    echo '
                                    </script>';
                                    ?>
                                </div>
                            </center>
                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="section-top-border">
                    <div class="row d-flex justify-content-center">
                        <!-- <div class="col-lg-9 col-md-9 col-sm-10"> -->
                            <center>
                                <h3 style="margin-bottom: 20px">第二幕</h3>
                                <button type="button" id="button_2_0" onclick="add_section(2, -1, -1, 0, '', '', '')" class="genric-btn info-border circle e-large col-10" style="font-size: 10pt; width: 170px;">添加公共章节</button>
                                <button type="button" id="button_2_1" onclick="add_section(2, -1, -1, 1, '', '', '')" class="genric-btn info-border circle e-large col-10" style="font-size: 10pt; width: 170px;">添加普通章节</button>
                                <button type="button" id="button_2_2" onclick="add_section(2, -1, -1, 2, '', '', '')" class="genric-btn info-border circle e-large col-10" style="font-size: 10pt; width: 170px;">添加时间线</button>
                                <button type="button" id="button_2_3" onclick="add_section(2, -1, -1, 3, '', '', '')" class="genric-btn info-border circle e-large col-10" style="font-size: 10pt; width: 170px;">添加房间线索</button>
                                <button type="button" id="button_2_4" onclick="add_section(2, -1, -1, 4, '', '', '')" class="genric-btn info-border circle e-large col-10" style="font-size: 10pt; width: 170px;">添加任务</button>
                                <div id="section_area2">
                                    <?php
                                    $sql = 'SELECT * FROM sections WHERE chapter=2 AND script_id='.$script_id.' ORDER BY sequence ASC';
                                    $result = $conn->query($sql);
                                    echo '
                                    <script type="text/javascript">';
                                    while ($row = $result->fetch_assoc()) {
                                        $content = "";
                                        $sql1 = 'SELECT * FROM character_section WHERE character_id='.$admin.' AND section_id='.$row["id"];
                                        $result1 = $conn->query($sql1);
                                        while ($row1 = $result1->fetch_assoc()) {
                                            $content = $row1["content"];
                                        }
                                        echo '
                                        add_section(2, '.$row["id"].', '.$row["sequence"].', '.$row["type"].', \''.$row["title"].'\', \''.$content.'\', \''.$row["sub_title"].'\');';
                                    }
                                    echo '
                                    </script>';
                                    ?>
                                </div>
                            </center>
                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </section>