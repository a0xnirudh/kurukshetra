<?php
include('includes/inc/core.inc');
if(!check_login()) //not logged in? 

	header('Location: ../login/index.php'); //redirect to login page
?>

<?php
	include('includes/html/faq.inc'); // displaying faq content
?>