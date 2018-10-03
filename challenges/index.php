<?php
/**
 * Challenge listing page - index.php
 *
 * PHP version 7.2
 *
 * All the uploaded challenges are listed here which includes the
 * category/language/type etc.
 *
 * @category PHP
 * @package  Kurukshetra
 * @author   Anirudh Anand <a0xnirudh@gmail.com>
 * @license  Apache 2.0
 */
require $_SERVER['DOCUMENT_ROOT'].'/includes/core.php';
if (!check_login()) {//not logged in? redirect to login page
    header('Location: /login/index.php');
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

    <link href="/staticfiles/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="/staticfiles/js/bootstrap-toggle.min.js"></script>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="/staticfiles/css/index.css">
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
            <li class="active">
                <a href="index.php#homeSubmenu" data-toggle="collapse" aria-expanded="false">
                    <i class="glyphicon glyphicon-briefcase"></i>
                    Challenges
                </a>
                <ul class="collapse list-unstyled" id="homeSubmenu">
                    <li id="challenge-all">
                        <a href="#all" onclick="show_all()" class="glyphicon glyphicon-chevron-right">
                            All
                        </a>
                    </li>
                    <?php
                    $categories = get_categories();
                    foreach ($categories as $category){
                        echo '<li id="challenge-'.strtolower($category['name']).'"><a class="glyphicon glyphicon-chevron-right" id="'.$category['id'].'" href="#" onclick="show_chall(\''.strtolower($category['name']).'\')"> '.$category['name'].'</a></li>';
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
                    <span id="challenge-type-head" class="title">All Challenges</span>
                    <div class="btn-group pull-right" id="easy-med-hard-btn" role="group">
                        Welcome <span class="title"><?php welcome_message(); ?> </span></br></br>


                        <?php
                        $difficulties = get_difficulties();
                        foreach ($difficulties as $difficulty) {
                            $difficulty = $difficulty['name'];
                            echo '<button type="button" class="btn btn-default '.strtolower($difficulty).'" id="'.strtolower($difficulty).'" onclick="show_chall(\''.strtolower($difficulty).'\')">'.$difficulty.'</button>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </nav>
        <?php
                        
            if($_GET['error'] == "unauthorised")
                Print_message(true, "User Not authorized.");

        ?>
        <style>
        #chall_url_btn {
            text-decoration: None;
            border: 1px solid #337ab7;
            color: #337ab7;
            text-transform: bold;
        }

        #chall_url_btn:hover {
            background-color: #337ab7;
            color: white;
        }

        .show_loading {
            display: inline-block;
        }

        .hide_loading {
            display: none;
        }

        </style>
        <div id="page_content">
            <div class="col-md-12 challenge-list">
                <?php
                $result = get_challenges();

                if($result->num_rows == 0)
                    echo "<center><label>No Challenges available for you. Try again later.</label></center></label>";

                foreach ($result as $row) {

                    echo '<div class="challenge-box '.$row['type'].' '.$row['difficulty'].'">
                                <br>
                                <a id="'.$row['name'].'" class="challenge-title" href="challenge.php?id='.$row['id'].'">'.$row['name'].'</a>';

                    if(check_user_challenge_status($row['id']))
                        echo "<font color='green'> (completed)</font>";

                    if(isset($_SESSION['challenge_status'][(int)$row['id']]['port']))
                        {
                            $checked = 'checked';
                            $port = $_SESSION['challenge_status'][(int)$row['id']]['port'];
                            $protocal = $_SERVER['REQUEST_SCHEME'].'://';
                            $chall_url = $protocal.$_SERVER['SERVER_NAME'].':'.$port;
                        }
                    else
                        {
                            $checked = '';
                            $port = None;
                            $protocal = $_SERVER['REQUEST_SCHEME'].'://';
                            $chall_url = $protocal.$_SERVER['SERVER_NAME'].':'.$port;
                        }

                    echo '<div style="float: right; padding-right: 3%;">
                            <span class="hide_loading" id="loading_'.$row['id'].'"><font color="#337ab7" class="glyphicon glyphicon-hourglass"> Loading.... </font> </span>
                            <label id="label_'.$row['id'].'">';

                    if($checked != ''){
                        echo '<button id="chall_url_btn" class="btn"><a href="'.$chall_url.'" target="_blank">Lab url</a></button>';
                    }
                    echo '</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input id='.$row['id'].' '.$checked.' type="checkbox" onchange="host_chall('.$row['id'].',this.checked)" data-toggle="toggle" data-on="Stop" data-off="Start" data-onstyle="danger" data-offstyle="success" >
                          </div>';
                    echo '<p class="challenge-description">
                                    '.$row['intro'].'
                                </p>
                                <p class="challenge-type">Language : <span style="color:#535353">' . $row['language'] . '</span></p>
                                <p class="challenge-type">Type : <span style="color:#535353">' . $row['type'] . '</span></p>
                                <p class="challenge-type">Difficulty : <span class="difficulty-'.$row['difficulty'].' '.$row['difficulty'].'">' . $row['difficulty'] . '</span></p>
                                <div class="button-style">
                                    <button onclick="window.open(\'challenge.php?id=' . $row['id'] . '\', \'_blank\');" class="solve-challenge-btn btn btn-primary">Solve Challenge</button>
                                </div>
                                </div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- jQuery CDN -->
<script src="/staticfiles/js/jquery-1.12.0.min.js"></script>
<script src="/staticfiles/js/bootstrap-toggle.js"></script>

<!-- Bootstrap Js CDN -->
<script src="/staticfiles/js/3.3.7/bootstrap.min.js"></script>

<script type="text/javascript">
function host_chall(id, enabled){

    var action = "stop";
    if(enabled == true){
        action = "start";
    }

    var data = "id="+id+"&action="+action;
    $('#loading_'+id).toggleClass("show_loading");

      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        $('#loading_'+id).toggleClass("hide_loading");
        if (this.readyState == 4 && this.status == 200) {
            var resp = JSON.parse(this.responseText);
            if(resp.success)
            {
                if(resp.action == "start")
                    document.getElementById("label_"+id).innerHTML = "<button id='chall_url_btn' class='btn'><a href='http://"+document.domain+":"+resp.port+"' target='_blank'>Lab url</a></button>";

                if(resp.action == "stop")
                    document.getElementById("label_"+id).innerHTML = "";
            }
                
        }
      };
      xhttp.open("POST", "/challenges/test.php", true);
      xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhttp.send(data);

}
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });

        hash = location.hash.substr(1);
        if (hash == 'all' || hash == "")
            show_all();
        else
            show_chall(hash);
    });
</script>
<script>
    function show_chall(class_name){
        $('.challenge-box').hide();
        $('.'+class_name).show();
        class_name = class_name.toUpperCase();
        $('#challenge-type-head').text(class_name+" Challenges");
    }

    function show_all(){
        $('.challenge-box').show();
        $('#challenge-type-head').text('All Challenges');
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
