	<br><br><br>
	<form method="get" action="home.php">
		<section>
	        <div class="container">
	            <div class="section-top-border">
	            	<div class="row d-flex justify-content-center">
                		<div class="col-lg-8 col-md-9 col-sm-10">
	                        <center>
	                            <h3 style="margin-bottom: 20px;">投票</h3>
	                        </center>
	                        <?php
	                        $sql = 'SELECT * FROM votes WHERE script_id='.$script_id.' ORDER BY id ASC';
	                        $result = $conn->query($sql);
	                        while ($row = $result->fetch_assoc()) {
	                        	echo '
	                        <p style="margin-top: 20px;">'.$row["description"].'</p>';

	                        	$sql1 = 'SELECT * FROM characters WHERE script_id='.$script_id.' AND id<>'.$admin;
	                        	$result1 = $conn->query($sql1);
	                        	while ($row1 = $result1->fetch_assoc()) {
	                        		echo '
	                        <input required type="radio" name="vote'.$row["id"].'" value="'.$row1["id"].'" style="margin-top: 10px;"> '.$row1["name"].'<br>';
	                        	}
	                        }
	                        ?>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </section>
		<section style="position: fixed; left: 0; bottom: 0px; width: 100%; background-color: white;">
            <center>
                <button type="submit" class="genric-btn info circle e-large col-10" style="font-size: 14pt; width: 30%; margin-bottom: 30px;">保存</button>
            </center>
        </section>
    </form>