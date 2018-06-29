<?php
include("connection.php");

if (!isset($_SESSION["script_id"])) {
    $conn->close();
    header("Location: ../index.php");
}

if (!isset($_SESSION["character_id"])) {
    $conn->close();
    header("Location: login.php");
}
else {
    $character_id = $_SESSION["character_id"];
}

function replace_text($pairs, $text) {
    foreach ($pairs as $pair) {
        if ($pair[1] != "") {
            $text = str_replace($pair[0], $pair[1], $text);
        }
    }
    return $text;
}
?>

<!DOCTYPE html>
<html lang="zxx" class="no-js">
<head>
	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="shortcut icon" href="img/icon.png">
	<!-- Author Meta -->
	<meta name="author" content="codepixer">
	<!-- Meta Description -->
	<meta name="description" content="">
	<!-- Meta Keyword -->
	<meta name="keywords" content="">
	<!-- meta character set -->
	<meta charset="UTF-8">
	<!-- Site Title -->
	<title>惊魂醉阳楼</title>

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
    <style>
        u {color: #777777;}
        .ordered-list li {font-weight: 300;}
    </style>
</head>

<body>

<?php
$pairs = array();
$script_id = $_SESSION["script_id"];

$sql = 'SELECT MIN(id) AS admin FROM characters WHERE script_id='.$_SESSION["script_id"];
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $admin = $row["admin"];
}

$sql = "SELECT * FROM status WHERE name=1 AND script_id=".$script_id;
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $status = $row["value"];
}

$sql = "SELECT * FROM characters WHERE script_id=".$script_id;
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($pairs, array($row["name"], $row["preferred_name"]));
    if ($row["id"] == $_SESSION["character_id"]) {
        $user_information = $row;
    }
}

$sql = "SELECT * FROM maps WHERE script_id=".$script_id;
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    $maps = False;
}
else {
    $maps = True;
}

$sections = array(array(), array());
foreach (array(1, 2) as $chapter) {
    $sql = 'SELECT * FROM sections WHERE script_id='.$script_id.' AND chapter='.$chapter.' ORDER BY sequence ASC';
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        // 公共
        if ($row["type"] == 0) {
            array_push($sections[$chapter - 1], array("id"=>$row["id"], "type"=>$row["type"], "title"=>$row["title"], "sub_title"=>$row["sub_title"]));
        }
        // 普通线索
        if ($row["type"] == 1) {
            $sql1 = 'SELECT * FROM character_section WHERE character_id='.$character_id.' AND section_id='.$row["id"];
            $result1 = $conn->query($sql1);
            $content = "";
            while ($row1 = $result1->fetch_assoc()) {
                $content = $row1["content"];
            }
            if ($content != "") array_push($sections[$chapter - 1], array("id"=>$row["id"], "type"=>$row["type"], "title"=>$row["title"], "sub_title"=>$row["sub_title"]));
        }
        // 时间线
        else if ($row["type"] == 2) {
            $sql1 = 'SELECT * FROM timelines WHERE character_id='.$character_id.' AND chapter='.$chapter.' ORDER BY hour ASC, minute ASC';
            $result1 = $conn->query($sql1);
            if ($result1->num_rows > 0) array_push($sections[$chapter - 1], array("id"=>$row["id"], "type"=>$row["type"], "title"=>$row["title"], "sub_title"=>$row["sub_title"]));
        }
        // 房间线索
        else if ($row["type"] == 3) {
            $sql1 = 'SELECT * FROM clues AS C LEFT JOIN locations AS L ON C.location_id=L.id WHERE L.character_id='.$character_id.' AND C.chapter='.$chapter.' ORDER BY C.id ASC';
            $result1 = $conn->query($sql1);
            if ($result1->num_rows > 0) array_push($sections[$chapter - 1], array("id"=>$row["id"], "type"=>$row["type"], "title"=>$row["title"], "sub_title"=>$row["sub_title"]));
        }
        // 任务
        else {
            $sql1 = 'SELECT * FROM objectives WHERE character_id='.$character_id.' AND chapter='.$chapter.' ORDER BY id ASC';
            $result1 = $conn->query($sql1);
            if ($result1->num_rows > 0) array_push($sections[$chapter - 1], array("id"=>$row["id"], "type"=>$row["type"], "title"=>$row["title"], "sub_title"=>$row["sub_title"]));
        }
    }
}

$sql = "SELECT * FROM status WHERE name=2 AND script_id=".$script_id;
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    if ($row["value"] == 0) $duplicate = False;
    else $duplicate = True;
}

if (isset($_POST["tab"])) {
    include("tabs/request.php");
}
$tab = $_GET["tab"];
?>

    <header id="header">
	    <div class="container">
	        <div class="row align-items-center justify-content-between d-flex">
		        <div id="logo">
		            <a href="home.php"><img src="img/logo.png" style="height: 50px;"  alt="" title="" /></a>
			    </div>
			    <nav id="nav-menu-container">
			        <ul class="nav-menu">
                        <li><a href="home.php?tab=background">故事背景</a></li>
                        <?php
                        if ($maps) {
                            echo '
                        <li><a href="home.php?tab=maps">地图</a></li>';
                        }
                        ?>
                        <li class="menu-has-children"><a href="home.php?tab=scripts&chapter=1"><?php echo (sizeof($sections[1])>0? "第一幕":"你的剧本"); ?></a>
                            <ul>
                                <?php
                                foreach ($sections[0] as $section) {
                                    echo '
                                <li><a href="home.php?tab=scripts&chapter=1#section'.$section["id"].'">'.$section["title"].'</a></li>';
                                }
                                ?>
                            </ul>
                        </li>
                        <li class="menu-has-children" style="display: <?php echo ($status>=3? "block":"none"); ?>"><a href="home.php?tab=scripts&chapter=2">第二幕</a>
                            <ul>
                                <?php
                                foreach ($sections[1] as $section) {
                                    echo '
                                <li><a href="home.php?tab=scripts&chapter=2#section'.$section["id"].'">'.$section["title"].'</a></li>';
                                }
                                ?>
                            </ul>
                        </li>
                        <li style="display: <?php echo ($status>=2? "block":"none"); ?>;"><a href="<?php echo ($tab=='find_clue'? '#':'home.php?tab=find_clue') ?>">调查线索</a></li>
                        <li><a href="<?php echo ($tab=='your_clue'? '#':'home.php?tab=your_clue') ?>">你的线索</a></li>
                        <li style="display: <?php echo ($status>=5? "block":"none"); ?>;"><a href="<?php echo ($tab=='submit'? '#':'home.php?tab=submit') ?>">指认凶手</a></li>
                        <li style="display: <?php echo ($status>=6? "block":"none"); ?>;"><a href="<?php echo ($tab=='result'? '#':'home.php?tab=result') ?>">最终得分</a></li>
                        <li><a href="logout.php">登出</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>


    <?php
    include("tabs/".$tab.".php");
    $conn->close();
    ?>

    <style type="text/css">
        b {
            color: black;
            font-weight: bold;
        }
        ul {
            list-style-type: none;
        }
    </style>

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



