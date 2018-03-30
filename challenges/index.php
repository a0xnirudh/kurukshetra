<?php
session_start();

//Check if user is authenticated or redirect to login
if(!isset($_SESSION['userData']['email']))
{
    header('Location: ../login/index.php');
    die();
}

require __DIR__ . '/../database/db-credentials.php';

// Get all the challenge details from DB
$query = "SELECT * from challenges";
$result = mysqli_query($connection, $query);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Hackademic</title>
        <link rel="stylesheet" href="../staticfiles/css/bootstrap.min.css">
        <link rel="stylesheet" href="../staticfiles/css/animate.css">
        <link rel="stylesheet" href="../staticfiles/css/font-awesome.min.css">
        <link rel="stylesheet" href="../staticfiles/css/index.css">
        <link rel="stylesheet" href="../staticfiles/css/jquery-ui.css" />
        <script src="../staticfiles/js/jquery-2.1.3.js"></script>
        <script src="../staticfiles/js/jquery-ui.js"></script>
        <link href='https://fonts.googleapis.com/css?family=Josefin+Sans|Bree+Serif|Righteous' rel='stylesheet' type='text/css'>

    <body>
        
    <!-- Navbar -->
        <nav class="nav" id="nav">
            <div class="container">
                <div class="navbar-header animated bounceInLeft">
                    <p class="nav-bar-name name">Hackademic</p>
                </div>
                <div class="title-main">
                    <p>Challenges</p>
                </div>

            </div>
        </nav>
        
    <!-- Easy Medium and Hard filter button -->
        <div id="challenge-division" class="col-md-3">
            <p id="challenge-division-heading">Challenges</p>
            <ul id="challenges">
                <li class="challenge" id="challenge-all">All Challenges</li>
                <li class="challenge" id="challenge-sql">SQL</li>
                <li class="challenge" id="challenge-xss">XSS</li>
            </ul>
        </div>
        
        
        <div class="btn-group pull-right" id="easy-med-hard-btn" role="group">
          <button type="button" class="btn btn-default" id="easy">Easy</button>
          <button type="button" class="btn btn-default" id="med">Medium</button>
          <button type="button" class="btn btn-default" id="hard">Hard</button>
        </div>
        
        <div class="col-md-8 challenge-list">
            
            
            <p id="challenge-type-head">All Challenges</p>
            
            <?php

            while($row = mysqli_fetch_array($result)) { 

            echo '
                <div class="challenge-box challenge-' . $row['type'] . '-' . $row['difficulty'] . '">
                <br>
                <a id="' . $row['name'] . '" class="challenge-title" href="challenge.php?id=' . $row['id'] . '">' . $row['name'] . '</a>
                <p class="challenge-description">
                    ' . $row['intro'] . '
                </p>
                <p class="challenge-type">Language : <span style="color:#535353">' . $row['language'] . '</span></p>
                <p class="challenge-type">Type : <span style="color:#535353">' . $row['type'] . '</span></p>
                <p class="challenge-type">Difficulty : <span class="difficulty-easy">' . $row['difficulty'] . '</span></p>
                <div class="button-style">
                    <button onclick="window.open(\'challenge.php?id=' . $row['id'] . '\', \'_blank\');" class="solve-challenge-btn btn btn-primary">Solve Challenge</button>
                </div></div>

                ';
            }
                ?> 
            
            
        </div>
        
        <script>
            var type = 0;
            
                function sql_show_all() {
                    $('.challenge-sql-easy').show();
                    $('.challenge-sql-med').show();
                    $('.challenge-sql-hard').show();
                }
                    
                function sql_hide_all() {
                    $('.challenge-sql-easy').hide();
                    $('.challenge-sql-med').hide();
                    $('.challenge-sql-hard').hide();
                }
                
                function xss_show_all() {
                    $('.challenge-xss-easy').show();
                    $('.challenge-xss-med').show();
                    $('.challenge-xss-hard').show();
                }
                
                function xss_hide_all() {
                    $('.challenge-xss-easy').hide();
                    $('.challenge-xss-med').hide();
                    $('.challenge-xss-hard').hide();
                }
            
                function xss_show_easy() {
                    $('.challenge-xss-easy').show();
                    $('.challenge-xss-med').hide();
                    $('.challenge-xss-hard').hide();
                }
            
                function xss_show_med() {
                    $('.challenge-xss-easy').hide();
                    $('.challenge-xss-med').show();
                    $('.challenge-xss-hard').hide();
                }
            
                function xss_show_hard() {
                    $('.challenge-xss-easy').hide();
                    $('.challenge-xss-med').hide();
                    $('.challenge-xss-hard').show();
                }
            
                function sql_show_easy() {
                    $('.challenge-sql-easy').show();
                    $('.challenge-sql-med').hide();
                    $('.challenge-sql-hard').hide();
                }
                function sql_show_med() {
                    $('.challenge-sql-easy').hide();
                    $('.challenge-sql-med').show();
                    $('.challenge-sql-hard').hide();
                }
                function sql_show_hard() {
                    $('.challenge-sql-easy').hide();
                    $('.challenge-sql-med').hide();
                    $('.challenge-sql-hard').show();
                }
            
                function remove_color_button() {
                    $( "#easy" ).removeClass( "easy-color" );
                    $( "#med" ).removeClass( "med-color" );
                    $( "#hard" ).removeClass( "hard-color" );
                }
            
                
            $(document).ready(function(){
                
                $("#challenge-all").click(function(){
                    sql_show_all();
                    xss_show_all();
                    $('#challenge-type-head').text("All challenges");
                    type = 0;
                    remove_color_button();
                    
                     $('#challenge-all').addClass('challenge-select');
                    $('#challenge-sql').removeClass('challenge-select');
                    $('#challenge-xss').removeClass('challenge-select');
                });
                $("#challenge-sql").click(function(){
                    xss_hide_all();
                    sql_show_all();
                    $('#challenge-type-head').text("SQL");
                    type = 1;
                    remove_color_button();
                    
                    $('#challenge-all').removeClass('challenge-select');
                    $('#challenge-sql').addClass('challenge-select');
                    $('#challenge-xss').removeClass('challenge-select');
                });
                $("#challenge-xss").click(function(){
                    sql_hide_all();
                    xss_show_all();
                    $('#challenge-type-head').text("XSS");
                    type = 2;
                    remove_color_button();
                    
                    $('#challenge-all').removeClass('challenge-select');
                    $('#challenge-sql').removeClass('challenge-select');
                    $('#challenge-xss').addClass('challenge-select');
                });
                

                $('#easy').click(function() {
                    remove_color_button();
                    $( "#easy" ).addClass( "easy-color" );
                    if(type == 0) {
                        sql_show_easy();
                        xss_show_easy();
                    }
                    else if(type == 1) {
                        sql_show_easy();
                        xss_hide_all();
                    }
                    else if(type == 2) {
                        xss_show_easy();
                        sql_hide_all();
                    }
                              
                });
                
                $('#med').click(function() {
                    remove_color_button();
                    $( "#med" ).addClass( "med-color" );
                    if(type == 0) {
                        sql_show_med();
                        xss_show_med();
                    }
                    else if(type == 1) {
                        sql_show_med();
                        xss_hide_all();
                    }
                    else if(type == 2) {
                        xss_show_med();
                        sql_hide_all();
                    }
                });
                
                $('#hard').click(function() {
                    remove_color_button();
                    $( "#hard" ).addClass( "hard-color" );
                    if(type == 0) {
                        sql_show_hard();
                        xss_show_hard();
                    }  
                    else if(type == 1) {
                        sql_show_hard();
                        xss_hide_all();
                    }
                    else if(type == 2) {
                        xss_show_hard();
                        sql_hide_all();
                    }
                }); 
            });
        </script>
    </body>
</html>
