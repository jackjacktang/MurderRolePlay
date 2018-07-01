<?php
if ($_POST["tab"] == "find_clue") {
	if (isset($_POST["find_clue"])) {
		$clue_id = $_POST["clue_id"];
		$clue;
		try {
			$conn->begin_transaction();
		    if ($duplicate) {
		        $sql = 'SELECT * FROM character_clue WHERE character_id='.$_SESSION["character_id"].' AND clue_id='.$clue_id;
		    }
		    else {
		        $sql = 'SELECT * FROM character_clue WHERE clue_id='.$clue_id;
		    }
		    $result = $conn->query($sql);
		    if ($result->num_rows == 0) {
		    	$sql1 = 'SELECT C.position, C.description, C.points, C.file_path, L.name FROM clues as C LEFT JOIN locations AS L on C.location_id=L.id WHERE C.id='.$clue_id;
		    	$result1 = $conn->query($sql1);
		    	while ($row1 = $result1->fetch_assoc()) {
		    		$clue = $row1;
		    	}
		    	if ($clue["points"] > $user_information["points"]) {
		            echo '
<script type="text/javascript">
	alert("作弊有意思么？");
	window.location = "home.php?tab=find_clue";
</script>';
		        }
		        else {
		        	$sql1 = 'INSERT INTO character_clue(script_id, character_id, clue_id, owner) VALUES('.$script_id.', '.$_SESSION["character_id"].', '.$clue_id.', 1)';
		        	echo $sql1;
		        	$conn->query($sql1);
		        	$sql1 = 'UPDATE characters SET points=points-'.$clue["points"].' WHERE id="'.$_SESSION["character_id"].'"';
		        	$conn->query($sql1);
		        }
		    }
		    else {
		    	echo '
<script type="text/javascript">
	alert("不好意思哦，手慢了。请选择其它的线索。");
	window.location = "home.php?tab=find_clue";
</script>';
			}
			$conn->commit();
			echo '
<div id="myModal2" class="modal" style="top: 8%; display: block;">
	<div class="modal-content col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-sm-10 offset-sm-1">
		<div class="modal-header">
			<h4 id="modal_title" class="modal-title">'.replace_text($pairs, $clue["name"]).'：'.$clue["position"].'</h4>
			<span class="close" style="float: right;" onclick="window.location=\'home.php?tab=find_clue\'">&times;</span>
		</div>
		<div class="modal-body">
			<img src="'.(file_exists($clue["file_path"])? ($clue["file_path"]):"img/alt.png").'" class="col-lg-8 offset-lg-2 col-md-10 offset-md-1" id="modal_img">
		</div>
		<div class="modal-footer justify-content-between" id="modal_footer">
			'.replace_text($pairs, $clue["description"]).'
		</div>
	</div>
</div>';
		}
		catch (Exception $e) {
			$conn->rollBack();
			echo '
<script type="text/javascript">
	alert("不好意思哦，手慢了。请选择其它的线索。");
	window.location = "home.php?tab=find_clue";
</script>';
		}
	}
	else {
		try {
			$conn->begin_transaction();
			$sql = 'SELECT points FROM characters WHERE id='.$_SESSION["character_id"];
			$result = $conn->query($sql);
			while ($row = $result->fetch_assoc()) {
				if ($_POST["amount"] > $row["points"]) {
					throw new Exception();
				}
			}
			$sql = 'UPDATE characters SET points=points-'.$_POST["amount"].' WHERE id="'.$_SESSION["character_id"].'"';
	        $conn->query($sql);
	        $sql = 'UPDATE characters SET points=points+'.$_POST["amount"].' WHERE id="'.$_POST["share_target"].'"';
	        $conn->query($sql);
	        $conn->commit();
	        echo '
<script type="text/javascript">
	window.location = "home.php?tab=find_clue";
</script>';
		}
		catch (Exception $e) {
			$conn->rollBack();
			echo '
<script type="text/javascript">
	alert("作弊有意思么？");
	window.location = "home.php?tab=find_clue";
</script>';
		}
	}
}

if ($_POST["tab"] == "your_clue") {
	if (isset($_POST["share"])) {
		foreach ($_POST["share_targets"] as $share_target) {
			$sql = 'INSERT INTO character_clue(script_id, character_id, clue_id, owner) VALUES('.$script_id.', '.$share_target.', '.$_POST["clue_id"].', 0)';
			$conn->query($sql);
		}
	}
	if (isset($_POST["delete"])) {
		$sql = 'DELETE FROM character_clue WHERE character_id='.$_SESSION["character_id"].' AND clue_id='.$_POST["clue_id"];
		$conn->query($sql);
	}
	if (isset($_POST["unlock"])) {
		$clue_id = $_POST["clue_id"];
		$clue;
		try {
			$conn->begin_transaction();
		    if ($duplicate) {
		        $sql = 'SELECT * FROM character_clue WHERE character_id='.$_SESSION["character_id"].' AND clue_id='.$clue_id;
		    }
		    else {
		        $sql = 'SELECT * FROM character_clue WHERE clue_id='.$clue_id;
		    }
		    $result = $conn->query($sql);
		    if ($result->num_rows == 0) {
		    	$sql1 = 'SELECT C.position, C.description, C.points, C.file_path, L.name FROM clues as C LEFT JOIN locations AS L on C.location_id=L.id WHERE C.id='.$clue_id;
		    	$result1 = $conn->query($sql1);
		    	while ($row1 = $result1->fetch_assoc()) {
		    		$clue = $row1;
		    	}
		    	if ($clue["points"] > $user_information["points"]) {
		            echo '
<script type="text/javascript">
	alert("作弊有意思么？");
	window.location = "home.php?tab=your_clue&chapter='.$_POST["chapter"].'";
</script>';
		        }
		        else {
		        	$sql1 = 'INSERT INTO character_clue(script_id, character_id, clue_id, owner) VALUES('.$script_id.', "'.$_SESSION["character_id"].'", '.$clue_id.', 1)';
		        	$conn->query($sql1);
		        	$sql1 = 'UPDATE characters SET points=points-'.$clue["points"].' WHERE id="'.$_SESSION["character_id"].'"';
		        	$conn->query($sql1);
		        }
		    }
		    else {
		    	echo '
<script type="text/javascript">
	alert("作弊有意思么？");
	window.location = "home.php?tab=your_clue&chapter='.$_POST["chapter"].'";
</script>';
			}
			$conn->commit();
			echo '
<div id="myModal2" class="modal" style="top: 20%; display: block;">
	<div class="modal-content col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-sm-10 offset-sm-1">
		<div class="modal-header">
			<h4 id="modal_title" class="modal-title">'.replace_text($pairs, $clue["name"]).'：'.$clue["position"].'</h4>
			<span class="close" style="float: right;" onclick="window.location=\'home.php?tab=find_clue\'">&times;</span>
		</div>
		<div class="modal-body">
			<img src="'.(file_exists($clue["file_path"])? ($clue["file_path"]):"img/alt.png").'" class="col-lg-8 offset-lg-2 col-md-10 offset-md-1" id="modal_img">
		</div>
		<div class="modal-footer justify-content-between" id="modal_footer">
			'.replace_text($pairs, $clue["description"]).'
		</div>
	</div>
</div>';
		}
		catch (Exception $e) {
			$conn->rollBack();
			echo '
<script type="text/javascript">
	alert("不好意思哦，手慢了。请选择其它的线索。");
	window.location = "home.php?tab=your_clue&chapter='.$_POST["chapter"].'";
</script>';
		}
	}
	echo '
<script type="text/javascript">
	window.location = "home.php?tab=your_clue&chapter='.$_POST["chapter"].'";
</script>';
}

if ($_POST["tab"] == "votes") {
	$sql = 'SELECT * FROM votes WHERE script_id='.$script_id;
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		$vote_id = $row["id"];
		$sql1 = 'INSERT INTO character_vote(script_id, character_id, vote_id, selection) VALUES('.$script_id.', '.$_SESSION["character_id"].', '.$vote_id.', '.$_POST["vote".$vote_id].')';
		$conn->query($sql1);
	}
	echo '
<script type="text/javascript">
	window.location = "home.php?tab=votes";
</script>';
}
	
exit(0);
?>