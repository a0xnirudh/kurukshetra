<?php

# Killing all containers
require $_SERVER['DOCUMENT_ROOT'].'/includes/core.php';

$url = "http://127.0.0.1:2376";

$container_id = get_container_details($_SESSION['userData']['email']);

foreach ($container_id as $id) {
    // Killing the container
    httpPost($url . "/containers/" . $id . "/kill");

    // Removing the container
    httpDelete($url . "/containers/" . $id . "?force=1");

    // Update the DB
    update_container_status($id);

}

//Include GP config file
include_once 'gpconfig.php';

//Unset token and user data from session
unset($_SESSION['token']);
unset($_SESSION['userData']);

//Reset OAuth access token
$gClient->revokeToken();

//Destroy entire session
session_destroy();

//Redirect to homepage
header("Location: index.php");
?>