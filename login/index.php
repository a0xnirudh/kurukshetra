<?php

require_once 'user.php';
require_once 'gpconfig.php';

/**
 * Prints success/error messages
 *
 * Function which prints the error messages depending up on success or failure
 * of an action
 *
 * @param boolean $error Trigger error if true
 * @param string  $msg   Message printed to the user
 *
 * @return void
 */
function Print_message($error, $msg)
{
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
}


if (isset($_GET['code'])) {
    $gClient->authenticate($_GET['code']);
    $_SESSION['token'] = $gClient->getAccessToken();
    header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
    $gClient->setAccessToken($_SESSION['token']);
}

if ($gClient->getAccessToken()&& !(isset($_GET['status'])&&$_GET['status'] == 403)) {
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

    $userCount = $user->checkUserCount();
    if ($userCount == 1) {
        $user->setAdmin($gpUserData);
    }

    $adminData = $user->checkAdmin($gpUserData);

    if ($adminData == 1) {
        header('Location: /admin/');
    } else {
        header('Location: /challenges/');
    }
    die();
    
} else {
    $authUrl = $gClient->createAuthUrl();
    ?>
    <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="robots" content="noindex, nofollow">

            <title>Login &amp; SignIn to Kurukshetra</title>
                <meta name="viewport" content="width=device-width, initial-scale=1">
            <link href="/staticfiles/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
            <link href="/staticfiles/css/bootstrap-social.css" rel="stylesheet" id="bootstrap-css-social">
            <link href="/staticfiles/css/font-awesome.css" rel="stylesheet" id="font-awesome-css">
            <style type="text/css">
            
            </style>
            <script src="/staticfiles/css/jquery-1.10.2.min.js"></script>
            <script src="/staticfiles/css/bootstrap.min.js"></script>
        </head>
        <body>
                <div class="container">    
                <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                    <!-- <div style="text-align: center"><img src="images/logo.png" alt="Logo" width="160px" height="160px" ></div><br> -->
                    <?php if(isset($_GET['status']) && $_GET['status'] == 403) Print_message(true, "User Not allowed to login. Contact administrator."); ?>
                    <?php if(isset($_GET['status']) && $_GET['status'] == 201) Print_message(false, "Database setup successful. Please login using google oauth. First user to login will become \"admin user\" :)"); ?>
                    <div class="panel panel-info"  style="text-align: center">
                        <div class="panel-heading">
                            <div class="panel-title">SignIn to Kurukshetra</div>
                        </div>     

                        <div style="padding-top:30px" class="panel-body" >
                            <form id="loginform" class="form-horizontal" role="form">
                                <div style="margin-top:10px" class="form-group">
                                    <div class="col-sm-12 controls">
                                      <a id="btn-google" href="<?php echo filter_var($authUrl, FILTER_SANITIZE_URL); ?>" class="btn btn-danger">Login with Google</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        </body>
        </html>

<?php
}
?>