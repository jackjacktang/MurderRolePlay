<!DOCTYPE html>
<html lang="zxx" class="no-js">
<?php
session_start();
if (!isset($_SESSION["character_id"])) {
    header("Location: login.php");
}
else if ($_SESSION["character_id"] != 1) {
    header("Location: login.php");
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
    <title><?php echo $_SESSION["script_name"]; ?></title>

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
$tab = $_GET["tab"];
$pairs = array();

$db_host = "localhost";
$db_user = "root";
$db_password = "Lu636593";
$db = "rp_".$_SESSION["script_id"];
$conn = new mysqli($db_host, $db_user, $db_password, $db);
if (mysqli_connect_errno()) {
    echo mysqli_connect_error();
}
$conn->set_charset("utf8");
?>

    <header id="header">
        <div class="container">
            <div class="row align-items-center justify-content-between d-flex">
                <div id="logo">
                    <a href="index.php"><img src="img/logo.png" style="height: 50px;"  alt="" title="" /></a>
                </div>
                <nav id="nav-menu-container">
                    <ul class="nav-menu">
                        <li><a href="<?php echo($tab=='background'? '#':'admin.php?tab=background'); ?>">故事背景</a></li>
                        <li><a href="<?php echo($tab=='sections'? '#':'admin.php?tab=sections'); ?>">章节管理</a></li>
                        <li class="menu-has-children"><a href="#">剧本管理（第一幕）</a>
                            <ul>
                                <?php
                                $sql = "SELECT id, name FROM characters ORDER BY id ASC";
                                $result = $conn->query($sql);
                                $counter = 0;
                                while ($row = $result->fetch_assoc()) {
                                    if ($counter > 0) {
                                        echo '
                                <li><a href="admin.php?tab=scripts&character_id='.$row["id"].'&chapter=1">'.$counter.". ".$row["name"].'</a></li>';
                                    }
                                    $counter++;
                                }
                                ?>
                            </ul>
                        </li>
                        <li class="menu-has-children"><a href="#">剧本管理（第二幕）</a>
                            <ul>
                                <?php
                                $sql = "SELECT id, name FROM characters ORDER BY id ASC";
                                $result = $conn->query($sql);
                                $counter = 0;
                                while ($row = $result->fetch_assoc()) {
                                    if ($counter > 0) {
                                        echo '
                                <li><a href="admin.php?tab=scripts&character_id='.$row["id"].'&chapter=2">'.$counter.". ".$row["name"].'</a></li>';
                                    }
                                    $counter++;
                                }
                                ?>
                            </ul>
                        </li>
                        <li><a href="<?php echo($tab=='clues'? '#':'admin.php?tab=clues'); ?>">线索管理</a></li>
                        <li><a href="logout.php">登出</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>


    <?php
    include("admin_tabs/".$tab.".php");
    $conn->close();
    ?>

    <style type="text/css">
        b {
            color: black;
            font-weight: bold;
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


