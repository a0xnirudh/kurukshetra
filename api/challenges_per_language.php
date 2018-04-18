<?php
    include($_SERVER['DOCUMENT_ROOT'].'/includes/core.php');
    check_login(); //not logged in? redirect to login page
    $all_challenges = chall_per_lang();
    $challs = [];
    foreach ($all_challenges as $challenge) {
        $challenge['y'] = (int)$challenge['y'];
        array_push($challs,$challenge);
    }
    echo json_encode($challs);
?>