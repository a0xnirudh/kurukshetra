<?php
//Include GP config file && User class
include_once 'gpconfig.php';
include_once 'user.php';

if(isset($_GET['code'])){
    $gClient->authenticate($_GET['code']);
    $_SESSION['token'] = $gClient->getAccessToken();
    header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
    $gClient->setAccessToken($_SESSION['token']);
}

if ($gClient->getAccessToken()) {
    //Get user profile data from google
    $gpUserProfile = $google_oauthV2->userinfo->get();
    
    //Initialize User class
    $user = new User();
    
    //Insert or update user data to the database
    $gpUserData = array(
        'oauth_provider'=> 'google',
        'oauth_uid'     => $gpUserProfile['id'],
        'first_name'    => $gpUserProfile['given_name'],
        'last_name'     => $gpUserProfile['family_name'],
        'email'         => $gpUserProfile['email'],
        'gender'        => $gpUserProfile['gender'],
        'locale'        => $gpUserProfile['locale'],
        'picture'       => $gpUserProfile['picture'],
        'link'          => $gpUserProfile['link']
    );
    $userData = $user->checkUser($gpUserData);
    
    //Storing user data into session
    $_SESSION['userData'] = $userData;
    header('Location: ../challenges/');
    die();
    
    
} else {
    $authUrl = $gClient->createAuthUrl();
    $output = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="robots" content="noindex, nofollow">

            <title>Login &amp; SignIn to Security Playground</title>
                <meta name="viewport" content="width=device-width, initial-scale=1">
            <link href="../staticfiles/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
            <link href="../staticfiles/css/bootstrap-social.css" rel="stylesheet" id="bootstrap-css-social">
            <link href="../staticfiles/css/font-awesome.css" rel="stylesheet" id="font-awesome-css">
            <style type="text/css">
            
            </style>
            <script src="../staticfiles/css/jquery-1.10.2.min.js"></script>
            <script src="../staticfiles/css/bootstrap.min.js"></script>
            </script>
        </head>
        <body>
                <div class="container">    
                <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
                    <div class="panel panel-info" >
                            <div class="panel-heading">
                                <div class="panel-title">SignIn to Security Playground</div>
                                <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#">Forgot password?</a></div>
                            </div>     

                            <div style="padding-top:30px" class="panel-body" >

                                <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                                    
                                <form id="loginform" class="form-horizontal" role="form">
                                            
                                    <div style="margin-bottom: 25px" class="input-group">
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                                <input id="login-username" type="text" class="form-control" name="username" value="" placeholder="username or email">                                        
                                            </div>
                                        
                                    <div style="margin-bottom: 25px" class="input-group">
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                                <input id="login-password" type="password" class="form-control" name="password" placeholder="password">
                                            </div>
                                            

                                        
                                    <div class="input-group">
                                              <div class="checkbox">
                                                <label>
                                                  <input id="login-remember" type="checkbox" name="remember" value="1"> Remember me
                                                </label>
                                              </div>
                                            </div>


                                        <div style="margin-top:10px" class="form-group">
                                            <!-- Button -->

                                            <div class="col-sm-12 controls">
                                              <a id="btn-login" href="#" class="btn btn-success">Login  </a>
                                              <a id="btn-google" href='. filter_var($authUrl, FILTER_SANITIZE_URL) . ' class="btn btn-danger">Login with Google</a>

                                            </div>
                                        </div>
                          
                            </div>  
                </div>
            
            <script type="text/javascript">
            
            </script>
        </body>
        </html>';
}
?>

<div><?php echo $output; ?></div>
