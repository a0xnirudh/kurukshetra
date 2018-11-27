<?php
if (file_exists('/var/config/.kurukshetra.ini')) {
    $config = parse_ini_file('/var/config/.kurukshetra.ini');
    if ($config !== []) {
        header('Location: /login/');
        die();
    }
} else {
    exec("touch /var/config/.kurukshetra.ini");
    exec("chmod -R 777 /var/config/.kurukshetra.ini");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kurukshetra Installation Script</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="bootstrap/bootstrap.min.css" rel="stylesheet" />
    <link href="bootstrap/bootstrap-wizard.css" rel="stylesheet" />
    <link rel="shortcut icon" type="image/png" href="/staticfiles/img/favicon.png"/>
    <style type="text/css">
        .wizard-modal p {
            margin: 0 0 10px;
            padding: 0;
        }

        #wizard-ns-detail-servers, .wizard-additional-servers {
            font-size: 12px;
            margin-top: 10px;
            margin-left: 15px;
        }
        #wizard-ns-detail-servers > li, .wizard-additional-servers li {
            line-height: 20px;
            list-style-type: none;
        }
        #wizard-ns-detail-servers > li > img {
            padding-right: 5px;
        }

        .wizard-modal .chzn-container .chzn-results {
            max-height: 150px;
        }
        .wizard-addl-subsection {
            margin-bottom: 40px;
        }
        .create-server-agent-key {
            margin-left: 15px;
            width: 90%;
        }
    </style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv-3.7.0.js"></script>
    <script src="js/respond-1.3.0.min.js"></script>
    <![endif]-->
</head>
<body style="padding:30px;">
<center>
    <div id="DBError" class="alert alert-danger"  style="width:700px">
        <strong>DB Error: </strong> Please use Installation wizard to properly set DB settings
    </div>
    <button id="open-wizard" class="btn btn-success"> Install Kurukshetra</button><br /><br />
    Already installed? <a href="/login/"><button class="btn btn-warning"> login here</button></a>
</center>

<div class="wizard" id="installation-wizard" data-title="Install Kurukshetra">
    <!-- Step 1 Name & passWord -->
    <div class="wizard-card" data-cardname="name">
        <h3>Database Settings</h3>
        <div class="wizard-input-section">
            <p>
                Server ip.
            </p>

            <div class="form-group">
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="serverIp" name="passWord" placeholder="Hostname / IP" data-validate="validateServerLabel" />
                </div>
            </div>
        </div>
        <p>
            Credentials
        </p>

        <div class="wizard-input-section">
            <div class="form-group">
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="userName" name="userName" placeholder="Username" data-validate="validateServerLabel">
                </div>
            </div>
        </div>

        <div class="wizard-input-section">
            <div class="form-group">
                <div class="col-sm-8">
                    <div class="input-group">
                        <input type="password" class="form-control" id="passWord" name="passWord" placeholder="Password" data-validate="validatepassWord" data-is-valid="0" data-lookup="0" />
                        <span class="input-group-btn" id="btn-passWord">
                                    <button class="btn btn-default" id="btn-validate" type="button" onclick='lookup();'>
                                        Validate creds?
                                    </button> </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="wizard-card wizard-card-overlay" data-cardname="services">
        <h3>Google Auth Settings</h3>

        <div class="alert hide">
            These are mandatory for login to work.
        </div>

        <div class="wizard-input-section">
            <p>
                Please update your google oauth credentials below. You can obtain these from <a href="https://console.developers.google.com/apis/credentials" target="_blank">this link</a>
            </p>
            <div class="wizard-input-section">
                <div class="form-group">
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="clientId" name="clientId" placeholder="Google Client ID" data-validate="validateServerLabel" /><br />
                        <input type="text" class="form-control" id="clientSecret" name="clientSecret" placeholder="Google Client Secret" data-validate="validateServerLabel" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery-2.0.3.min.js" type="text/javascript"></script>
<script src="chosen/chosen.jquery.js"></script>
<script src="js/bootstrap.min.js" type="text/javascript"></script>
<script src="js/prettify.js" type="text/javascript"></script>
<script src="bootstrap/bootstrap-wizard.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $.fn.wizard.logging = true;
        var wizard = $('#installation-wizard').wizard({
            keyboard : false,
            contentHeight : 400,
            contentWidth : 700,
            backdrop: 'static'
        });

        $(".chzn-select").chosen();

        wizard.on('closed', function() {
            wizard.reset();
        });

        wizard.on("reset", function() {
            wizard.modal.find(':input').val('').removeAttr('disabled');
            wizard.modal.find('.form-group').removeClass('has-error').removeClass('has-succes');
            wizard.modal.find('#passWord').data('is-valid', 0).data('lookup', 0);
        });

        wizard.on("submit", function(wizard) {
            var data = {
                host: $("#serverIp").val(),
                user: $("#userName").val(),
                pass:$("#passWord").val(),
                clientId:$("#clientId").val(),
                clientSecret:$("#clientSecret").val()
            };

            $.post('validate.php',data,function(data,success){
                console.log(data);
                if(data.test == "succeed"){
                    window.location = "/login/index.php?status=201";
                }
            });
        });

        wizard.el.find(".wizard-success .im-done").click(function() {
            wizard.hide();
            setTimeout(function() {
                wizard.reset();
            }, 250);

        });

        wizard.el.find(".wizard-success .create-another-server").click(function() {
            wizard.reset();
        });

        $('#open-wizard').click(function(e) {
            e.preventDefault();
            wizard.show();
        });
    });

    function validateServerLabel(el) {
        var name = el.val();
        var retValue = {};

        if (name == "") {
            retValue.status = false;
            retValue.msg = "Please enter a value";
        } else {
            retValue.status = true;
        }

        return retValue;
    };

    function validatepassWord(el) {
        var $this = $(el);
        var retValue = {};

        if ($this.is(':disabled')) {
            // passWord Disabled
            retValue.status = true;
        } else {
            if ($this.data('lookup') === 0) {
                retValue.status = false;
                retValue.msg = "Please Validate first";
            } else {
                if ($this.data('is-valid') === 0) {
                    retValue.status = false;
                    retValue.msg = "Validation Failed";
                } else {
                    retValue.status = true;
                }
            }
        }

        return retValue;
    };

    function lookup() {
        // call to the server to preform validation
        var data={
            host: $("#serverIp").val(),
            user: $("#userName").val(),
            pass:$("#passWord").val()
        }
        $.post('validate.php',data,function(data,success){
            if(data.test == "succeed"){
                $('#passWord').data('lookup', 1);
                $('#passWord').data('is-valid', 1);
                $('#btn-validate').text("Valid");
                $('#btn-validate').removeClass('btn-default');
                $('#btn-validate').removeClass('btn-warning');
                $('#btn-validate').addClass('btn-success');
            }
            else{
                $('#passWord').data('lookup', 0);
                $('#passWord').data('is-valid', 0);
                $('#btn-validate').text("Invalid, Validate Again?");
                $('#btn-validate').removeClass('btn-default');
                $('#btn-validate').removeClass('btn-success');
                $('#btn-validate').addClass('btn-warning');
            }
        });
    }

    $(document).ready(function(){
        $("#DBError").hide();
        if(location.hash.substr(1) == "DBError"){
            $("#DBError").show();
        }
        $("#open-wizard").click();
    });

</script>
</body>
</html>
