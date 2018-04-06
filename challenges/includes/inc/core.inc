<?php
	session_start();
	require '../database/db-credentials.php';

	//function to check logged in or not
	function check_login(){
		return isset($_SESSION['userData']['email']);
	}

	function welcome_message()
	{
		echo $_SESSION['userData']['first_name']." ".$_SESSION['userData']['last_name'];
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

	function get_categories(){
		global $connection;
		$query = "SELECT distinct id,name from categories;# where approved = 1 and enabled = 1";
		$result = mysqli_query($connection, $query);
		return $result;
		// print_r($result);
	}

	function get_challenges(){
		global $connection;
		$cats = get_categories();
		$query = "SELECT * from challenges where type in (SELECT distinct name from categories)";
		$result = mysqli_query($connection, $query);
		// var_dump($result);
		return $result;
	}

	function get_defficulties(){
		global $connection;
		$query = "SELECT distinct name from difficulties";
		$result = mysqli_query($connection, $query);
		return $result;
	}

	function get_faqs(){
		global $connection;
		$query = "SELECT question,answer from faqs";
		$result = mysqli_query($connection, $query);
		return $result;	
	}
?>










