	<br><br><br>
	<form method="post" action="home.php">
		<input type="hidden" name="tab" value="votes">

		<?php
		$sql = 'SELECT CV.id, CV.vote_id, C.name, V.description FROM character_vote AS CV LEFT JOIN characters AS C ON CV.selection=C.id LEFT JOIN votes AS V ON CV.vote_id=V.id WHERE CV.script_id='.$script_id.' AND CV.character_id='.$_SESSION["character_id"].' ORDER BY CV.id ASC';
		$result = $conn->query($sql);
		$vote_id = -1;
		if ($result->num_rows > 0) {
			echo '
		<section>
	        <div class="container">
	            <div class="section-top-border">
	            	<div class="row d-flex justify-content-center">
                		<div class="col-lg-8 col-md-9 col-sm-10">
	                        <center>
	                            <h3 style="margin-bottom: 20px;">投票历史</h3>
	                        </center>';

			while ($row = $result->fetch_assoc()) {
				if ($vote_id == -1) {

				}
				else if ($row["vote_id"] < $vote_id) {
					echo '
							<button type="button" class="genric-btn danger circle small" style="width: 25px; height: 25px; padding: 0px; margin-left: 10%;" onclick="open_delete_modal('.$row1["id"].')">
                                <i class="fa fa-minus"></i>
                            </button><br><br>';
				}
				else {
					echo '
							<br>';
				}
				$vote_id = $row["vote_id"];

				echo '
							<b>'.$row["description"].'</b>'.$row["name"];
			}

			echo '
							<button type="button" class="genric-btn danger circle small" style="width: 25px; height: 25px; padding: 0px; margin-left: 10%;" onclick="open_delete_modal('.$row1["id"].')">
                                <i class="fa fa-minus"></i>
                            </button><br><br>';

			echo '
	                    </div>
	                </div>
	            </div>
	        </div>
	    </section>';
		}

	    $sql = 'SELECT * FROM votes WHERE script_id='.$script_id.' ORDER BY id ASC';
	    $result = $conn->query($sql);
	    while ($row = $result->fetch_assoc()) {
	        echo '
		<section>
	        <div class="container">
	            <div class="section-top-border">
	            	<div class="row d-flex justify-content-center">
                		<div class="col-lg-8 col-md-9 col-sm-10">
	                        <center>
	                            <h3 style="margin-bottom: 20px;">'.$row["description"].'</h3>
	                        </center>';

	                        	$sql1 = 'SELECT * FROM characters WHERE script_id='.$script_id.' AND id<>'.$admin;
	                        	$result1 = $conn->query($sql1);
	                        	while ($row1 = $result1->fetch_assoc()) {
	                        		echo '
	                        <input required type="radio" name="vote'.$row["id"].'" value="'.$row1["id"].'" style="margin-top: 10px;"> '.$row1["name"].'<br>';
	                        	}
	        echo '
	                    </div>
	                </div>
	            </div>
	        </div>
	    </section>
	    ';
		}
		?>

	    <section>
            <div class="container">
                <div class="section-top-border"></div>
            </div>
        </section>

		<section style="position: fixed; left: 0; bottom: 0px; width: 100%; background-color: white;">
            <div class="row d-flex justify-content-center">
                <button type="submit" class="genric-btn info circle e-large col-lg-4 col-md-6 col-sm-8 col-10" style="font-size: 14pt; margin-bottom: 30px;">保存</button>
            </div>
        </section>
    </form>