	<br><br><br>
	<script type="text/javascript">
        var id;
        var counter;

		function add_location(id, name, is_room) {
            var location_area = document.getElementById("location_area");
            var div = document.createElement("div");
            div.setAttribute("id", "location" + location_counter);
            div.style.marginTop = "20px";
            location_area.appendChild(div);
            
            div.innerHTML = div.innerHTML + '<input type="hidden" name="location_ids[]" value="' + id + '">';
            if (id == 0) {
                div.innerHTML = div.innerHTML + '<label style="width: 10%; text-align: right;">地点：&nbsp;</label><input readonly required style="width: 30%; border: 0px;" value=\'' + name + '\' name="location_names[]" maxlength=20 id="location'+ location_counter + '_name">';
            }
            else {
                div.innerHTML = div.innerHTML + '<label style="width: 10%; text-align: right;">地点：&nbsp;</label><input required style="width: 30%;" value=\'' + name + '\' name="location_names[]" maxlength=20 id="location'+ location_counter + '_name">';
            }
            
            if (is_room) var visibility = "hidden";
            else var visibility = "visible";
            div.innerHTML = div.innerHTML + '<button type="button" onclick="open_modal(' + id + ', ' + location_counter + ')" class="genric-btn danger circle small" style="width: 25px; height: 25px; padding: 0px; margin-left: 50px; visibility: ' + visibility + ';"><i class="fa fa-minus"></i></button>';

            location_counter += 1;
        }

        function open_modal(id, counter) {
            var modal = document.getElementById("myModal");
            modal.style.display = "block";
            window.id = id;
            window.counter = counter;
            document.getElementById("modal_content").innerHTML = document.getElementById("location" + counter + "_name").value;
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

        function delete_modal() {
            if (id < 0) {
                var delete_li = document.getElementById("location" + counter);
                delete_li.parentNode.removeChild(delete_li);
                close_modal();
            }
            else {
                document.getElementById("delete_id").value = id;
                document.getElementById("myform").submit();
            }
        }
    </script>

	<form id="myform" action="admin_tabs/request.php" method="post">
		<input type="hidden" name="tab" value="locations">
        <input type="hidden" name="delete_id" id="delete_id" value="-1">
        <div id="myModal" class="modal" style="top: 30%;">
            <div class="modal-content col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-10 offset-1">
                <div class="modal-header">
                    <h4 class="modal-title">确认要删除这个地点么？</h4>
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
                        <div class="col-lg-9 col-md-9 col-sm-10">
                            <center>
                                <h3>是否允许重复查找线索？</h3>
                                <div class="switch-wrap" style="margin-top: 20px;">
                                    <div class="confirm-switch">
                                        <?php
                                        $sql = "SELECT * FROM status WHERE name=2 AND script_id=".$script_id;
                                        $result = $conn->query($sql);
                                        while ($row = $result->fetch_assoc()) {
                                            $duplicate = $row["value"];
                                        }
                                        ?>
                                        <input type="checkbox" name="duplicate" id="confirm-switch" <?php echo ($duplicate==0? "":"checked"); ?>>
                                        <label for="confirm-switch"></label>
                                    </div>
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
                                <h3>添加/修改地点
                                    <button class="genric-btn info circle small" style="width: 25px; height: 25px; padding: 0px;" type="button" onclick="add_location(-1, '')">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </h3>
                                <div id="location_area">
                                    <?php
                                    echo '
                                        <script type="text/javascript">
                                            var location_counter = 0;
                                            add_location(0, "秘密线索", true);';
                                    $sql = 'SELECT * FROM locations WHERE character_id IS NOT NULL AND character_id<>'.$admin.' AND script_id='.$script_id.' ORDER BY character_id ASC';
                                    $result = $conn->query($sql);
                                    while ($row = $result->fetch_assoc()) {
                                        echo '
                                            add_location('.$row["id"].', \''.$row["name"].'\', true);';
                                    }
                                    $sql = 'SELECT * FROM locations WHERE character_id IS NULL AND script_id='.$script_id.' ORDER BY id ASC';
                                    $result = $conn->query($sql);
                                    while ($row = $result->fetch_assoc()) {
                                        echo '
                                            add_location('.$row["id"].', \''.$row["name"].'\', false);';
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