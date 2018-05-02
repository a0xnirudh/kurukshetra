<?php
/**
 * Add new challenges - add_new.php
 *
 * PHP version 7.2
 *
 * Using this file, we can upload challenges into the database which
 * will automatically gets populated during the challenge listing.
 *
 * The challenge source code is actually saved as base64 in the database
 *
 * @category PHP
 * @package  Kurukshetra
 * @author   Anirudh Anand <a0xnirudh@gmail.com>
 * @license  Apache 2.0
 */

require $_SERVER['DOCUMENT_ROOT'].'/includes/core.php';
check_admin(); //not logged in? redirect to login page

list($error, $msg) = array(null,null);
if (isset($_POST) && $_POST != array()) {
    $post = array_map('htmlspecialchars', $_POST);
    list($error, $msg) = add_challenge($post, $_FILES);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" type="image/png" href="/staticfiles/img/favicon.png"/>
    <title>Kurukshetra</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="/staticfiles/css/base.css">
</head>
<body>
<div class="wrapper">
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
                    <li><a href="#">Add New</a></li>
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
                    <h3 id='page_title'>Add New Challenge</h3>
                </div>
            </div>
        </nav>

        <div id="page_content">
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
                    <div class="collapse navbar-collapse add_new" id="bs-example-navbar-collapse-1">

                        <form method="POST" action="add_new.php" enctype="multipart/form-data">
                            <!-- Default input name -->
                            <label for="name" class="grey-text">Challenge Name*</label>
                            <input type="text" id="name" name="name" class="form-control">
                            <br>
                            <label for="language" class="grey-text">Challenge Language</label>
                            <select class="form-control" id="language" name="language">
                                <?php
                                $languages = get_languages();
                                foreach ($languages as $language) {
                                    $language = $language['name'];
                                    echo '<option value="'.$language.'">'.strtoupper($language).'</option>';
                                }
                                ?>
                            </select>
                            <br>
                            <label for="difficulty" class="grey-text">Challenge Type</label>
                            <select class="form-control" id="type" name="type">
                                <?php
                                $categories = get_categories();
                                foreach ($categories as $category) {
                                    $category = $category['name'];
                                    echo '<option value="'.$category.'">'.strtoupper($category).'</option>';
                                }
                                ?>
                            </select>
                            <br>
                            <label for="difficulty" class="grey-text">Challenge Difficulty</label>
                            <select class="form-control" id="difficulty" name="difficulty">
                                <?php
                                $difficulties = get_difficulties();
                                foreach ($difficulties as $difficulty) {
                                    $difficulty = $difficulty['name'];
                                    echo '<option value="'.$difficulty.'">'.$difficulty.'</option>';
                                }
                                ?>
                            </select>
                            <br>
                            <label for="code" class="grey-text">Upload Challenge Code*</label>
                            <input type="file" class="form-control-file" id="code" name="code">
                            <br>
                            <label for="unittests" class="grey-text">Upload Unit Tests*</label>
                            <input type="file" class="form-control-file" id="unittests" name="unittests">
                            <br>
                            <label for="intro" class="grey-text">Challenge Introduction*</label>
                            <textarea type="text" id="intro" name="intro" class="form-control" rows="4"></textarea>
                            <br>
                            <label for="instructions" class="grey-text">Challenge Instructions</label>
                            <textarea type="text" id="instructions" name="instructions" class="form-control" rows="4"></textarea>
                            <br>
                            <label for="references" class="grey-text">Challenge References</label>
                            <textarea type="text" id="references" name="references" class="form-control" rows="4"></textarea>
                            <br>
                            <label for="references" class="grey-text">Challenge Hints</label>
                            <textarea type="text" id="hints" name="hints" class="form-control" rows="4"></textarea>
                            <br>
                            <div class="text-center mt-4">
                                <br /><p><button class="btn btn-info" type="submit">Send<i class="fa fa-paper-plane-o ml-2"></i></button></p>
                            </div>
                        </form>
                    </div>
                </div>
            </nav>
        </div>
        <div class="line"></div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
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
