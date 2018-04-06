<?php
	session_destroy(); //invalidating session
	session_start(); //setting new session
	unset($_SESSION); //clearing session variable values if any
?>

<?php
	include('includes/html/login_form.inc'); //displaying login form
?>