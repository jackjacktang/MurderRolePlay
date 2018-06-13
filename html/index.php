<!DOCTYPE html>
<?php
session_start();

// Link to database
$db_host = "localhost";
$db_user = "root";
$db_password = "Lu636593";
$db1 = "rp";
$conn = new mysqli($db_host, $db_user, $db_password, $db1);
if (mysqli_connect_errno()) {
    echo mysqli_connect_error();
}
$conn->set_charset("utf8");

// Handle click on the script
if (isset($_GET["script_id"])) {
    $_SESSION["script_id"] = $_GET["script_id"];
    $sql = 'SELECT name FROM script_names WHERE id='.$_GET["script_id"];
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $_SESSION["script_name"] = $row["name"];
    }
}
if (isset($_SESSION["script_id"])) {
    $conn->close();
    header("Location: public/login.php");
}

// Handle create new script
if (isset($_POST["submit"])) {
    if ($_POST["password"] != "Lu@dashen666") {
        echo '
    <script type="text/javascript">
        alert("密码不正确，创建剧本失败！");
        window.location = "index.php";
    </script>';
    }
    else {
        $sql = 'INSERT INTO script_names(name) VALUES("'.$_POST["name"].'");';
        $conn->query($sql);
        $sql1 = 'SELECT id FROM script_names WHERE name="'.$_POST["name"].'"';
        $result1 = $conn->query($sql1);
        while ($row1 = $result1->fetch_assoc()) {
            mkdir("scripts/".$row1["id"]);
            mkdir("scripts/".$row1["id"].'/maps');
            mkdir("scripts/".$row1["id"].'/clues');
            $sql2 = 'CREATE DATABASE rp_'.$row1["id"];
            $conn->query($sql2);
            $db2 = "rp_".$row1["id"];
            $conn2 = new mysqli($db_host, $db_user, $db_password, $db2);
            $conn2->set_charset("utf8");
            $sql2 = "
            CREATE TABLE characters(
                id int PRIMARY KEY AUTO_INCREMENT,
                username VARCHAR(20),
                password VARCHAR(20),
                name VARCHAR(20),
                preferred_name VARCHAR(20),
                description VARCHAR(100),
                points int DEFAULT 0) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            $conn2->query($sql2);
            $sql2 = 'INSERT INTO characters(username, password, name, preferred_name, description, points) VALUES("admin", "admin", "组织者", "", "你是组织者，拥有至高无上的权力！", 0)';
            $conn2->query($sql2);
            $sql2 = "CREATE TABLE background(id int PRIMARY KEY, content varchar(3000)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            $conn2->query($sql2);
            $sql2 = "CREATE TABLE status(id int PRIMARY KEY, value int) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            $conn2->query($sql2);
            $sql2 = "INSERT INTO status(id, value) VALUES(1, 1)";
            $conn2->query($sql2);
            $sql2 = "CREATE TABLE sections(id int PRIMARY KEY AUTO_INCREMENT, sequence int, type int, title VARCHAR(20), chapter int) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            $conn2->query($sql2);
            $sql2 = "
            CREATE TABLE character_section(
                id int PRIMARY KEY AUTO_INCREMENT,
                character_id int,
                section_id int,
                content VARCHAR(20000),
                FOREIGN KEY (character_id) REFERENCES characters(id) ON DELETE CASCADE,
                FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            $conn2->query($sql2);
            $sql2 = "
            CREATE TABLE timelines(
                id int PRIMARY KEY AUTO_INCREMENT,
                character_id int,
                chapter int,
                hour int,
                minute int,
                content VARCHAR(400),
                FOREIGN KEY (character_id) REFERENCES characters(id) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            $conn2->query($sql2);
            $sql2 = "
            CREATE TABLE objectives(
                id int PRIMARY KEY AUTO_INCREMENT,
                character_id int,
                chapter int,
                content varchar (400),
                points int,
                FOREIGN KEY (character_id) REFERENCES characters(id) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            $conn2->query($sql2);
            $sql2 = "
            CREATE TABLE maps(
                id int PRIMARY KEY AUTO_INCREMENT,
                description varchar (20)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            $conn2->query($sql2);
            $conn2->close();
        }
        $conn->close();
        header("Location: index.php");
    }
}
?>
<html lang="zxx" class="no-js">
<head>
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon-->
    <link rel="shortcut icon" href="public/img/icon.png">
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
    <link rel="stylesheet" href="public/css/linearicons.css">
    <link rel="stylesheet" href="public/css/font-awesome.min.css">
    <link rel="stylesheet" href="public/css/bootstrap.css">
    <link rel="stylesheet" href="public/css/magnific-popup.css">
    <link rel="stylesheet" href="public/css/nice-select.css">                  
    <link rel="stylesheet" href="public/css/animate.min.css">
    <link rel="stylesheet" href="public/css/owl.carousel.css">
    <link rel="stylesheet" href="public/css/main.css">
</head>

<body>
    <header id="header">
        <div class="container">
            <div class="row align-items-center justify-content-between d-flex">
                <div id="logo">
                    <a href="#"><img src="public/img/logo.png" style="height: 50px;"  alt="" title="" /></a>
                </div>
                <nav id="nav-menu-container">
                    <ul class="nav-menu">
                        <li><a href="#" onclick="open_modal()">添加新剧本</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <br><br><br>
    <!-- Show all the script -->
    <form method="get" action="index.php">
        <section>
            <div class="container">
                <div class="section-top-border">
                    <div class="row justify-content-center">
                        <div class="col-lg-9 col-md0 col-sm-10">
                            <img src="public/img/cover.jpg" style="width: 100%;"/>
                        </div>
                        <?php
                        $sql = "SELECT * FROM script_names ORDER BY id ASC";
                        $result = $conn->query($sql);
                        $counter = 0;
                        while ($row = $result->fetch_assoc()) {
                            echo '
                        <div class="col-lg-4 col-md-4 col-sm-6 col-10" style="margin-top: 30px;">
                            <button class="genric-btn info circle" style="width:100%; font-size: 16pt;" value="'.$row["id"].'" name="script_id">'.$row["name"].'</button>
                        </div>';
                            $counter = $counter + 1;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </form>

    <!-- Modal for creating the new script -->
    <form method="post" action="index.php">
        <div id="myModal" class="modal" style="top: 30%;">
            <div class="modal-content col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-10 offset-1">
                <div class="modal-header">
                    <h4 id="modal_title" class="modal-title"></h4>
                    <span class="close" style="float: right;" onclick="close_modal()">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <label style="margin-bottom: 20px;" class="col-lg-4 col-md-10 col-sm-10">剧本名称：</label><input required name="name" style="margin-bottom: 20px;" type="text" maxlength="50" class="col-lg-6 col-md-7 col-sm-10">
                        <label class="col-lg-4 col-md-10 col-sm-10">密码：</label><input required name="password" type="password" class="col-lg-6 col-md-7 col-sm-10">
                    </div>
                </div>
                <div class="modal-footer justify-content-center" style="font-size: 14pt;">
                    <button class="genric-btn info circle e-large" name="submit">确定</button>
                    <button type="button" class="genric-btn info circle e-large" onclick="close_modal()">关闭</button>
                </div>
            </div>
        </div>
    </form>

    <script type="text/javascript">
        var modal = document.getElementById("myModal");
        function open_modal(id, position, points) {
            modal.style.display = "block";
        }
        function close_modal() {
            modal.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

    <?php
    $conn->close();
    ?>

    <script src="public/js/vendor/jquery-2.2.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="public/js/vendor/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhOdIF3Y9382fqJYt5I_sswSrEw5eihAA"></script>
    <script src="public/js/easing.min.js"></script>
    <script src="public/js/hoverIntent.js"></script>
    <script src="public/js/superfish.min.js"></script>
    <script src="public/js/jquery.ajaxchimp.min.js"></script>
    <script src="public/js/jquery.magnific-popup.min.js"></script>
    <script src="public/js/owl.carousel.min.js"></script>
    <script src="public/js/jquery.sticky.js"></script>
    <script src="public/js/jquery.nice-select.min.js"></script>
    <script src="public/js/parallax.min.js"></script>
    <script src="public/js/mail-script.js"></script>
    <script src="public/js/main.js"></script>

</body>
</html>
