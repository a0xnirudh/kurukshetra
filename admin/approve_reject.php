<?php
include('includes/inc/core.inc');
if(!check_login()) //not logged in? redirect to login page

	header('Location: login.php');
?>

<?php
	include('includes/html/approve_reject.inc'); // displaying approve / rejct page content
?>