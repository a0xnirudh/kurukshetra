<?php

require $_SERVER['DOCUMENT_ROOT'].'/includes/core.php';
check_admin(); //not logged in? redirect to login page

$url = "http://127.0.0.1:2376";

function httpPost($url, $params=null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,  CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

    $output=curl_exec($ch);

    curl_close($ch);
    return $output;
}

function httpDelete($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

    $output = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);
    return $output;
}

if(isset($_GET['id'])) {
    // Killing the container
    httpPost($url . "/containers/" . $_GET['id'] . "/kill");

    // Removing the container
    httpDelete($url . "/containers/" . $_GET['id'] . "?force=1");
}