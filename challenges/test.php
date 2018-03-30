<?php

require __DIR__ . '/../database/db-credentials.php';

class Http {

    public function httpGet($url) {
        $ch = curl_init();  
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
     
        $output=curl_exec($ch);
        # echo "\nStatus: " . curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        return $output;
    }


    public function httpPost($url, $params=Null) {
        $ch = curl_init();  
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));    
     
        $output=curl_exec($ch);
        # echo "\nStatus: " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
     
        curl_close($ch);
        return $output;
    }


    public function httpDelete($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        
        $output = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        return $output;
    }
}


class UnitTest extends Http {
    public $folder = "";
    public $unittest = "";

    function __construct() {
        $this->folder = md5(uniqid(rand(), true));
    } 

    public function mysqlConnect() {
        global $host, $db_name, $db_user, $db_pass;

        $conn = new mysqli($host, $db_user, $db_pass, $db_name);
        if($conn->connect_error) {
            die("Failed to connect with MySQL");
        } else {
            $query = "SELECT unittest from unittests where challenge_id=";
            $query .= mysqli_real_escape_string($conn, $_POST['id']);
            $result = $conn->query($query);
            if($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $this->unittest = $row['unittest'];
                $fh = fopen("uploads/" . $this->folder . "/unittest.php", 'w+');
                fwrite($fh, $this->unittest);
                fclose($fh);
            }
        }
    }

    public function prepare() {
        if(isset($_POST['function'])) {
            mkdir(__DIR__ . "/uploads/" . $this->folder);
            $fh = fopen(__DIR__ . "/uploads/" . $this->folder . "/src.php", 'w+');
            $stringData = "<?php
            class Src {
            ";
            fwrite($fh, $stringData);

            $stringData = $_POST['function'];
            fwrite($fh, $stringData);

            $stringData = "\n }";
            fwrite($fh, $stringData);
            fclose($fh);

        }
    }
}


class Docker extends UnitTest {

    public $url = "http://127.0.0.1:2375";
    # public $current_path = realpath(dirname(__FILE__));
    public $container_id = "";
    
    public function createContainer() {
        $params = (object) [
           "Cmd" => ["sleep", "15"],
           "Image" => "hackademic",
           "HostConfig" => ["Binds" => [__DIR__ . "/uploads/" . $this->folder . ":/var/www/html:ro"]] 
        ];

        $res = json_decode($this->httpPost($this->url . "/containers/create", json_encode($params)), true);
        $this->container_id = $res["Id"];

        # Starting the container
        $res = $this->httpPost($this->url . "/containers/" . $this->container_id . "/start");
        return;
    }


    public function execContainer() {
        $params = (object) [
           "AttachStdin" => false,
           "AttachStdout" => true,
           "AttachStderr" => true,
           "Tty" => false,
           "Cmd" => ["phpunit", "/var/www/html/unittest.php"]
        ];
        $exec = json_decode($this->httpPost($this->url . "/containers/" . $this->container_id . "/exec", json_encode($params)), true);

        # Running the exec
        $params = (object) [
            "Detach" => false,
            "Tty" => false
        ];
        $output = $this->httpPost($this->url . "/exec/" . $exec["Id"] . "/start", json_encode($params));
        echo "Output: \n\n" . $output;
        return;

    }

    public function removeContainer(){
        # Killing the container
        echo $this->httpPost($this->url . "/containers/" . $this->container_id . "/kill");

        # Removing the container
        echo $this->httpDelete($this->url . "/containers/" . $this->container_id . "?force=1");

    }

}

$docker = New Docker();
$docker -> prepare();
$docker -> mysqlConnect();
$docker -> createContainer();
$docker -> execContainer();
$docker -> removeContainer();

