    <br><br><br>

	<?php
    $sql = "SELECT * FROM maps";
    $result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
        echo '
    <section>
        <div class="container">
            <div class="section-top-border">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-8 col-md-9 col-sm-10">
                        <center>
                            <h3 style="margin-bottom: 20px;">'.$row["description"].'</h3>
                            <img src="'.$row["file_path"].'">
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </section>';
    }