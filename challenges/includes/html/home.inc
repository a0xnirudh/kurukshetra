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
        <link rel="stylesheet" href="../staticfiles/css/index.css">
        <link rel="stylesheet" href="includes/css/base.css">
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
                        <a href="../login/logout.php">
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
                            <span id="challenge-type-head" class="title">Home page - need updation or delete this</span>
                            <div class="pull-right">
                                Welcome <span class="title"><?php welcome_message(); ?> </span>
                            </div>
                        </div>
                    </div>
                </nav>
                <div id="page_content">
                    <div class="col-md-12 challenge-list">
                        {{UPDATE CONTENT HERE}}
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
