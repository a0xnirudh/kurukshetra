<?php
/**
 * Admin index page - index.php
 *
 * PHP version 7.2
 *
 * Index page for admin where charts based on type/severity/languages
 * are shown to the admin
 *
 * @category PHP
 * @package  Kurukshetra
 * @author   Anirudh Anand <a0xnirudh@gmail.com>
 * @license  Apache 2.0
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
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src='/staticfiles/js/index.js'></script>
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
            <li class="active">
                <a href="#">
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
                    <h3 id='page_title'>Kurukshetra</h3>
                </div>
            </div>
        </nav>

        <div id="page_content">
            <div class="container-fluid bg-4 text-center">
                <div class="row no-gutter">
                    <div class="columns col-sm-4">
                        <div id="container" style="min-width: 310px; height: 300px; max-width: 600px; margin: 0 auto"></div>
                    </div>
                    <div class="columns col-sm-4">
                        <div id="container2" style="min-width: 310px; height: 300px; max-width: 600px; margin: 0 auto"></div>
                    </div>
                    <div class="columns col-sm-4">
                        <div id="container3" style="min-width: 310px; height: 300px; max-width: 600px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="line"></div>
    </div>
</div>
<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
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
