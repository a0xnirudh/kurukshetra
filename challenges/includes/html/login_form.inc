<!DOCTYPE html>
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
                    <div style="text-align: center"><img src="images/logo.png" alt="Logo" width="160px"></div><br>
                    <div class="panel panel-info"  style="text-align: center">
                        <div class="panel-heading">
                            <div class="panel-title">SignIn to Security Playground</div>
                        </div>     

                        <div style="padding-top:30px" class="panel-body" >
                            <form id="loginform" class="form-horizontal" role="form">
                                <div style="margin-top:10px" class="form-group">
                                    <div class="col-sm-12 controls">
                                      <a id="btn-google" href="{{auth_url}}" class="btn btn-danger">Login with Google</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        </body>
        </html>