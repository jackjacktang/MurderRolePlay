	<?php
	$sql = 'SELECT * FROM vote WHERE person="'.$_SESSION["username"].'"';
	$result = $conn->query($sql);
	if ($result->num_rows == 0) {
		include("tabs/submit1.php");
	}
	else {
		include("tabs/submit2.php");
	}
	?>