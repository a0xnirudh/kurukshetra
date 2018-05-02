<?php
/**
 * FAQ's - faq.php
 *
 * PHP version 7.2
 *
 * Admin's can add/edit custom FAQ's based on the challenges/instructions
 * that they want the user to know.
 *
 * Only an admin can edit the FAQ while users can view the FAQ's.
 *
 * @category PHP
 * @package  Kurukshetra
 * @author   Anirudh Anand <a0xnirudh@gmail.com>
 * @license  Apache 2.0
 */

require $_SERVER['DOCUMENT_ROOT'].'/includes/core.php';
require $_SERVER['DOCUMENT_ROOT'].'/database/db_credentials.php';

check_admin(); //not logged in? redirect to login page

list($error,$msg) = array(null,null);
if (isset($_POST) && $_POST != []) {

    $question = $_POST['question'];
    $answer = $_POST['answer'];

    $prevQuery = "INSERT INTO faqs(question,answer) values(?,?)";
    $stmt = $conn->prepare($prevQuery);

    $stmt->bind_param("ss", $question, $answer);
    $stmt->execute();
    $prevResult = mysqli_stmt_affected_rows($stmt);
    if ($prevResult > 0) {
        list($error, $msg) = array(false, "FAQ Added Successfully");
    } else {
        list($error, $msg) = array(true, "FAQ Failed to Added. Check data Once.");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Kurukshetra</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="/staticfiles/css/base.css">
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
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

        td:nth-child(3){
            text-align: left;
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
                    <h3 id='page_title'>Frequently Asked Questions</h3>
                </div>
            </div>
        </nav>

        <div id="page_content">
            <div id="result"></div>
            <?php
            if ($msg) {
                if (!$error) {
                    ?>
                    <div class="alert alert-success">
                        <strong>Success!</strong> <?php echo $msg; ?>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="alert alert-danger">
                        <strong>Danger!</strong> <?php echo $msg; ?>
                    </div>
                    <?php
                }
            }
            ?>
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="container-fluid bg-4">
                        <div class="row no-gutter">
                            <div class="columns">
                                <script src="/staticfiles/js/faq.js"></script>
                                <table id="table" data-pagination="true"></table>
                            </div>
                            <div class="columns" id="add_new">
                                <!-- Default form contact -->
                                <form method="POST" action="faq.php" enctype="multipart/form-data">
                                    <!-- Default input name -->
                                    <label for="name" class="grey-text">Question*</label>
                                    <input type="text" id="question" name="question" class="form-control">

                                    <br>

                                    <label for="name" class="grey-text">Answer*</label>
                                    <textarea type="text" id="answer" name="answer" class="form-control" rows=4></textarea>

                                    <br>
                                    <div class="text-center mt-4">
                                        <br /><p><button class="btn btn-info" type="submit">Add FAQ<i class="fa fa-paper-plane-o ml-2"></i></button></p><br />
                                    </div>
                                </form>
                                <!-- Default form contact -->

                            </div>
                        </div>
                    </div>

                </div>
        </div>
    </div>
    </nav>
</div>
<div class="line"></div>
</div>
</div>
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
