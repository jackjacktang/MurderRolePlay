	<?php
	if (isset($_GET["submit"])) {
		$sql = 'INSERT INTO vote(person, murder, vote) VALUES("'.$_SESSION["username"].'", 1, "'.$_GET["murder_1"].'")';
		$conn->query($sql);
		$sql = 'INSERT INTO vote(person, murder, vote) VALUES("'.$_SESSION["username"].'", 2, "'.$_GET["murder_2"].'")';
		$result = $conn->query($sql);
		if (!$result) {
			echo '
    <script type="text/javascript">
        alert("你已经投过票了");
        window.location = "home.php?tab=submit";
    </script>';
        }
		else {
			header("Location: home.php?tab=submit");
		}
	}
	?>

	<br><br><br>
    <section>
        <div class="container">
            <div class="section-top-border">
            	<form method="get" action="home.php">
                	<div class="row d-flex justify-content-between">
                		<div class="col-lg-5 offset-lg-1 col-md-5 offset-md-1 col-sm-6" style="margin-bottom: 30px;">
                			<input type="hidden" name="tab" value="submit">
                    		<h2>第一幕：</h2>
                    		<p style="margin-top: 20px;">杀害【郑伟业】的凶手是：</p>
                    		<?php
                    		$sql = "SELECT username, chinese_name FROM players";
                    		$result = $conn->query($sql);
                    		while ($row = $result->fetch_assoc()) {
                        		if ($row["username"] != $_SESSION["username"]) {
                            		echo '
                        	<input required type="radio" name="murder_1" value="'.$row["username"].'" style="margin-top: 10px;"> '.$row["chinese_name"].'<br>';
                        		}
                    		}
                    		?>
                    	</div>
                    	<div class="col-lg-5 offset-lg-1 col-md-5 offset-md-1 col-sm-6" style="margin-bottom: 30px;">
                    		<h2>第二幕：</h2>
                    		<p style="margin-top: 20px;">杀害【傅明义】的凶手是：</p>
                    		<?php
                    		$sql = "SELECT username, chinese_name FROM players";
                    		$result = $conn->query($sql);
                    		while ($row = $result->fetch_assoc()) {
                        		if ($row["username"] != $_SESSION["username"]) {
                            		echo '
                        	<input required type="radio" name="murder_2" value="'.$row["username"].'" style="margin-top: 10px;"> '.$row["chinese_name"].'<br>';
                        		}
                    		}
                    		?>
                		</div>
                		<div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-12">
                			<button name="submit" class="genric-btn info circle e-large" style="width: 100%; font-size: 14pt; margin-top: 30px;">提交</button>
                		</div>
                	</div>
               	</form>
            </div>
        </div>
    </section>