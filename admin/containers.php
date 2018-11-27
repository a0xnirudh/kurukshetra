<?php
/**
 * Continaer management - container.php
 *
 * PHP version 7.2
 *
 * Admins can Kill containers from the dashboard.
 *
 * @category PHP
 * @package  Kurukshetra
 * @author   Anirudh Anand <a0xnirudh@gmail.com>
 * @license  GPL v3.0
 */

require $_SERVER['DOCUMENT_ROOT'].'/includes/core.php';
check_admin();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" type="image/png" href="/staticfiles/img/favicon.png"/>

    <title>Kurukshetra</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="/staticfiles/css/base.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <link rel="stylesheet" href="/staticfiles/css/bootstrap-table.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="/staticfiles/js/bootstrap-table.min.js"></script>
    <style>
        th {
            text-align: center;
            background-color: #333333;
            color: white;
            min-height: 50px;
        }

    </style>
</head>
<body>
<div class="wrapper">
    <!-- Sidebar Holder -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <button type="button" id="sidebarCollapse" class="btn btn-success navbar-btn">
                <i class="glyphicon glyphicon-align-left"></i>
            </button>
            <strong>PG</strong>
        </div>

        <ul class="list-unstyled components">
            <li>
                <a href="index.php">
                    <i class="glyphicon glyphicon-briefcase"></i>
                    Play Ground
                </a>
            </li>
            <li>
                <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false">
                    <i class="glyphicon glyphicon-home"></i>
                    Challenges
                </a>
                <ul class="collapse list-unstyled" id="homeSubmenu">
                    <li><a href="add_new.php">Add New</a></li>
                    <li><a href="view_edit.php">View / Modify</a></li>
                </ul>
            </li>
            <li>
                <a href="users.php">
                    <i class="glyphicon glyphicon-user"></i>
                    Users
                </a>
            </li>
            <li class="active">
                <a href="#">
                    <i class="glyphicon glyphicon-cloud"></i>
                    Hosted Challenges
                </a>
            </li>
            <li>
                <a href="faq.php">
                    <i class="glyphicon glyphicon-screenshot"></i>
                    FAQ
                </a>
            </li>
            <li>
                <a href="mailto:<?php echo get_admin_email(); ?>">
                    <i class="glyphicon glyphicon-send"></i>
                    Contact
                </a>
            </li>
            <li>
                <a href="/login/logout.php">
                    <i class="glyphicon glyphicon-log-out"></i>
                    Logout
                </a>
            </li>
        </ul>
    </nav>

    <!-- Page Content Holder -->
    <div id="content">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <h3 id='page_title'>User Management</h3>
                </div>
            </div>
        </nav>

        <div id="page_content">
            <div id="result"></div>
            <script src="/staticfiles/js/containers.js"></script>
            <table id="table" data-pagination="true"></table>
        </div>
        <div class="line"></div>
    </div>
</div>
<!-- jQuery CDN -->
<!-- Bootstrap Js CDN -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });
</script>
</body>
</html>
