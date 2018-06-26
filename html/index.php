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

function removeDirectory($path) {
    $files = glob($path . '/*');
    foreach ($files as $file) {
        is_dir($file) ? removeDirectory($file) : unlink($file);
    }
    rmdir($path);
    return;
}

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
if (isset($_POST["submit1"])) {
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
            mkdir("scripts/".$row1["id"], 0777, true);
            mkdir("scripts/".$row1["id"].'/maps', 0777, true);
            mkdir("scripts/".$row1["id"].'/clues', 0777, true);
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
            $sql2 = "INSERT INTO status(id, value) VALUES(2, 0)";
            $conn2->query($sql2);
            $sql2 = "CREATE TABLE sections(id int PRIMARY KEY AUTO_INCREMENT, sequence int, type int, title VARCHAR(30), chapter int) ENGINE=InnoDB DEFAULT CHARSET=utf8";
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
                description varchar (20),
                file_path varchar(50)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            $conn2->query($sql2);
            $sql2 = "
            CREATE TABLE locations(
                id int PRIMARY KEY,
                name varchar (20)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            $conn2->query($sql2);
            $sql2 = 'INSERT INTO locations(id, name) VALUES(0, "秘密线索")';
            $conn2->query($sql2);
            $sql2 = "
            CREATE TABLE clues(
                id int PRIMARY KEY AUTO_INCREMENT,
                chapter int,
                location_id int,
                position varchar(20),
                points int,
                self_description varchar(300),
                description varchar(300),
                file_path varchar(50),
                unlock_id int,
                unlock_characters varchar(30),
                FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            $conn2->query($sql2);
            $sql2 = "
            CREATE TABLE character_clue(
                id int PRIMARY KEY AUTO_INCREMENT,
                character_id int,
                clue_id int,
                owner int,
                FOREIGN KEY (character_id) REFERENCES characters(id) ON DELETE CASCADE,
                FOREIGN KEY (clue_id) REFERENCES clues(id) ON DELETE CASCADE,
                UNIQUE KEY (character_id, clue_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            $conn2->query($sql2);
            $conn2->close();
        }
        $conn->close();
        header("Location: index.php");
    }
}

// handle delete script(s)
if (isset($_POST["submit2"])) {
    if ($_POST["password"] != "Lu@dashen666") {
        echo '
    <script type="text/javascript">
        alert("密码不正确，创建剧本失败！");
        window.location = "index.php";
    </script>';
    }
    else {
        foreach ($_POST["script_ids"] as $script_id) {
            $sql = "DELETE FROM script_names WHERE id=".$script_id;
            $conn->query($sql);
            $sql = "DROP DATABASE rp_".$script_id;
            $conn->query($sql);
            removeDirectory("scripts/".$script_id);
        }
        header("Location: index.php");
    }
}
?>

<!DOCTYPE html>
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
                        <li><a href="#" onclick="open_modal1()">添加新剧本</a></li>
                        <li><a href="#" onclick="open_modal2()">删除剧本</a></li>
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
        <div id="myModal1" class="modal" style="top: 30%;">
            <div class="modal-content col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-10 offset-1">
                <div class="modal-header">
                    <h4 id="modal_title" class="modal-title">添加新剧本</h4>
                    <span class="close" style="float: right;" onclick="close_modal1()">&times;</span>
                </div>
                <div class="modal-body">
                    <center style="width: 100%;">
                        <label style="margin-bottom: 20px; text-align: left; width: 80px;">剧本名称：</label><input required name="name" style="margin-bottom: 20px; width: 200px;" type="text" maxlength="50">
                        <br>
                        <label style="text-align: left; width: 80px;">密码：</label><input style="width: 200px;" required name="password" type="password">
                    </center>
                </div>
                <div class="modal-footer justify-content-center" style="font-size: 14pt;">
                    <button class="genric-btn info circle e-large" name="submit1">确定</button>
                    <button type="button" class="genric-btn info circle e-large" onclick="close_modal1()">关闭</button>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal for deleting the script -->
    <form method="post" action="index.php">
        <div id="myModal2" class="modal" style="top: 30%;">
            <div class="modal-content col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-10 offset-1">
                <div class="modal-header">
                    <h4 id="modal_title" class="modal-title">删除剧本</h4>
                    <span class="close" style="float: right;" onclick="close_modal2()">&times;</span>
                </div>
                <div class="modal-body">
                    <center style="width: 100%;">
                        <label style="margin-bottom: 10px; width: 280px; text-align: left;">剧本名称：</label>
                        <?php
                        $sql = "SELECT * FROM script_names";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            echo '
                        <div style="text-align: left; width: 280px; margin-bottom: 10px;">
                            <input type="checkbox" name="script_ids[]" value="'.$row["id"].'">&nbsp;'.$row["name"].'
                        </div>';
                        }
                        ?>
                        <br><label style="text-align: left; width: 80px; margin-top: 20px;">密码：</label><input style="width: 200px;" required name="password" type="password">
                    </center>
                </div>
                <div class="modal-footer justify-content-center" style="font-size: 14pt;">
                    <button class="genric-btn info circle e-large" name="submit2">确定</button>
                    <button type="button" class="genric-btn info circle e-large" onclick="close_modal2()">关闭</button>
                </div>
            </div>
        </div>
    </form>

    <script type="text/javascript">
        var modal1 = document.getElementById("myModal1");
        var modal2 = document.getElementById("myModal2");
        function open_modal1(id, position, points) {
            modal1.style.display = "block";
            modal2.style.display = "none";
        }
        function close_modal1() {
            modal1.style.display = "none";
        }
        function open_modal2(id, position, points) {
            modal2.style.display = "block";
            modal1.style.display = "none";
        }
        function close_modal2() {
            modal2.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == modal1) {
                modal1.style.display = "none";
            }
            if (event.target == modal2) {
                modal2.style.display = "none";
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
