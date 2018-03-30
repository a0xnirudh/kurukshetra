<?php
/**
 * Created by Anirudh Anirudh.
 * User: a0xnirudh
 * Date: 8/9/15
 */


$host = 'localhost';
$db_user = 'database username';
$db_pass = 'database password';
$db_name = 'database name';

$connection = mysqli_connect($host, $db_user, $db_pass, $db_name);

//checking the connection
if (!$connection) {
    die("DB connection failed");
}
