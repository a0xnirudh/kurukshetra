<?php
session_start();

//Include Google client library 
include_once 'src/Google_Client.php';
include_once 'src/contrib/Google_Oauth2Service.php';

/*
 * Configuration and setup Google API
 */
$clientId = '127411714284-9ugvrbt7ijgn4o74cnsmveh075h221ep.apps.googleusercontent.com';
$clientSecret = '0Eek0PSRtfr0MW-TTC3V89kE';
$redirectURL = "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . "/security-playground/login/index.php";

//Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('Login to Security Playground');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectURL);

$google_oauthV2 = new Google_Oauth2Service($gClient);
?>