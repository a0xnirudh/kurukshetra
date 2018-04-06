<?php
include('includes/inc/core.inc');
if(!check_login()) //not logged in? 

	header('Location: login.php'); //redirect to login page
?>

<?php
	include('includes/html/faq.inc'); // displaying faq content
?>