	<script type="text/javascript">
		var vote_counter = 0;
		var id;
        var counter;

		function add_vote(id, content) {
            var vote_area = document.getElementById("vote_area");
            var div = document.createElement("div");
            div.style.marginTop = "20px";
            vote_area.appendChild(div);
            div.setAttribute("id", "vote" + vote_counter);
            div.innerHTML += '<input type="hidden" name="vote_ids[]" value="' + id + '">';
            div.innerHTML = div.innerHTML + '<input required style="width: 30%;" value=\'' + content + '\' name="vote_descriptions[]" id="vote'+ vote_counter + '_content">';
            div.innerHTML = div.innerHTML + '<button type="button" onclick="open_modal(' + id +', ' + vote_counter + ')" class="genric-btn danger circle small" style="width: 25px; height: 25px; padding: 0px; margin-left: 5%;" tabindex="-1"><i class="fa fa-minus"></i></button>';
            vote_counter += 1;
        }

        function open_modal(id, counter) {
            var modal = document.getElementById("myModal");
            modal.style.display = "block";
            window.id = id;
            window.counter = counter;
            var title = document.getElementById("vote" + counter + "_content").value;
            document.getElementById("modal_content").innerHTML = title;
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
                var delete_li = document.getElementById("vote" + counter);
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
    	<input type="hidden" name="tab" value="votes">
    	<input type="hidden" name="delete_id" id="delete_id" value="-1">
    	<div id="myModal" class="modal" style="top: 30%;">
            <div class="modal-content col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-10 offset-1">
                <div class="modal-header">
                    <h4 class="modal-title">确认要删除这个投票么？</h4>
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
                        <center style="width: 100%;">
                            <h3 style="margin-bottom: 20px">
                            	投票
                            	<button class="genric-btn info circle small" style="width: 25px; height: 25px; padding: 0px;" type="button" onclick="add_vote(-1, '')">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </h3>

                            <div id="vote_area">
                            	<script type="text/javascript">
                            		<?php
	                            	$sql = 'SELECT * FROM votes WHERE script_id='.$script_id;
	                            	$result = $conn->query($sql);
	                            	while ($row = $result->fetch_assoc()) {
	                            		echo '
	                            	add_vote('.$row["id"].', \''.$row["description"].'\')';
	                            	}
	                            	?>
                            	</script>
                            </div>
                        </center>
                    </div>
                </div>
            </div>
        </section>