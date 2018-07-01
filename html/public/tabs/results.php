    <br><br><br>
    <?php
    $sql = 'SELECT * FROM votes WHERE script_id='.$script_id;
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo '
    <section>
        <div class="container">
            <div class="section-top-border">
                <div class="row d-flex justify-content-between">
                    <div class="col-lg-10 offset-lg-1 col-md-10 offset-md-1 col-sm-10 offset-sm-1">
                        <center>
                            <h3 style="margin-bottom: 20px;">'.$row["description"].'</h3>
                        </center>';

        $sql1 = 'SELECT COUNT(V.selection) AS count, C.name, C.id FROM (SELECT * FROM characters WHERE script_id='.$script_id.' and id<>'.$admin.') AS C LEFT JOIN (SELECT * FROM character_vote WHERE vote_id='.$row["id"].') AS V ON C.id=V.selection GROUP BY C.id ORDER BY COUNT(V.selection) DESC';
        $result1 = $conn->query($sql1);
        $counter = 1;
        $max = 0;
        while ($row1 = $result1->fetch_assoc()) {
            if ($row1["count"] > $max) $max = $row1["count"];
            echo '
                        <div style="width: 100%; border: 1px solid #BBBBBB;">
                            <p><a style="font-weight: bold;">【'.$row1["name"].'】</a>的得票：'.$row1["count"].' 票';
            $sql2 = 'SELECT C.name FROM characters AS C LEFT JOIN character_vote AS V ON C.id=V.character_id WHERE V.vote_id='.$row["id"].' AND V.selection='.$row1["id"];
            $result2 = $conn->query($sql2);
            if ($result2->num_rows > 0) echo "（";
            while ($row2 = $result2->fetch_assoc()) {
                echo "【".$row2["name"]."】";
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

        echo '
                    </div>
                </div>
            </div>
        </div>
    </section>';
    }
    ?>