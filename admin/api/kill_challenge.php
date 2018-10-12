<?php

    require $_SERVER['DOCUMENT_ROOT'].'/includes/core.php';
    check_admin(); //not logged in? redirect to login page

    $url = "http://127.0.0.1:2376";

    if(isset($_GET['id'])) {
        // Killing the container
        $result = httpPost($url . "/containers/" . $_GET['id'] . "/kill");

        // Removing the container
        httpDelete($url . "/containers/" . $_GET['id'] . "?force=1");

        // Update the DB
        update_container_status($_GET['id']);

        if(!$result or $result == "")
        {
            $output = array("status" => True);
            header('Content-Type: application/json');
            echo json_encode($output);
        }
    }
    else 
        echo '{"message": "No challenge ID passed."}';
?>