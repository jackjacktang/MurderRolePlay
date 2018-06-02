<!DOCTYPE html>
<html lang="zxx" class="no-js">
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
    <title>谋杀之谜</title>

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
$db_host = "localhost";
$db_user = "root";
$db_password = "Lu636593";
$db = "rp";
$conn = new mysqli($db_host, $db_user, $db_password, $db);
if (mysqli_connect_errno()) {
    echo mysqli_connect_error();
}
$conn->set_charset("utf8");

session_start();
if (isset($_POST["script_name"])) {
    $_SESSION["script_name"] = $_POST["script_name"];
    $sql = 'Select * from rp WHERE english="'.$_POST["script_name"].'"';
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $_SESSION["script_chinese"] = $row["chinese"];
    }
    header("Location: public/index.php");
}
?>



    <header id="header">
        <div class="container">
            <div class="row align-items-center justify-content-between d-flex">
                <div id="logo">
                    <a href="index.php"><img src="img/logo.png" style="height: 50px;"  alt="" title="" /></a>
                </div>
            </div>
        </div>
    </header><!-- #header -->

    <br><br><br>
    <form method="post" action="index.php">
        <section>
            <div class="container">
                <div class="section-top-border">
                    <div class="row justify-content-center">
                        <div class="col-lg-9 col-md0 col-sm-10">
                            <img src="img/cover.jpg" style="width: 100%;"/>
                        </div>
                        <?php
                        $sql = "SELECT * FROM rp";
                        $result = $conn->query($sql);
                        $counter = 0;
                        while ($row = $result->fetch_assoc()) {
                            echo '
                        <div class="col-lg-4 col-md-4 col-sm-6 col-10" style="margin-top: 30px;">
                            <button class="genric-btn info circle" style="width:100%; font-size: 16pt;" value="'.$row["english"].'" name="script_name">'.$row["chinese"].'</button>
                        </div>';
                            $counter = $counter + 1;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </form>

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
