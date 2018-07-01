    <br><br><br>
    <style type="text/css">
        table, th, td {
            border: 1px solid #BBBBBB;
            border-collapse: collapse;
        }
    </style>
    <section>
        <div class="container">
            <div class="section-top-border">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-9 col-md-9 col-sm-10">
                        <p>
                            <?php
                            $sql = 'SELECT * FROM background WHERE script_id='.$script_id.' AND type=2';
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                echo replace_text($pairs, $row["content"]);
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    $chapters = array();
    $sql = 'SELECT * FROM sections WHERE type=2 AND chapter=1 AND script_id='.$script_id;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) array_push($chapters, 1);
    $sql = 'SELECT * FROM sections WHERE type=2 AND chapter=2 AND script_id='.$script_id;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) array_push($chapters, 2);
    foreach ($chapters as $chapter) {
        if (sizeof($chapters) == 1) {
            $title = "时间线";
        }
        else {
            if ($chapter == 1) $title = "时间线（第一幕）";
            else $title = "时间线（第二幕）";
        }
        echo '
    <section>
        <div class="container">
            <div class="section-top-border">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-8 col-md-9 col-sm-10">
                        <center>
                            <h3 style="margin-bottom: 20px;">'.$title.'</h3>
                        </center>
                    </div>
                    <table style="width: 100%;">
                        <tr>
                            <th></th>';
        $character_ids = array();
        $sql = 'SELECT * FROM characters WHERE script_id='.$script_id;
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            if ($row["id"] != $admin) {
                array_push($character_ids, $row["id"]);
                echo '
                            <th style="min-width: 150px; text-align: center;">'.$row["name"].'</th>';
            }
        }
        echo '
                        </tr>';

        $sql = 'SELECT hour, minute FROM timelines WHERE chapter='.$chapter.' AND script_id='.$script_id.' GROUP BY hour, minute ORDER BY hour ASC, minute ASC';
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $hour = $row["hour"];
            $minute = $row["minute"];
            echo '
                        <tr>
                            <td>'.($hour<10? ("0".$hour):$hour).":".($minute<10? ("0".$minute):$minute).'</td>';

            foreach ($character_ids as $character_id) {
                $content = "";
                $sql1 = 'SELECT content FROM timelines WHERE chapter='.$chapter.' AND character_id='.$character_id.' AND hour='.$hour.' AND minute='.$minute;
                $result1 = $conn->query($sql1);
                while ($row1 = $result1->fetch_assoc()) {
                    $content = $row1["content"];
                }
                echo '
                            <td>'.$content.'</td>';
            }

            echo '
                        </tr>';
        }

        echo '
                    </table>
                </div>
            </div>
        </div>
    </section>';
    }
    ?>