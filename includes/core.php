<?php
	session_start();
	require $_SERVER['DOCUMENT_ROOT'].'/database/db_credentials.php';

	//function to check logged in or not
	function check_login(){
		$is_logged_in = isset($_SESSION['userData']['email']);
		if(!$is_logged_in)
			die(header("Location: /login/"));

		$is_enabled = $_SESSION['userData']['enabled'];
		if($is_enabled == "0")
			// print('aaaaa');
			die(header("Location: /login/index.php?status=403"));

		return true;
	}

	function check_admin(){
		check_login();
		if($_SESSION['userData']['is_admin'] != 1)
			die(header("Location: /challenges/"));
		return True;
	}

	function welcome_message()
	{
		echo $_SESSION['userData']['first_name']." ".$_SESSION['userData']['last_name'];
	}

	function display_login(){
		include('../html/login_form.inc');
	}

	function get_categories(){
		global $conn;
		$query = "SELECT distinct id,name from categories;# where approved = 1 and enabled = 1";
		$result = mysqli_query($conn, $query);
		return $result;
		// print_r($result);
	}

	function get_challenges(){
		global $conn;
		$cats = get_categories();
		$query = "SELECT * from challenges where type in (SELECT distinct name from categories)";
		$result = mysqli_query($conn, $query);
		// var_dump($result);
		return $result;
	}

	function get_difficulties(){
		global $conn;
		$query = "SELECT distinct name from difficulties";
		$result = mysqli_query($conn, $query);
		return $result;
	}

	function get_languages(){
		global $conn;
		$query = "SELECT distinct name from languages";
		$result = mysqli_query($conn, $query);
		return $result;	
	}

	function get_faqs(){
		global $conn;
		$query = "SELECT * from faqs";

		if(!(isset($_SESSION['userData']['is_admin'])) || $_SESSION['userData']['is_admin'] != 1)
			$query .= " where enabled=1";
			
		$result = mysqli_query($conn, $query);
		return $result;	
	}

	function get_instructions(){
		global $conn;
		$query = "SELECT instructions from faqs";
		$result = mysqli_query($conn, $query);
		return $result;	
	}

	function get_challenge($id){
		# Get the challenge details from DB
		global $conn;
		$query = "SELECT * from challenges where id = '" . mysqli_real_escape_string($conn, $id) . "'";
		$result = mysqli_query($conn, $query);
		$rows = mysqli_fetch_array($result);
		return $rows;
	}

	function insert_challenge($data,$files){
		global $conn;

		$id = $data['id'];
	    $name = $data['name'];
	    $code = base64_encode(file_get_contents($files['code']['tmp_name']));
	    $unittests = base64_encode(file_get_contents($files['unittests']['tmp_name']));
	    $intro = $data['intro'];
	    $instructions = $data['instructions'];
	    $references = $data['references'];
	    $approved = 1;
	    $enabled = 1;
	    $difficulty = strtolower($data['difficulty']);
	    $type = strtolower($data['type']);
	    $language = strtolower($data['language']);

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
	    // print_r($prevResult);
	    if($prevResult)
	        return array(false,"Challenge with title '".htmlspecialchars($data['name'],ENT_QUOTES)."' Added Successfully");
	    else
	        return array(true,"Challenge with title '".htmlspecialchars($data['name'],ENT_QUOTES)."' Failed to Add. Check data Once.");
	}

	function update_challenge($data,$files){
		global $conn;

		$id = $data['id'];
	    $name = $data['name'];

	    if(isset($files['code']) && $files['code']['tmp_name'] != "") 
	    	$code = base64_encode(file_get_contents($files['code']['tmp_name']));
	    else
	    	$code = "";

	    if(isset($files['code']) && $files['code']['tmp_name'] != "") 
	    	$unittests = base64_encode(file_get_contents($files['unittests']['tmp_name'])) ;
	   	else
	   		$code = "";

	    $intro = $data['intro'];
	    $instructions = $data['instructions'];
	    $references = $data['references'];
	    $difficulty = strtolower($data['difficulty']);
	    $type = strtolower($data['type']);
	    $language = strtolower($data['language']);

	    if($difficulty == 'easy')
	        $points = 10;
	    if($difficulty == 'medium')
	        $points = 20;
	    if($difficulty == 'hard')
	        $points = 30;

	    $prevQuery = "UPDATE challenges set name=?,code=?,intro=?,instructions=?,reference=?,points=?,difficulty=?,type=?,language=? where id=?";
	    $stmt = $conn->prepare($prevQuery);

	    $stmt->bind_param("sssssdssss",$name,$code,$intro,$instructions,$references,$points,$difficulty,$type,$language,$id);
	    $stmt->execute();
	    $prevResult = mysqli_stmt_affected_rows($stmt);

	    if($prevResult)
	        return array(false,"Challenge with title '".htmlspecialchars($data['name'],ENT_QUOTES)."' Updated Successfully");
	    else
	        return array(true,"Challenge with title '".htmlspecialchars($data['name'],ENT_QUOTES)."' Failed to Update. Check data Once.");
	}

	function get_all_challenges(){
		# Get the challenge details from DB
		global $conn;
		$query = "SELECT * from challenges";
		$result = mysqli_query($conn, $query);
		return $result;
		// $rows = mysqli_fetch_array($result);
		// return $rows;
	}

	function get_all_users_data(){
		global $conn;
		$query = "SELECT * from users";
		$all_users = mysqli_query($conn, $query);
		
        $users = [];
	    foreach ($all_users as $user) {
	    	$user['is_admin'] = (int)$user['is_admin'];
	    	$user['enabled'] = (int)$user['enabled'];
	    	$user['id'] = (int)$user['id'];
	    	array_push($users,$user);
	    }
	    return json_encode($users);
	}

	function get_all_faq_data(){
		global $conn;
		$query = "SELECT * from faqs";
		$all_users = mysqli_query($conn, $query);
		
        $users = [];
	    foreach ($all_users as $user) {
	    	$user['enabled'] = (int)$user['enabled'];
	    	$user['id'] = (int)$user['id'];
	    	array_push($users,$user);
	    }
	    return json_encode($users);
	}


	function get_all_challenges_data(){
		$all_challenges = get_all_challenges();
        $challs = [];
	    foreach ($all_challenges as $challenge) {
	    	array_push($challs,$challenge);
	    }
	    return json_encode($challs);
	}

	function get_all_challenges_to_approve_data(){
		# Get the challenge details from DB
		global $conn;
		$query = "SELECT * from challenges where approved != 1";
		$all_challenges = mysqli_query($conn, $query);
        $challs = [];
	    foreach ($all_challenges as $challenge) {
	    	array_push($challs,$challenge);
	    }
	    return json_encode($challs);
	}

	function show_all_challenges(){
        $all_challenges = get_all_challenges();
        $challs = [];
	    foreach ($all_challenges as $challenge) {
	    	array_push($challs,json_encode($challenge));
	    }
	    ?>
	    <script src="/staticfiles/js/view_edit.js"></script>
	     <?php
	    // echo ;

	}

	function show_all_challenges_to_approve(){
        ?>
        <script src='/staticfiles/js/approve_reject.js'></script>
	     <?php
	    // echo ;

	}
	function show_challenge($id,$challenge){
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
	                // echo "<script>alert('".$option."');</script>";
	                echo $option;
	            }
	        ?>
	        </select>

	        <br>
	        <!-- Default textarea code -->
	        <label for="code" class="grey-text">Upload Challenge Code*</label>
	        <!-- <textarea type="text" id="code" name="code" class="form-control" rows="4"></textarea> -->
	        <input type="file" class="form-control-file" id="code" name="code">
	        

	        <br>
	        <!-- Default textarea code -->
	        <label for="unittests" class="grey-text">Upload Unit Tests*</label>
	        <!-- <textarea type="text" id="unittests" name="unittests" class="form-control" rows="4"></textarea> -->
	        <input type="file" class="form-control-file" id="unittests" name="unittests">

	        <br>
	        <!-- Default textarea introduction -->
	        <label for="intro" class="grey-text">Challenge Introduction*</label>
	        <textarea type="text" id="intro" name="intro" class="form-control" rows="4"><?php
	                echo $challenge['intro'];
	            ?>
	        </textarea>

	        <br>
	        
	        <!-- Default textarea references -->
	        <label for="instructions" class="grey-text">Challenge Instructions</label>
	        <textarea type="text" id="instructions" name="instructions" class="form-control" rows="4"><?php
	                echo $challenge['instructions'];
	            ?>
	        </textarea>

	        <br>


	        <!-- Default textarea references -->
	        <label for="references" class="grey-text">Challenge References</label>
	        <textarea type="text" id="references" name="references" class="form-control" rows="4"><?php
	                echo $challenge['reference'];
	            ?>
	        </textarea>

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

	function print_message($error,$msg){
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
		$prevQuery = "SELECT type as name, count(*) as y from challenges group by type";
		$result = mysqli_query($conn, $prevQuery);
		return $result;
	}

	function chall_per_lang(){
		global $conn;
		$prevQuery = "SELECT language as name, count(*) as y from challenges group by language";
		$result = mysqli_query($conn, $prevQuery);
		return $result;
	}

	function chall_per_difficulty(){
		global $conn;
		$prevQuery = "SELECT difficulty as name, count(*) as y from challenges group by difficulty";
		$result = mysqli_query($conn, $prevQuery);
		return $result;
	}

	function add_challenge($post,$files){
		global $conn;
	    $name = $post['name'];
	    $code = base64_encode(file_get_contents($files['code']['tmp_name']));
	    $unittests = base64_encode(file_get_contents($files['unittests']['tmp_name']));
	    $intro = $post['intro'];
	    $instructions = $post['instructions'];
	    $references = $post['references'];
	    $approved = 1;
	    $enabled = 1;
	    $difficulty = strtolower($post['difficulty']);
	    $type = strtolower($post['type']);
	    $language = strtolower($post['language']);

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
	    $addchallengeresult = mysqli_stmt_affected_rows($stmt);

	    if($addchallengeresult)
	    {
	    	$challenge_id = get_challenge_id($name,$code,$intro,$instructions,$references,$difficulty,$type,$language);
	        if($challenge_id){
		        $prevQuery = "INSERT INTO unittests(unittest,challenge_id) values(?,?)";
		        $stmt = $conn->prepare($prevQuery);
		        $stmt->bind_param("sd",$unittests,$challenge_id);
		        $stmt->execute();
		        $unittestresult = mysqli_stmt_affected_rows($stmt);
		        var_dump($unittestresult);

		        if(!$unittestresult)
		        	return array(false,"Challenge Added Successfully. But unit tests failed to update.");
		        else
		        	return array(false,"Challenge Added Successfully");
			}
			else
		        return array(true,"Challenge Failed to Add. Check data Once.");
	    }
	    else
	        return array(true,"Challenge Failed to Add. Check data Once.");
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

?>










	