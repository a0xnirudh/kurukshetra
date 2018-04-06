<?php
include('includes/inc/core.inc');
if(!check_login()) //not logged in? redirect to login page

    header('Location: ../login/index.php'); 
?>

<?php
    include('includes/html/home.inc'); //displaying home page html content
?>