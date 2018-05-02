<?php
/**
 * Edit challenges - view_edit.php
 *
 * PHP version 7.2
 *
 * The existing challenges can be edited by the admins which includes all
 * the challenge details like introduction, challenge code, unit tests etc.
 *
 * @category PHP
 * @package  Kurukshetra
 * @author   Anirudh Anand <a0xnirudh@gmail.com>
 * @license  Apache 2.0
 */

require $_SERVER['DOCUMENT_ROOT'].'/includes/core.php';
check_admin(); //not logged in? redirect to login page

if (!isset($error)) {
    list($error, $msg) = array(null, null);
}

if (isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Security PlayGround</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="/staticfiles/css/base.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.12.1/bootstrap-table.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.12.1/bootstrap-table.min.js"></script>
    <style>
        th {
            text-align: center;
            background-color: #333333;
            color: white;
            min-height: 50px;
        }

        #challenge_data td:nth-child(odd) {
            width: 120px;
        }

        #challenge_data table {
            border: solid 1px #333333;
            text-align: center;
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
            <li class="active">
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
                <a href="mailto:security@flipkart.com">
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
                    <h3 id='page_title'>
                        <?php
                        if (isset($id)) {
                            echo "Update Challenge Details";
                        } else {
                            echo "Available Challenges";
                        }
                        ?>
                    </h3>
                </div>
            </div>
        </nav>
        <div id="page_content">
            <div id="result"></div>
            <?php
            print_message($error, $msg);
            if (isset($_POST) && $_POST != []) {
                list($error, $msg) = update_challenge($_POST, $_FILES);
                print_message($error, $msg);
            }

            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $challenge = get_challenge($id);
                show_challenge($id, $challenge);
            } else {
                show_all_challenges();
            }
            ?>
            <table id="table" data-pagination="true"></table>
            <div id="pop">
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="myModalLabel">Challenge Details</h4>
                            </div>
                            <div class="modal-body">
                                <table id="challenge_data">
                                    <tr>
                                        <td>Name</td><td id="chall_name"></td>
                                    </tr>
                                    <tr>
                                        <td>Introduction</td><td id="chall_intro"></td>
                                    </tr>
                                    <tr>
                                        <td>Instructions</td><td id="chall_instr"></td>
                                    </tr>
                                    <tr>
                                        <td>References</td><td id="chall_ref"></td>
                                    </tr>
                                    <tr>
                                        <td>Hints</td><td id="chall_hints"></td>
                                    </tr>
                                    <tr>
                                        <td>Code</td><td id="chall_code"></td>
                                    </tr>
                                    <tr>
                                        <td>Unittests</td><td id="chall_unit"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="line"></div>
            </div>
        </div>
</body>
</html>
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
