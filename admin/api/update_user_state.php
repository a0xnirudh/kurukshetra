<?php
require $_SERVER['DOCUMENT_ROOT'].'/includes/core.php';
check_admin(); //not logged in? redirect to login page
if (isset($_POST['id']) && isset($_POST['action'])) {
    $id = $_POST['id'];
    $action = $_POST['action'];
    $result = [];
    if ($action == 'make_admin' || $action == 'remove_admin') {
        $result = admin_update_user($id, $action);
    } else if ($action == 'enabled' || $action == 'disabled') {
        $result = enable_disable_user($id, $action);
    } else {
        $result['success'] = false;
        $result['action'] = "invalid";
    }
} else {
    $result['success'] = false;
    $result['action'] = "invalid";
}
echo json_encode($result);
