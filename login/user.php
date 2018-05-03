<?php

require $_SERVER['DOCUMENT_ROOT'].'/database/db_credentials.php';

/**
 * Class User
 *
 * Class which handles the user signup/signin. After installation, the first signed
 * up user in automatically made admin.
 *
 * @category PHP
 * @package  Kurukshetra
 * @author   Anirudh Anand <a0xnirudh@gmail.com>
 * @license  Apache 2.0
 */
class User
{
    private $userTbl = 'users';

    /**
     * User constructor.
     *
     * @var object $conn MySQL connection object
     */
    function __construct()
    {
        global $conn;
        $this->db = $conn;
    }

    /**
     * Check users in DB - checkUser()
     *
     * Functions checks to see if the user already exists in the db. If so
     * it updates the already existing data else it will insert a new data
     *
     * @param array $userData Holds the entire user data
     *
     * @return array
     */
    function checkUser($userData = array())
    {
        $userData = array_map('addslashes', $userData);
        if (!empty($userData)) {
            //Check whether user data already exists in database
            $prevQuery = "SELECT * FROM ".$this->userTbl." WHERE oauth_provider = '".$userData['oauth_provider']."' AND oauth_uid = '".$userData['oauth_uid']."'";
            $prevResult = $this->db->query($prevQuery);
            if ($prevResult->num_rows > 0) {
                //Update user data if already exists
                $query = "UPDATE ".$this->userTbl." SET first_name = '".$userData['first_name']."', last_name = '".$userData['last_name']."', email = '".$userData['email']."', gender = '".$userData['gender']."', locale = '".$userData['locale']."', picture = '".$userData['picture']."', link = '".$userData['link']."', modified = '".date("Y-m-d H:i:s")."' WHERE oauth_provider = '".$userData['oauth_provider']."' AND oauth_uid = '".$userData['oauth_uid']."'";
                $update = $this->db->query($query);
            } else {
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

    /**
     * Check if the user is admin - checkAdmin()
     *
     * Check if the logged in user has admin privileges or not.
     *
     * @param array $userData User information data
     *
     * @return mixed
     */
    function checkAdmin($userData = [])
    {
        $prevQuery = "SELECT is_admin FROM ".$this->userTbl." WHERE oauth_provider = '".$userData['oauth_provider']."' AND oauth_uid = '".$userData['oauth_uid']."' AND is_admin=1 LIMIT 1";
        $prevResult = $this->db->query($prevQuery);
        $_SESSION['userData']['is_admin'] = $prevResult->num_rows;
        
        return $_SESSION['userData']['is_admin'];
    }

    /**
     * Check the number of registered users - checkUserCount()
     *
     * Returns the total number of registered users
     *
     * @var string $prevQuery SQL query which is to be run
     *
     * @return int
     */
    function checkUserCount()
    {
        $prevQuery = "SELECT * FROM ".$this->userTbl;
        $prevResult = $this->db->query($prevQuery);
        
        return $prevResult->num_rows;
    }

    /**
     * Make a user admin - setAdmin()
     *
     * Already existing admins can give admin rights to the normal
     * users.
     *
     * @param array $userData User information data
     *
     * @return bool
     */
    function setAdmin($userData = array())
    {
        $query = "UPDATE users SET is_admin = 1 WHERE oauth_provider = '".$userData['oauth_provider']."' AND oauth_uid = '".$userData['oauth_uid']."'";
        $update = $this->db->query($query);
        return true;
    }

}