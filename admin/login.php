<?php
	include('includes/inc/core.inc'); //including core functions

	if(isset($_POST['username'])) //if login form submitted
	{
        if(login_action()) //if login success
    		header('Location: index.php');
	}
    if(check_login()) //if already logged in
    {
        header('Location: index.php'); //redirect to home page
    }
	else
	{
        include('includes/html/login_form.inc'); //displaying login form
    }
?>
