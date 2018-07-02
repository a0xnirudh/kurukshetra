<?php

    require $_SERVER['DOCUMENT_ROOT'].'/includes/core.php';
    error_reporting(0);
    
    $api_key = $_REQUEST['api_key'];
    $bad_api_key['error'] = "bad api key";

    if($api_key != "65d1ef30-c1f8-4d5e-9a53-ea8c1d2deb6c")
        die(json_encode($bad_api_key));

    $email = $_REQUEST['email_id'];
    $level_id = $_REQUEST['level_id'];

    get_challenge_code($email,$level_id);
    $chall_code['status'] = "unlocked";
    $chall_code['email'] = $email;
    $chall_code['level'] = $level_id;
   
    echo json_encode($chall_code);

?>