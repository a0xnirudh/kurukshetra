<?php
	session_start();

	//function to check logged in or not
	function check_login(){
		if(isset($_SESSION['admin_login'])) 
		{
			if($_SESSION['admin_login'] === 1)
				return 1;
		}
		else{
			$_SESSION['admin_login'] = 0;
		}
		return 0;
	}

	//function to perform login action
	//replace with proper DB based login later
	function login_action(){
		if($_POST['username'] == 'mohan' && $_POST['password'] == 'mohan')
		{
			$_SESSION['admin_login'] = 1;
			$_SESSION['admin_name'] = 'Mohan Krushna';
			return True;
		}
		return Flase;
	}

	function display_login(){
		include('../html/login_form.inc');
	}
?>
