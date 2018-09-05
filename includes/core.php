<?php

session_start();
require $_SERVER['DOCUMENT_ROOT'].'/database/db_credentials.php';

/**
 * Check if a user is logged in or not - check_login()
 *
 * If a user is logged in, function will return true else it will redirect
 *  the user to /login/ (login page).
 *
 * @return bool
 */
function check_login()
{
    $is_logged_in = isset($_SESSION['userData']['email']);
    if (!$is_logged_in) {
        header("Location: /login/");
        die();
    }

    $is_enabled = $_SESSION['userData']['enabled'];
    if ($is_enabled == "0") {
        header("Location: /login/index.php?status=403");
        die();
    }

    return true;
}

/**
 * Check if the user is admin - check_admin()
 *
 * Function returns true if the user is admin. If the user is not admin, he will
 * be redirected to challenge listing page.
 *
 * @return bool
 */
function check_admin()
{
    check_login();
    if ($_SESSION['userData']['is_admin'] != 1) {
        header("Location: /challenges/");
        die();
    }
    return true;
}

/**
 * Greeting the user with a welcome message - welcome_message()
 *
 * Function will printout the first name and last name of a logged in user
 *
 * TODO: Prevent XSS
 *
 * @return void
 */
function welcome_message()
{
    echo $_SESSION['userData']['first_name']." ".$_SESSION['userData']['last_name'];
}

/**
 * Get the list of categories - get_categories()
 *
 * Returns a list of challenge categories.
 *
 * @return bool|mysqli_result
 */
function get_categories()
{
    global $conn;
    $query = "SELECT distinct id,name from categories";
    $result = mysqli_query($conn, $query);
    return $result;
}

/**
 * Get the list of challenges - get_challenges()
 *
 * Returns a list of challenges based on the category.
 *
 * @return bool|mysqli_result
 */
function get_challenges()
{
    global $conn;
    $email_id = $_SESSION['userData']['email'];
    $query = "SELECT * from challenges where (type in (SELECT distinct name from categories) and enabled = 1 or id in (select distinct level_id from user_level_enabled_challenges where email_id = '$email_id'))";
    $result = mysqli_query($conn, $query);
    return $result;
}

/**
 * Get the list of difficulties - get_difficulties()
 *
 * We have 3 defined difficuties namely easy, medium and hard
 *
 * @return bool|mysqli_result
 */
function get_difficulties()
{
    global $conn;
    $query = "SELECT distinct name from difficulties";
    $result = mysqli_query($conn, $query);
    return $result;
}

/**
 * Get the list of languages - get_languages()
 *
 * As of now, only PHP is supported but more languages will be added
 * soon.
 *
 * @return bool|mysqli_result
 */
function get_languages()
{
    global $conn;
    $query = "SELECT distinct name from languages";
    $result = mysqli_query($conn, $query);
    return $result;
}

/**
 * Get the list of FAQ's - get_faq()
 *
 * Admins can see all the FAQ's regardless of if its enabled or not. Users
 * can only see approved FAQ's from the admin.
 *
 * @return bool|mysqli_result
 */
function get_faqs(){
    global $conn;
    $query = "SELECT * from faqs";

    if (!(isset($_SESSION['userData']['is_admin'])) || $_SESSION['userData']['is_admin'] != 1)
        $query .= " where enabled=1";

    $result = mysqli_query($conn, $query);
    return $result;
}

/**
 * Get the istructions from FAQ's - get_instructions().
 *
 * TODO: Remove this function
 *
 * @return bool|mysqli_result
 */
function get_instructions()
{
    global $conn;
    $query = "SELECT instructions from faqs";
    $result = mysqli_query($conn, $query);
    return $result;
}

/**
 * Get the details of a challenge - get_challenge()
 *
 * Returns the details of one particular challenge based on the ID
 * provided.
 *
 * @return bool|mysqli_result
 */
function get_challenge($id)
{
    global $conn;
    $id = mysqli_real_escape_string($conn, $id);
    $query = "SELECT * from challenges where id = '" . $id . "'";
    $result = mysqli_query($conn, $query);
    $rows = mysqli_fetch_array($result);
    return $rows;
}

/**
 * Add a new challenge into the DB - add_challenge()
 *
 * Admin's has the functionality to add a new challenge into the DB which is
 * auto approved and is populated in the challenges page when user visits it.
 *
 * The main code block and unittests are being saved as base64 inside the db
 * so as to maintain the code style.
 *
 * TODO: Validate difficulty/type/language before inserting to DB
 *
 * @param array $post  The whole $POST data is passed as the function arg.
 * @param array $files The uploaded Files are bring passed as function arg.
 *
 * @return array
 */
function add_challenge($post, $files)
{
    global $conn;
    $name = $post['name'];
    $code = base64_encode(file_get_contents($files['code']['tmp_name']));
    $unittests = base64_encode(file_get_contents($files['unittests']['tmp_name']));
    $intro = $post['intro'];
    $instructions = $post['instructions'];
    $hints = $post['hints'];
    $references = $post['references'];
    $approved = 1;
    $enabled = 1;
    $difficulty = strtolower($post['difficulty']);
    $type = strtolower($post['type']);
    $language = strtolower($post['language']);

    if ($difficulty == 'easy') {
        $points = 10;
    } else if ($difficulty == 'medium') {
        $points = 20;
    } else if ($difficulty == 'hard') {
        $points = 30;
    } else {
        return array(true, "Invalid difficulty");
    }


    $prevQuery = "INSERT INTO challenges(name,code,intro,instructions,reference,approved,enabled,points,difficulty,type,language,hints) values(?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($prevQuery);

    $stmt->bind_param("sssssdddssss",$name,$code,$intro,$instructions,$references,$approved,$enabled,$points,$difficulty,$type,$language,$hints);
    $stmt->execute();
    $addchallengeresult = mysqli_stmt_affected_rows($stmt);

    if ($addchallengeresult) {
        $challenge_id = get_challenge_id($name,$code,$intro,$instructions,$references,$difficulty,$type,$language);
        if ($challenge_id) {
            $prevQuery = "INSERT INTO unittests(unittest,challenge_id) values(?,?)";
            $stmt = $conn->prepare($prevQuery);
            $stmt->bind_param("sd", $unittests, $challenge_id);
            $stmt->execute();
            $unittestresult = mysqli_stmt_affected_rows($stmt);

            if (!$unittestresult) {
                return array(false, "Adding unittest failed !");
            } else {
                return array(false, "Challenge Added Successfully");
            }
        } else {
            return array(true, "Challenge Failed to Add. Check data Once.");
        }
    } else {
        return array(true, "Challenge Failed to Add. Check data Once.");
    }
}


/**
 * Update existing challenges - update_challenge()
 *
 * Admins can update the existing challenges and all its parameters including
 * instructions, code, unittests, hints and references.
 *
 * @param array $data  Entire POST body is passed as function argument
 * @param array $files The uploaded Files are bring passed as function arg.
 *
 * @return array
 */
function update_challenge($data, $files){
    global $conn;

    $id = $data['id'];
    $name = $data['name'];
    
    if (isset($files['code']) && $files['code']['tmp_name'] != "") {
        $code = base64_encode(file_get_contents($files['code']['tmp_name']));
    } else {
        $code = base64_encode($data['disabled_code']);
    }

    if (isset($files['unittests']) && $files['unittests']['tmp_name'] != "") {
        $unittests = base64_encode(file_get_contents($files['unittests']['tmp_name']));
    } else {
        $unittests = base64_encode($data['disabled_unittests']);
    }

    $intro = $data['intro'];
    $instructions = $data['instructions'];
    $hints = $data['hints'];
    $references = $data['references'];
    $difficulty = strtolower($data['difficulty']);
    $type = strtolower($data['type']);
    $language = strtolower($data['language']);

    if ($difficulty == 'easy') {
        $points = 10;
    } else if ($difficulty == 'medium') {
        $points = 20;
    } else if ($difficulty == 'hard') {
        $points = 30;
    } else {
        return array(true, "Invalid difficulty");
    }

    $prevQuery = "UPDATE challenges set name=?,code=?,intro=?,instructions=?,reference=?,points=?,difficulty=?,type=?,language=?,hints=? where id=?";
    $stmt = $conn->prepare($prevQuery);

    $stmt->bind_param("sssssdsssss",$name,$code,$intro,$instructions,$references,$points,$difficulty,$type,$language,$hints,$id);
    $stmt->execute();
    $prevResult = mysqli_stmt_affected_rows($stmt);

    if ($prevResult) {
        $challenge_id = get_challenge_id($name, $code, $intro, $instructions, $references, $difficulty, $type, $language);
        if ($challenge_id) {
            $prevQuery = "UPDATE unittests set unittest=? where id=?";
            $stmt = $conn->prepare($prevQuery);
            $stmt->bind_param("sd", $unittests, $challenge_id);
            $stmt->execute();
        }
    }

    if ($prevResult) {
        return array(false, "Challenge with title '" . $data['name'] . "' Updated Successfully");
    } else {
        return array(true, "Challenge with title '" . $data['name'] . "' Failed to Update. Check data Once.");
    }
}

/**
 * Get the list of all challenges - get_all_challenges()
 *
 * Returns the list of all the challenges !
 *
 * @return bool|mysqli_result
 */
function get_all_challenges()
{
    global $conn;
    $query = "SELECT * from challenges";
    $result = mysqli_query($conn, $query);
    return $result;
}

/**
 * Get all the user data - get_all_users_data()
 *
 * Returns the list of all the users and their data
 *
 * @return string
 */
function get_all_users_data()
{
    global $conn;
    $query = "SELECT * from users";
    $all_users = mysqli_query($conn, $query);

    $users = [];
    foreach ($all_users as $user) {
        $user['is_admin'] = (int)$user['is_admin'];
        $user['enabled'] = (int)$user['enabled'];
        $user['id'] = (int)$user['id'];
        array_push($users, $user);
    }
    return json_encode($users);
}

/**
 * Get all the FAQ - get_all_faq_data()
 *
 * Returns the list of all the FAQ's.
 *
 * @return string
 */
function get_all_faq_data()
{
    global $conn;
    $query = "SELECT * from faqs";
    $all_faqs = mysqli_query($conn, $query);

    $faqs = [];
    foreach ($all_faqs as $faq) {
        $faq['enabled'] = (int)$faq['enabled'];
        $faq['id'] = (int)$faq['id'];
        array_push($faqs, $faq);
    }
    return json_encode($faqs);
}

/**
 * Get all the HINTS - get_all_hint_data()
 *
 * Returns the list of all the HINTs.
 *
 * @return string
 */
function get_all_hint_data()
{
    global $conn;
    $query = "SELECT * from hints";
    $all_hints = mysqli_query($conn, $query);
    $hints = [];
    foreach ($all_hints as $hint) {
        $hint['enabled'] = (int)$hint['enabled'];
        $hint['id'] = (int)$hint['id'];
        array_push($hints, $hint);
    }
    return json_encode($hints);
}

/**
 * Get all the challenges data - get_all_challenges_data()
 *
 * Returns the list of all the challenge's data.
 *
 * @return string
 */
function get_all_challenges_data()
{
    $all_challenges = get_all_challenges();
    $challs = [];
    foreach ($all_challenges as $challenge) {
        array_push($challs, $challenge);
    }
    return json_encode($challs);
}

/**
 * Get all the challenge details from the db - get_all_challenges_to_approve_data()
 *
 * Returns the list of all the challenges and its detail.
 *
 * @return string
 */
function get_all_challenges_to_approve_data()
{
    global $conn;
    $query = "SELECT * from challenges where approved != 1";
    $all_challenges = mysqli_query($conn, $query);
    $challs = [];
    foreach ($all_challenges as $challenge) {
        array_push($challs, $challenge);
    }
    return json_encode($challs);
}

/**
 * Show all the challenges to the user - show_all_challenges()
 *
 * @return string
 */
function show_all_challenges(){
    $all_challenges = get_all_challenges();
    $challs = [];
    foreach ($all_challenges as $challenge) {
        array_push($challs, json_encode($challenge));
    }
    ?>
    <script src="/staticfiles/js/view_edit.js"></script>
    <?php
    // echo ;

}

/**
 * Show all the challenges which is not approved - show_all_challenges_to_approve()
 *
 * Returns the list of all the FAQ's.
 *
 * @return string
 */
function show_all_challenges_to_approve(){
    ?>
    <script src='/staticfiles/js/approve_reject.js'></script>
    <?php
    // echo ;

}

/**
 * Show the challenges to the user - show_challenge()
 *
 * List all the challenges to the user
 *
 * @return string
 */
function show_challenge($id, $challenge){
    ?>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="collapse navbar-collapse add_new" id="bs-example-navbar-collapse-1">

                <!-- Default form contact -->
                <form method="POST" action="view_edit.php" enctype="multipart/form-data">
                    <!-- Default input name -->
                    <label for="name" class="grey-text">Challenge Name*</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo $challenge['name'] ?>">

                    <br>

                    <!-- Default dropdown language -->
                    <label for="language" class="grey-text">Challenge Language</label>
                    <select class="form-control" id="language" name="language">
                        <?php
                        $languages = get_languages();
                        foreach($languages as $language)
                        {
                            $language = $language['name'];
                            $option = '<option value="'.$language.'"';
                            if(strtoupper($language) == strtoupper($challenge['language'])){
                                $option = $option. ' selected';
                            }

                            $option = $option. '>'.strtoupper($language).'</option>';
                            echo $option;
                        }
                        ?>
                    </select>

                    <br>

                    <!-- Default dropdown type -->
                    <label for="difficulty" class="grey-text">Challenge Type</label>
                    <select class="form-control" id="type" name="type">
                        <?php
                        $categories = get_categories();
                        foreach($categories as $category)
                        {
                            $category = $category['name'];
                            $option = '<option value="'.$category.'"';
                            if(strtoupper($category) == strtoupper($challenge['type'])){
                                $option = $option. ' selected';
                            }

                            $option = $option. '>'.strtoupper($category).'</option>';
                            // echo "<script>alert('".$option."');</script>";
                            echo $option;
                        }
                        ?>
                    </select>

                    <br>

                    <!-- Default dropdown difficulty -->
                    <label for="difficulty" class="grey-text">Challenge Difficulty</label>
                    <select class="form-control" id="difficulty" name="difficulty">
                    <?php
                        $difficulties = get_difficulties();
                        foreach($difficulties as $difficulty)
                        {
                            $difficulty = $difficulty['name'];
                            $option = '<option value="'.$difficulty.'"';
                            if(strtoupper($difficulty) == strtoupper($challenge['difficulty'])){
                                $option = $option. ' selected';
                            }

                            $option = $option. '>'.strtoupper($difficulty).'</option>';
                            echo $option;
                        }
                    ?>
                    </select>

                    <br>
                    <!-- Default textarea code -->
                    <label for="code" class="grey-text">Upload Challenge Code*</label>
                    <textarea type="text" id="disabled_code" name="disabled_code" readonly class="form-control" rows="4"><?php
                        echo base64_decode($challenge['code']);
                    ?></textarea>
                    <input type="file" class="form-control-file" id="code" name="code">
                    <br>
                    <!-- Default textarea code -->
                    <label for="unittests" class="grey-text">Upload Unit Tests*</label>
                    <textarea type="text" id="disabled_unittests" name="disabled_unittests" readonly class="form-control" rows="4"><?php
                        echo base64_decode($challenge['code']);
                    ?></textarea>
                    <input type="file" class="form-control-file" id="unittests" name="unittests">

                    <br>
                    <!-- Default textarea introduction -->
                    <label for="intro" class="grey-text">Challenge Introduction*</label>
                    <textarea type="text" id="intro" name="intro" class="form-control" rows="4"><?php
                        echo htmlspecialchars($challenge['intro']);
                    ?></textarea>

                    <br>

                    <!-- Default textarea references -->
                    <label for="instructions" class="grey-text">Challenge Instructions</label>
                    <textarea type="text" id="instructions" name="instructions" class="form-control" rows="4"><?php
                        echo htmlspecialchars($challenge['instructions']);
                    ?></textarea>
                    <br>
                    <!-- Default textarea references -->
                    <label for="hints" class="grey-text">Challenge Hints</label>
                    <textarea type="text" id="hints" name="hints" class="form-control" rows="4"><?php
                        echo htmlspecialchars($challenge['hints']);
                    ?></textarea>
                    <br>
                    <!-- Default textarea references -->
                    <label for="references" class="grey-text">Challenge References</label>
                    <textarea type="text" id="references" name="references" class="form-control" rows="4"><?php
                        echo htmlspecialchars($challenge['reference']);
                    ?></textarea>

                    <br>
                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
                    <div class="text-center mt-4">
                        <br /><p><button class="btn btn-info" type="submit">Send<i class="fa fa-paper-plane-o ml-2"></i></button></p>
                    </div>
                </form>
                <!-- Default form contact -->
            </div>
        </div>
    </nav>
    </div>
    <?php
}


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

function admin_update_user($id,$action){
    global $conn;

    if($action == "make_admin")
        $action = 1;
    else
        $action = 0;

    var_dump($_SESSION);
    $prevQuery = "UPDATE users set is_admin=?, updated_by='".$_SESSION['userData']['email']."' where id=?";
    $stmt = $conn->prepare($prevQuery);

    $stmt->bind_param("dd",$action,$id);;
    $stmt->execute();
    $prevResult = mysqli_stmt_affected_rows($stmt);

    return array("is_admin"=>$action);
}

function enable_disable_faq($id,$action){
    global $conn;

    if($action == "enabled")
        $action = 1;
    else
        $action = 0;

    $prevQuery = "UPDATE faqs set enabled=? where id=?";
    $stmt = $conn->prepare($prevQuery);

    $stmt->bind_param("dd",$action,$id);
    $stmt->execute();
    $prevResult = mysqli_stmt_affected_rows($stmt);

    return array("enabled"=>$action);
}

function enable_disable_hints($id,$action){
    global $conn;

    if($action == "enabled")
        $action = 1;
    else
        $action = 0;

    $prevQuery = "UPDATE hints set enabled=? where id=?";
    $stmt = $conn->prepare($prevQuery);

    $stmt->bind_param("dd",$action,$id);
    $stmt->execute();
    $prevResult = mysqli_stmt_affected_rows($stmt);

    return array("enabled"=>$action);
}


function enable_disable_user($id,$action){
    global $conn;

    if($action == "enabled")
        $action = 1;
    else
        $action = 0;

    $prevQuery = "UPDATE users set enabled=? where id=?";
    $stmt = $conn->prepare($prevQuery);

    $stmt->bind_param("dd",$action,$id);
    $stmt->execute();
    $prevResult = mysqli_stmt_affected_rows($stmt);

    return array("enabled"=>$action);
}

function approve_reject_challenge($id,$action){
    global $conn;

    if($action == "approved")
        $action = 1;
    else
        $action = 0;
    $prevQuery = "UPDATE challenges set approved=? where id=?";
    $stmt = $conn->prepare($prevQuery);

    $stmt->bind_param("dd",$action,$id);;
    $stmt->execute();
    $prevResult = mysqli_stmt_affected_rows($stmt);

    return array("approved"=>$action);
}

function enable_disable_challenge($id,$action){
    global $conn;

    if($action == "enabled")
        $action = 1;
    else
        $action = 0;

    $prevQuery = "UPDATE challenges set enabled=? where id=?";
    $stmt = $conn->prepare($prevQuery);

    $stmt->bind_param("dd",$action,$id);
    $stmt->execute();
    $prevResult = mysqli_stmt_affected_rows($stmt);

    // var_dump($conn);
    return array("enabled"=>$action);
}

function chall_per_type(){
    global $conn;
    $prevQuery = "SELECT type as name, count(*) as y from challenges where (enabled = 1 and approved = 1) group by type";
    $result = mysqli_query($conn, $prevQuery);
    return $result;
}

function chall_per_lang(){
    global $conn;
    $prevQuery = "SELECT language as name, count(*) as y from challenges where (enabled = 1 and approved = 1) group by language";
    $result = mysqli_query($conn, $prevQuery);
    return $result;
}

function chall_per_difficulty(){
    global $conn;
    $prevQuery = "SELECT difficulty as name, count(*) as y from challenges where (enabled = 1 and approved = 1) group by difficulty";
    $result = mysqli_query($conn, $prevQuery);
    return $result;
}


function get_challenge_id($name,$code,$intro,$instructions,$references,$difficulty,$type,$language){
    global $conn;
    $prevQuery = "SELECT id from challenges where name=? AND code=? AND intro=? AND instructions=? AND reference=? AND difficulty=? AND type=? AND language=? limit 1";
    $stmt = $conn->prepare($prevQuery);
    $stmt->bind_param("ssssssss",$name,$code,$intro,$instructions,$references,$difficulty,$type,$language);
    $stmt->execute();
    $stmt->bind_result($id);
    $stmt->fetch();

    return $id;
}

function get_admin_email(){
    global $conn;
    $query = "select email from users limit 1";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $stmt->bind_result($email);
    $stmt->fetch();
    return $email;
}

function get_challenge_language($id) {
    global $conn;
    $query = "select language from challenges where id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("d", $id);
    $stmt->execute();
    $stmt->bind_result($type);
    $stmt->fetch();

    return $type;
}


function uuid_v4() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
  }

function get_challenge_code($email_id,$level_id) {
    global $conn;

    $chall_codes = get_challenge_flag($email_id,$level_id);

    foreach ($chall_codes as $chall_code) {
        return $chall_code['chall_code'];
    }

    $chall_code = uuid_v4();

    $prevQuery = "INSERT INTO user_level_enabled_challenges(chall_code,level_id,email_id,enabled) values(?,?,?,1)";
    $stmt = $conn->prepare($prevQuery);

    $stmt->bind_param("sss",$chall_code,$level_id,$email_id);
    $stmt->execute();

    if(mysqli_stmt_affected_rows($stmt))
        return $chall_code;
    else
        return null;

}

function get_challenge_flag($email_id,$level_id) {;

    global $conn;
    $prevQuery = "SELECT chall_code from user_level_enabled_challenges where email_id = '$email_id' and level_id = $level_id limit 1";
    $results = mysqli_query($conn, $prevQuery);

    return $results;

}

function check_enabled_level($level_id) {
    global $conn;

    $prevQuery = "(SELECT c.enabled from challenges c where (c.id = $level_id and c.enabled = 1)) union (SELECT b.enabled from user_level_enabled_challenges b where (b.level_id = $level_id and b.email_id = '".$_SESSION['userData']['email']."' and b.enabled = 1))";
    $results = mysqli_query($conn, $prevQuery);

    foreach ($results as $row) {
        return True;
    }

    die(header('Location: /challenges/index.php?error=unauthorised'));
}

function get_dev_token(){
    $config = parse_ini_file('/var/config/.kurukshetra.ini');
    $token = $config['token'];

    return $token;

}

function update_user_challenge_status($chall_id){
    global $conn;

    $user_email = $_SESSION['userData']['email'];

    if(check_user_challenge_status($chall_id)){
        return null;
    }

    $prevQuery = "INSERT INTO user_challenges(user_email, chall_id) values(?,?)";
    $stmt = $conn->prepare($prevQuery);

    $stmt->bind_param("ss",$user_email,$chall_id);
    $stmt->execute();

    if(mysqli_stmt_affected_rows($stmt))
        return True;
    else
        return False;
}

function check_user_challenge_status($chall_id){
    global $conn;

    $user_email = $_SESSION['userData']['email'];

    $query = "SELECT count(*) as count from user_challenges where chall_id=? and user_email=?";
    $stmt = $conn->prepare($query);

    $stmt->bind_param("ds",$chall_id,$user_email);
    $stmt->execute();

    $stmt->bind_result($count);
    $stmt->fetch();

    if($count > 0)
        return True;

    return False;
}

?>