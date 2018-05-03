<?php
/**
 * Add new challenges - faq.php
 *
 * PHP version 7.2
 *
 * FAQ page where all the Frequently Asked Questions will be listed.
 *
 * Only Admins can add/edit the FAQ's.
 *
 * @category PHP
 * @package  Kurukshetra
 * @author   Anirudh Anand <a0xnirudh@gmail.com>
 * @license  Apache 2.0
 */

require $_SERVER['DOCUMENT_ROOT'] . '/includes/core.php';

if (!check_login()) { //not logged in?
    header('Location: /login/index.php'); //redirect to login page
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

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../staticfiles/css/index.css">
    <link rel="stylesheet" href="/staticfiles/css/base.css">
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
                <a href="home.php">
                    <i class="glyphicon glyphicon-home"></i>
                    Home
                </a>
            </li>
            <li>
                <a href="index.php#homeSubmenu" data-toggle="collapse" aria-expanded="false">
                    <i class="glyphicon glyphicon-briefcase"></i>
                    Challenges
                </a>
                <ul class="collapse list-unstyled" id="homeSubmenu">
                    <li>
                        <a href="index.php#all" onclick="show_all()" class="glyphicon glyphicon-chevron-right">
                            All
                        </a>
                    </li>
                    <?php
                    $categories = get_categories();

                    foreach($categories as $category){
                        echo '<li id="challenge-'.strtolower($category['name']).'"><a class="glyphicon glyphicon-chevron-right" id="'.$category['id'].'" href="index.php#'.strtolower($category['name']).'" onclick="show_chall(\''.strtolower($category['name']).'\')"> '.$category['name'].'</a></li>';
                    }
                    ?>
                </ul>
            </li>
            <li class="active">
                <a href="faq.php">
                    <i class="glyphicon glyphicon-paperclip"></i>
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
                    <span id="challenge-type-head" class="title">FAQ Page</span>
                    <div class="pull-right">
                        Welcome <span class="title"><?php welcome_message(); ?> </span>
                    </div>
                </div>
            </div>


        </nav>
        <div id="page_content">
            <div class="panel-group" id="faqAccordion" >
                <?php
                $faqs = get_faqs();
                foreach ($faqs as $faq) {
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question<?php echo $faq['id']; ?>">
                            <h4 class="panel-title">
                                <a href="#" class="ing">Q: <?php echo $faq['question']; ?></a>
                            </h4>

                        </div>
                        <div id="question<?php echo $faq['id']; ?>" class="panel-collapse collapse" style="height: 0px;">
                            <div class="panel-body">
                                <p><?php echo $faq['answer']; ?> </p>
                            </div>
                        </div>
                    </div>
                    <?php
                    // echo '<p class="faqs text-justify"><span><strong>Q</strong></span> '.$faq['question'].'</br>';
                    // echo '<span><strong>A</strong></span> '.$faq['answer'].'</br></p></br>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<div class="line"></div>
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
