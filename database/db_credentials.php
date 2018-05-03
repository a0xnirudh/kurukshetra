<?php

error_reporting(0);

try {
    $config = parse_ini_file('/var/config/.kurukshetra.ini');
    if ($config == []) {
        header("Location: /installation/index.php#DBError");
        die();
    }
    $conn = new mysqli($config['servername'],$config['username'],$config['password']);

    if ($conn->connect_error) {
        header("Location: /installation/index.php#DBError");
        die();
    }
    
    if (!mysqli_select_db($conn, $config['dbname'])) {
        ?>
        <div class="mainbox col-md-6 col-md-offset-3" style="margin-top:30px">
            <div id="DBError" class="alert alert-success" data-toggle="collapse" data-target="#setup-log">
                <strong>[>] Setting up DB... </strong>
                <div id="setup-log" class="collapse">
                <?php
                    include 'db_setup.php';
                ?>
                </div>
            </div>
        </div>
        
        <?php
    }
}
catch(Exception $e)
{
    echo $e->getMessage();
}