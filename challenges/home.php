<?php
include($_SERVER['DOCUMENT_ROOT'].'/includes/core.php');
if(!check_login()) //not logged in? redirect to login page
    header('Location: /login/index.php'); 
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
        <!-- Our Custom CSS -->
        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <link rel="stylesheet" href="/staticfiles/css/index.css">
        <link rel="stylesheet" href="/staticfiles/css/base.css">
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
                    <li>
                        <a href="faq.php">
                            <i class="glyphicon glyphicon-paperclip"></i>
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
                            <span id="challenge-type-head" class="title">Security Playground Dashboard</span>
                            <div class="pull-right">
                                Welcome <span class="title"><?php welcome_message(); ?> </span>
                            </div>
                        </div>
                    </div>
                </nav>
                <div id="page_content">
                    <div class="col-md-12 challenge-list">
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
                 <script>
                 function show_chall(class_name){
                    console.log('.'+class_name);
                    $('.challenge-box').hide();
                    $('.'+class_name).show();
                 }
        </script>
        <script type="text/javascript">
        $(function() {
           $("li").click(function() {
              // remove classes from all
              $("li").removeClass("active");
              // add class to the one we clicked
              $(this).addClass("active");
           });
        });
        </script>
    </body>
</html>
