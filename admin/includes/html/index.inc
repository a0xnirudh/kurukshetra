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
        <link rel="stylesheet" href="includes/css/base.css">
    </head>
    <body>
        <div class="wrapper">
            <!-- Sidebar Holder -->
            <nav id="sidebar" class="active">
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
                            <li><a href="approve_reject.php">Approve / Reject</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="users.php">
                            <i class="glyphicon glyphicon-link"></i>
                            Users
                        </a>
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
                        <a href="logout.php">
                            <i class="glyphicon glyphicon-send"></i>
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
                            <h3 id='page_title'>Security Playground</h3>
                        </div>
                    </div>
                </nav>

                <div id="page_content">
                    Content About Playground
                </div>
                <div class="line"></div>
            </div>
        </div>
        <!-- jQuery CDN -->
         <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
         <!-- Bootstrap Js CDN -->
         <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </body>
</html>
