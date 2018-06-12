	<br><br><br>

	<form action="admin_tabs/request.php" method="post">
		<?php
		$sql = "SELECT * FROM sections ORDER BY chapter ASC, sequence ASC";
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			echo '
		<section>
            <div class="container">
                <div class="section-top-border">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-9 col-md-9 col-sm-10">
                            <center>
                                <h3>'.$row["title"].'</h3>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </section>';
		}
		?>

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