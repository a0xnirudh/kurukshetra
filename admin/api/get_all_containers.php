<?php

require $_SERVER['DOCUMENT_ROOT'].'/includes/core.php';
check_admin(); //not logged in? redirect to login page
header('Content-Type: application/json');
echo get_all_containers();
?>