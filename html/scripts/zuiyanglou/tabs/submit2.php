    <br><br><br>
    <section>
        <div class="container">
            <div class="section-top-border">
                <div class="row d-flex justify-content-between">
                    <div class="col-lg-10 offset-lg-1 col-md-10 offset-md-1 col-sm-10 offset-sm-1">
                        <h2 style="margin-bottom: 20px;">【郑伟业】之死：</h2>
                        <?php
                        $sql1 = "SELECT COUNT(V.vote) AS count, P.chinese_name, P.username FROM players AS P LEFT JOIN (SELECT * FROM vote WHERE murder=1) AS V ON P.username=V.vote GROUP BY P.username ORDER BY COUNT(V.vote) DESC";
                        $result1 = $conn->query($sql1);
                        $counter = 1;
                        $max = 0;
                        while ($row1 = $result1->fetch_assoc()) {
                            if ($row1["count"] > $max) $max = $row1["count"];
                            echo '
                        <div style="width: 100%; border: 1px solid #BBBBBB;">
                            <p><a style="font-weight: bold;">【'.$row1["chinese_name"].'】</a>的得票：'.$row1["count"].' 票';
                            $sql2 = 'SELECT P.chinese_name FROM players AS P LEFT JOIN vote AS V ON P.username=V.person WHERE murder=1 AND V.vote="'.$row1["username"].'"';
                            $result2 = $conn->query($sql2);
                            if ($result2->num_rows > 0) echo "（";
                            while ($row2 = $result2->fetch_assoc()) {
                                echo "【".$row2["chinese_name"]."】";
                            }
                            if ($result2->num_rows > 0) echo "）";
                            echo '
                            </p>
                            <div class="percentage" style="margin-bottom: 16px;">
                                <div class="progress-bar color-'.$counter.'" role="progressbar" style="height: 16px; width: '.intval($row1["count"] / $max * 100).'%"></div>
                            </div>
                        </div>';
                            $counter = $counter + 1;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="section-top-border">
                <div class="row d-flex justify-content-between">
                    <div class="col-lg-10 offset-lg-1 col-md-10 offset-md-1 col-sm-10 offset-sm-1">
                        <h2 style="margin-bottom: 20px;">【傅明义】之死：</h2>
                        <?php
                        $sql1 = "SELECT COUNT(V.vote) AS count, P.chinese_name, P.username FROM players AS P LEFT JOIN (SELECT * FROM vote WHERE murder=2) AS V ON P.username=V.vote GROUP BY P.username ORDER BY COUNT(V.vote) DESC";
                        $result1 = $conn->query($sql1);
                        $counter = 1;
                        $max = 0;
                        while ($row1 = $result1->fetch_assoc()) {
                            if ($row1["count"] > $max) $max = $row1["count"];
                            echo '
                        <div style="width: 100%; border: 1px solid #BBBBBB;">
                            <p><a style="font-weight: bold;">【'.$row1["chinese_name"].'】</a>的得票：'.$row1["count"].' 票';
                            $sql2 = 'SELECT P.chinese_name FROM players AS P LEFT JOIN vote AS V ON P.username=V.person WHERE murder=2 AND V.vote="'.$row1["username"].'"';
                            $result2 = $conn->query($sql2);
                            if ($result2->num_rows > 0) echo "（";
                            while ($row2 = $result2->fetch_assoc()) {
                                echo "【".$row2["chinese_name"]."】";
                            }
                            if ($result2->num_rows > 0) echo "）";
                            echo '
                            </p>
                            <div class="percentage" style="margin-bottom: 16px;">
                                <div class="progress-bar color-'.$counter.'" role="progressbar" style="height: 16px; width: '.intval($row1["count"] / $max * 100).'%"></div>
                            </div>
                        </div>';
                            $counter = $counter + 1;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>