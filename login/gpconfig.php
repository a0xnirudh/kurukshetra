<?php
session_start();

//Include Google client library 
include_once 'src/Google_Client.php';
include_once 'src/contrib/Google_Oauth2Service.php';

/*
 * Configuration and setup Google API
 */

$config = parse_ini_file('/var/config/.kurukshetra.ini'); //read from config file

$clientId = $config['clientId'];
$clientSecret = $config['clientSecret'];

$redirectURL = "http://" . $_SERVER['SERVER_NAME']. "/login/index.php";

//Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('Login to Kurukshetra');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectURL);

$google_oauthV2 = new Google_Oauth2Service($gClient);
