<?php
include('includes/inc/core.inc');
if(!check_login()) //not logged in? redirect to login page

	header('Location: login.php'); 
?>

<?php
	include('includes/html/index.inc'); //displaying home page html content
?>