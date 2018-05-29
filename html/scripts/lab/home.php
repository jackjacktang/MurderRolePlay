<!DOCTYPE html>
<html lang="zxx" class="no-js">
<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
}

function replace_text($pairs, $text) {
    foreach ($pairs as $pair) {
        $text = str_replace($pair[0], $pair[1], $text);
    }
    return $text;
}
?>
<head>
	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="shortcut icon" href="img/fav.png">
	<!-- Author Meta -->
	<meta name="author" content="codepixer">
	<!-- Meta Description -->
	<meta name="description" content="">
	<!-- Meta Keyword -->
	<meta name="keywords" content="">
	<!-- meta character set -->
	<meta charset="UTF-8">
	<!-- Site Title -->
	<title>实验室惊魂</title>

	<link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet"> 
			<!--
			CSS
			============================================= -->
	<link rel="stylesheet" href="css/linearicons.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/magnific-popup.css">
	<link rel="stylesheet" href="css/nice-select.css">					
	<link rel="stylesheet" href="css/animate.min.css">
	<link rel="stylesheet" href="css/owl.carousel.css">
	<link rel="stylesheet" href="css/main.css">
</head>

<body>

<?php
$tab = $_GET["tab"];
$pairs = array();

$db_host = "localhost";
$db_user = "root";
$db_password = "Lu636593";
$db = "role_play1";
$conn = new mysqli($db_host, $db_user, $db_password, $db);
if (mysqli_connect_errno()) {
    echo mysqli_connect_error();
}
$conn->set_charset("utf8");

$sql = "SELECT * FROM status";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $status = $row["status"];
}
if ($status == 2) $murder = 1;
else $murder = 2;

$sql = "SELECT * FROM players";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($pairs, array($row["initial_name"], $row["chinese_name"]));
    if ($row["username"] == $_SESSION["username"]) {
        $user_information = $row;
    }
}
?>

    <header id="header">
	    <div class="container">
	        <div class="row align-items-center justify-content-between d-flex">
		        <div id="logo">
		            <a href="index.php"><img src="img/logo.png" style="height: 50px;"  alt="" title="" /></a>
			    </div>
			    <nav id="nav-menu-container">
			        <ul class="nav-menu">
                        <li class="menu-has-children"><a href="<?php echo ($tab=='first'? '#':'home.php?tab=first'); ?>">第一幕</a>
                            <ul>
                                <li><a href="<?php echo ($tab=='first'? '':'home.php?tab=first'); ?>#background1">背景故事</a></li>
                                <li><a href="<?php echo ($tab=='first'? '':'home.php?tab=first'); ?>#story1">你的故事</a></li>
                                <li><a href="<?php echo ($tab=='first'? '':'home.php?tab=first'); ?>#timeline1">你的时间线</a></li>
                                <li><a href="<?php echo ($tab=='first'? '':'home.php?tab=first'); ?>#admit1">你要承认的事实</a></li>
                                <li><a href="<?php echo ($tab=='first'? '':'home.php?tab=first'); ?>#clues1">你房间的线索</a></li>
                                <li><a href="<?php echo ($tab=='first'? '':'home.php?tab=first'); ?>#objectives1">你的任务</a></li>
                                <li><a href="<?php echo ($tab=='first'? '':'home.php?tab=first'); ?>#skill1">你的特殊技能</a></li>
                            </ul>
                        </li>
                        <li class="menu-has-children" style="display: <?php echo ($status>=3? "block":"none"); ?>"><a href="<?php echo ($tab=='second'? '#':'home.php?tab=second'); ?>">第二幕</a>
                            <ul>
                                <li><a href="<?php echo ($tab=='second'? '':'home.php?tab=second'); ?>#background2">背景故事</a></li>
                                <li><a href="<?php echo ($tab=='second'? '':'home.php?tab=second'); ?>#story2">你的故事</a></li>
                                <li><a href="<?php echo ($tab=='second'? '':'home.php?tab=second'); ?>#timeline2">你的时间线</a></li>
                                <li><a href="<?php echo ($tab=='second'? '':'home.php?tab=second'); ?>#clues2">你房间的线索</a></li>
                                <li><a href="<?php echo ($tab=='second'? '':'home.php?tab=second'); ?>#objectives2">你的任务</a></li>
                            </ul>
                        </li>
                        <li style="display: <?php echo ($status>=2? "block":"none"); ?>;"><a href="<?php echo ($tab=='find_clue'? '#':'home.php?tab=find_clue') ?>">调查线索</a></li>
                        <li><a href="<?php echo ($tab=='your_clue'? '#':'home.php?tab=your_clue') ?>">你的线索</a></li>
                        <li style="display: <?php echo ($status>=4? "block":"none"); ?>;"><a href="<?php echo ($tab=='submit'? '#':'home.php?tab=submit') ?>">指认凶手</a></li>
                        <li style="display: <?php echo ($status>=5? "block":"none"); ?>;"><a href="<?php echo ($tab=='result'? '#':'home.php?tab=result') ?>">最终得分</a></li>
                        <li><a href="logout.php">登出</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>


    <?php
    if ($tab == "first") include("tabs/first.php");
    if ($tab == "second" && $status >= 3) include("tabs/second.php");
    if ($tab == "find_clue" && $status >= 2) include("tabs/find_clue.php");
    if ($tab == "your_clue") include("tabs/your_clue.php");
    if ($tab == "submit" && $status >= 4) include("tabs/submit.php");
    if ($tab == "result" && $status >= 5) include("tabs/result.php");
    $conn->close();
    ?>

	<script src="js/vendor/jquery-2.2.4.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="js/vendor/bootstrap.min.js"></script>			
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhOdIF3Y9382fqJYt5I_sswSrEw5eihAA"></script>
  	<script src="js/easing.min.js"></script>			
	<script src="js/hoverIntent.js"></script>
	<script src="js/superfish.min.js"></script>	
	<script src="js/jquery.ajaxchimp.min.js"></script>
	<script src="js/jquery.magnific-popup.min.js"></script>	
	<script src="js/owl.carousel.min.js"></script>			
	<script src="js/jquery.sticky.js"></script>
	<script src="js/jquery.nice-select.min.js"></script>			
	<script src="js/parallax.min.js"></script>		
	<script src="js/mail-script.js"></script>	
	<script src="js/main.js"></script>	
</body>
</html>



