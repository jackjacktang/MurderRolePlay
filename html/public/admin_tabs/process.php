	<br><br><br>

	<?php
	$sql = 'SELECT * FROM status WHERE script_id='.$script_id.' AND name=1';
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		$value = $row["value"];
	}
	?>

	

	<form action="admin_tabs/request.php" method="post">
		<input type="hidden" name="tab" value="process">
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