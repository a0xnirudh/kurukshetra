<?php
include($_SERVER['DOCUMENT_ROOT'].'/database/db_credentials.php');
class User {
    private $dbHost;
    private $dbUsername;
    private $dbPassword;
    private $dbName;
    private $userTbl = 'users';
    
    function __construct(){
        global $conn;
        $this->db = $conn;
    }
    
    function checkUser($userData = array()){
        if(!empty($userData)){
            //Check whether user data already exists in database
            $prevQuery = "SELECT * FROM ".$this->userTbl." WHERE oauth_provider = '".$userData['oauth_provider']."' AND oauth_uid = '".$userData['oauth_uid']."'";
            $prevResult = $this->db->query($prevQuery);
            if($prevResult->num_rows > 0){
                //Update user data if already exists
                $query = "UPDATE ".$this->userTbl." SET first_name = '".$userData['first_name']."', last_name = '".$userData['last_name']."', email = '".$userData['email']."', gender = '".$userData['gender']."', locale = '".$userData['locale']."', picture = '".$userData['picture']."', link = '".$userData['link']."', modified = '".date("Y-m-d H:i:s")."' WHERE oauth_provider = '".$userData['oauth_provider']."' AND oauth_uid = '".$userData['oauth_uid']."'";
                $update = $this->db->query($query);
            }else{
                //Insert user data
                $query = "INSERT INTO ".$this->userTbl." SET oauth_provider = '".$userData['oauth_provider']."', oauth_uid = '".$userData['oauth_uid']."', first_name = '".$userData['first_name']."', last_name = '".$userData['last_name']."', email = '".$userData['email']."', gender = '".$userData['gender']."', locale = '".$userData['locale']."', picture = '".$userData['picture']."', link = '".$userData['link']."', created = '".date("Y-m-d H:i:s")."', modified = '".date("Y-m-d H:i:s")."'";
                $insert = $this->db->query($query);
            }
            
            //Get user data from the database
            $result = $this->db->query($prevQuery);
            $userData = $result->fetch_assoc();
        }
        
        //Return user data
        return $userData;
    }

    function checkAdmin($userData = array()){
        $prevQuery = "SELECT is_admin FROM ".$this->userTbl." WHERE oauth_provider = '".$userData['oauth_provider']."' AND oauth_uid = '".$userData['oauth_uid']."' AND is_admin=1 LIMIT 1";
        $prevResult = $this->db->query($prevQuery);
        $_SESSION['userData']['is_admin'] = $prevResult->num_rows;
        
        return $_SESSION['userData']['is_admin'];
    }

    function checkUserCount(){
        $prevQuery = "SELECT * FROM ".$this->userTbl;
        $prevResult = $this->db->query($prevQuery);
        
        return $prevResult->num_rows;
    }

    function setAdmin($userData = array()){
        $query = "UPDATE users SET is_admin = 1 WHERE oauth_provider = '".$userData['oauth_provider']."' AND oauth_uid = '".$userData['oauth_uid']."'";
        $update = $this->db->query($query);
        return true;
    }

}
?>