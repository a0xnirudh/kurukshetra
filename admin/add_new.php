<?php
include($_SERVER['DOCUMENT_ROOT'].'/includes/core.php');
check_admin(); //not logged in? redirect to login page

list($error,$msg) = array(null,null);
if(isset($_POST) && $_POST != array()){
    include($_SERVER['DOCUMENT_ROOT'].'/database/db_credentials.php');
    
    $name = $_POST['name'];
    $code = base64_encode(file_get_contents($_FILES['code']['tmp_name']));
    $unittests = base64_encode(file_get_contents($_FILES['unittests']['tmp_name']));
    $intro = $_POST['intro'];
    $instructions = $_POST['instructions'];
    $references = $_POST['references'];
    $approved = 1;
    $enabled = 1;
    $difficulty = strtolower($_POST['difficulty']);
    $type = strtolower($_POST['type']);
    $language = strtolower($_POST['language']);

    if($difficulty == 'Easy')
        $points = 10;
    if($difficulty == 'Medium')
        $points = 20;
    if($difficulty == 'Hard')
        $points = 30;

    $prevQuery = "INSERT INTO challenges(name,code,intro,instructions,reference,approved,enabled,points,difficulty,type,language) values(?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($prevQuery);

    $stmt->bind_param("sssssdddsss",$name,$code,$intro,$instructions,$references,$approved,$enabled,$points,$difficulty,$type,$language);
    $stmt->execute();
    $prevResult = mysqli_stmt_affected_rows($stmt);
    
    if($prevResult)
        list($error,$msg) = array(false,"Challenge Added Successfully");
    else
        list($error,$msg) = array(true,"Challenge Failed to Added. Check data Once.");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Security PlayGround</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="/staticfiles/css/base.css">
    </head>
    <body>
        <div class="wrapper">
            <nav id="sidebar">
                <div class="sidebar-header">
                    <button type="button" id="sidebarCollapse" class="btn btn-success navbar-btn">
                        <i class="glyphicon glyphicon-align-left"></i>
                    </button>
                    <strong>PG</strong>
                </div>

                <ul class="list-unstyled components">
                    <li>
                        <a href="index.php">
                            <i class="glyphicon glyphicon-briefcase"></i>
                            Play Ground
                        </a>
                    </li>
                    <li class="active">
                        <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false">
                            <i class="glyphicon glyphicon-home"></i>
                            Challenges
                        </a>
                        <ul class="collapse list-unstyled" id="homeSubmenu">
                            <li><a href="#">Add New</a></li>
                            <li><a href="view_edit.php">View / Modify</a></li>
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
                        <a href="/login/logout.php">
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
                            <h3 id='page_title'>Add New Challenge</h3>
                        </div>
                    </div>
                </nav>

                <div id="page_content">
                    <?php
                        if($msg){
                            if(!$error){
                                ?>
                                <div class="alert alert-success">
                                    <strong>Success!</strong> <?php echo $msg; ?>
                                </div>
                                <?php
                            }
                            else{
                                ?>
                                <div class="alert alert-danger">
                                    <strong>Danger!</strong> <?php echo $msg; ?>
                                </div>
                                <?php
                            }
                        }
                    ?>
                    <nav class="navbar navbar-default">
                    <div class="container-fluid">
                        <div class="collapse navbar-collapse add_new" id="bs-example-navbar-collapse-1">
                            
                    <form method="POST" action="add_new.php" enctype="multipart/form-data">
                        <!-- Default input name -->
                        <label for="name" class="grey-text">Challenge Name*</label>
                        <input type="text" id="name" name="name" class="form-control">
                        <br>
                        <label for="language" class="grey-text">Challenge Language</label>
                        <select class="form-control" id="language" name="language">
                        <?php
                            $languages = get_languages();
                            foreach($languages as $language)
                            {
                                $language = $language['name'];
                                echo '<option value="'.$language.'">'.strtoupper($language).'</option>';
                            }
                        ?>
                        </select>
                        <br>
                        <label for="difficulty" class="grey-text">Challenge Type</label>
                        <select class="form-control" id="type" name="type">
                        <?php
                            $categories = get_categories();
                            foreach($categories as $category)
                            {
                                $category = $category['name'];
                                echo '<option value="'.$category.'">'.strtoupper($category).'</option>';
                            }
                        ?>
                        </select>
                        <br>
                        <label for="difficulty" class="grey-text">Challenge Difficulty</label>
                        <select class="form-control" id="difficulty" name="difficulty">
                        <?php
                            $difficulties = get_difficulties();
                            foreach($difficulties as $difficulty)
                            {
                                $difficulty = $difficulty['name'];
                                echo '<option value="'.$difficulty.'">'.$difficulty.'</option>';
                            }
                        ?>
                        </select>
                        <br>
                        <label for="code" class="grey-text">Upload Challenge Code*</label>
                        <input type="file" class="form-control-file" id="code" name="code">
                        <br>
                        <label for="unittests" class="grey-text">Upload Unit Tests*</label>
                        <input type="file" class="form-control-file" id="unittests" name="unittests">
                        <br>
                        <label for="intro" class="grey-text">Challenge Introduction*</label>
                        <textarea type="text" id="intro" name="intro" class="form-control" rows="4"></textarea>
                        <br>
                        <label for="instructions" class="grey-text">Challenge Instructions</label>
                        <textarea type="text" id="instructions" name="instructions" class="form-control" rows="4"></textarea>
                        <br>
                        <label for="references" class="grey-text">Challenge References</label>
                        <textarea type="text" id="references" name="references" class="form-control" rows="4"></textarea>
                        <br>
                        <div class="text-center mt-4">
                            <br /><p><button class="btn btn-info" type="submit">Send<i class="fa fa-paper-plane-o ml-2"></i></button></p>
                        </div>
                    </form>
                </div>
                </div>
                </nav>
                </div>
                <div class="line"></div>
            </div>
        </div>
         <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
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
