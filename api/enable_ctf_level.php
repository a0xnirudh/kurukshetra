<?php

    require $_SERVER['DOCUMENT_ROOT'].'/includes/core.php';
    error_reporting(0);
    
    $api_key = $_REQUEST['api_key'];
    $bad_api_key['error'] = "bad api key";
    $enabled = 1;

    if(isset($_POST['action']) and $_POST['action'] == "disable")
        $enabled = 0;

    $token = get_dev_token();

    if($api_key != $token)
        die(json_encode($bad_api_key));

    $email = $_REQUEST['email_id'];
    $level_id = $_REQUEST['level_id'];

    get_challenge_code($email,$level_id,$enabled);
    $chall_code['status'] = "success";
    $chall_code['email'] = $email;
    $chall_code['level'] = $level_id;
   
    echo json_encode($chall_code);
    die();

?>