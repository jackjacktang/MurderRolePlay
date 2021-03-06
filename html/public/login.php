<?php
include("connection.php");

if (!isset($_SESSION["script_id"])) {
    $conn->close();
    header("Location: ../index.php");
}

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $sql = 'SELECT id, password FROM characters WHERE username="'.$username.'" AND script_id='.$_SESSION["script_id"].' ORDER BY id ASC';
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {
        echo '<script type="text/javascript">alert("该用户不存在！请重试！");window.location="login.php?username='.$username.'";</script>';
    }
    else {
        while ($row = $result->fetch_assoc()) {
            if ($row["password"] == $password) {
                $_SESSION["character_id"] = $row["id"];
            }
            else {
                echo '<script type="text/javascript">alert("密码不正确！请重试！");window.location="login.php?username='.$username.'";</script>';
            }
        }
    }
}

if (isset($_SESSION["character_id"])) {
    $sql = 'SELECT MIN(id) AS admin FROM characters WHERE script_id='.$_SESSION["script_id"];
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $admin = $row["admin"];
    }
    $conn->close();
    if ($_SESSION["character_id"] != $admin) {
        header("Location: home.php?tab=background");
    }
    else {
        header("Location: admin.php?tab=background");
    }
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
</head>

<body>
    <header id="header">
	    <div class="container">
	        <div class="row align-items-center justify-content-between d-flex">
		        <div id="logo">
		            <a href="#"><img src="img/logo.png" style="height: 50px;"  alt="" title="" /></a>
			    </div>
                <nav id="nav-menu-container">
                    <ul class="nav-menu">
                        <li><a href="return.php">返回主页</a></li>
                    </ul>
                </nav>
		    </div>
	    </div>
    </header><!-- #header -->

    <div class="main-wrap section-gap">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7 col-sm-9 col-11">
                    <form action="login.php" method="post">
                        <div class="mt-10">
                            <input type="text" name="username" required placeholder="用户名" onfocus="this.placeholder=''" onblur="this.placeholder='用户名'" class="single-input" value="<?php echo (isset($_GET["username"])? $_GET["username"]:"") ?>" maxlength="20" autofocus>
                        </div>
                        <div class="mt-10">
                            <input type="password" name="password" required placeholder="密码" onfocus="this.placeholder=''" onblur="this.placeholder='密码'" class="single-input" maxlength="20">
                        </div>
                        <br/>
                        <br/>
                        <div class="mt-10">
                            <center>
                                <button type="submit" name="login" class="genric-btn info circle e-large col-10" style="font-size: 14pt;">登陆</button>
                            </center>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
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



