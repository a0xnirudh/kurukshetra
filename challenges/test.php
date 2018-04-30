<?php

require __DIR__ . '/../database/db_credentials.php';

/**
 * Class HTTP - HTTP is a class where functions are defined which can
 * initiate GET/POST requests along with data.
 *
 * @category PHP
 * @package  Kurukshetra
 * @author   Anirudh Anand <a0xnirudh@gmail.com>
 * @license  Apache 2.0
 */
class Http
{
    /**
     * Initiate GET Request - httpGet()
     *
     * Function httpGet() will initiate a GET request to the parameter $url.
     *
     * @param string $url URL to which the GET request is initiated
     *
     * @return string $output Response of the GET request
     */
    public function httpGet($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output=curl_exec($ch);

        curl_close($ch);
        return $output;
    }


    /**
     * Initiate POST Request - httpPost()
     *
     * Function httpPost() will initiate a POST request to the parameter $url.
     *
     * @param string $url    URL to which the POST request is initiated
     * @param string $params Parameters to be included in the POST request
     *
     * @return string $output Response of the POST request
     */
    public function httpPost($url, $params=null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,  CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        $output=curl_exec($ch);

        curl_close($ch);
        return $output;
    }


    /**
     * Initiate DELETE Request - httpPost()
     *
     * Function httpDelete() will initiate a DELETE request to the parameter $url.
     *
     * @param string $url URL to which the POST request is initiated
     *
     * @return string $output Response of the DELETE request
     */
    public function httpDelete($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

        $output = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        return $output;
    }
}


/**
 * Class UnitTest Handling Unit tests
 *
 * This class handles validatation of the user submitted challenge code
 * by using unittests. Once the user submits the code, the unittests are
 * run inside the docker containers and resulting output is shown to the
 * users.
 *
 * @category PHP
 * @package  Kurukshetra
 * @author   Anirudh Anand <a0xnirudh@gmail.com>
 * @license  Apache 2.0
 */
class UnitTest extends Http
{
    public $folder = "";
    public $unittest = "";

    /**
     * Connecting to the MySQL - mysqlConnect()
     *
     * Function will connect to the MySQL to load the unittests
     * corresponding to the challenges.
     *
     * @var object $conn MySQL connection object
     * @var string $query SQL query to be executed
     *
     * @return void
     */
    public function mysqlConnect()
    {
        global $conn;

        $query = "SELECT unittest from unittests where challenge_id=? LIMIT 1";
        $stmt = $conn->prepare($query);

        $stmt->bind_param("d", $_POST['id']);
        $stmt->execute();
        $stmt->bind_result($this->unittest);
        $stmt->fetch();

        $fh = fopen("uploads/" . $this->folder . "/unittest.php", 'w+');
        fwrite($fh, base64_decode($this->unittest));
        fclose($fh);
    }

    /**
     * Preparing to run unittests - prepare()
     *
     * Function will write the user submitted code into a file which is later
     * mounted on the docker container along with the unittests.
     *
     * @var object $fh File object used to write the code into a file
     *
     * @return void
     */
    public function prepare()
    {
        $this->folder = sha1(random_bytes(10));

        if (isset($_POST['function'])) {
            mkdir(__DIR__ . "/uploads/" . $this->folder);
            $fh = fopen(__DIR__ . "/uploads/" . $this->folder . "/src.php", 'w+');

            $stringData = base64_decode(str_replace(" ", "+", $_POST['function']));
            fwrite($fh, $stringData);
            fclose($fh);

        }
    }

    /**
     * Cleaning up after running - cleanup()
     *
     * Once the Docker process is done, cleanup
     *
     * @return void
     */
    public function cleanup()
    {
        $directory = __DIR__ .'/uploads/' . $this->folder;
        array_map('unlink', glob("$directory/*.*"));
        rmdir($directory);
        return;
    }
}


/**
 * Class Docker - Handles the Docker and its API
 *
 * Enabling Docker API is a must for this to work. The class deals
 * with running unittests for the user submitted code inside the Docker
 * container and return back its results.
 *
 * @category PHP
 * @package  Kurukshetra
 * @author   Anirudh Anand <a0xnirudh@gmail.com>
 * @license  Apache 2.0
 */
class Docker extends UnitTest
{
    public $url = "http://127.0.0.1:2376";
    public $container_id = "";

    /**
     * Function createContainer() will create a new container using which
     * we can run our unittests. The uploads directory is mounted to the docker
     * as *read only* and unittests are run inside it.
     *
     * @var array $res    Response of the container creation request
     * @var array $params Parameters required for creating a container
     *
     * @return void
     */
    public function createContainer()
    {
        $params = (object) [
            "Cmd" => ["tail", "-f", "/dev/null"],
            "Image" => "kurukshetra",
            "HostConfig" => ["Binds" => [__DIR__ . "/uploads/" . $this->folder . ":/var/www/html:ro"]]
        ];

        $res = json_decode($this->httpPost($this->url . "/containers/create", json_encode($params)), true);
        $this->container_id = $res["Id"];

        // Starting the container
        $res = $this->httpPost($this->url . "/containers/" . $this->container_id . "/start");
        return;
    }


    /**
     * Function execContainer will actually run the unittest.php against the
     * user submitted code and returns the results.
     *
     * @var array $params Parameters required for executing the commands
     * @var string $output Output of the commmand which ran inside the container
     *
     * @return void
     */
    public function execContainer()
    {
        $params = (object) [
            "AttachStdin" => false,
            "AttachStdout" => true,
            "AttachStderr" => true,
            "Tty" => false,
            "Cmd" => ["phpunit", "/var/www/html/unittest.php"]
        ];
        $exec = json_decode($this->httpPost($this->url . "/containers/" . $this->container_id . "/exec", json_encode($params)), true);

        // Running the exec
        $params = (object) [
            "Detach" => false,
            "Tty" => false
        ];
        $output = $this->httpPost($this->url . "/exec/" . $exec["Id"] . "/start", json_encode($params));
        $temp = explode("\n", $output);
        $output = "";
        $count = 0;

        foreach ($temp as $out) {
            if (stripos($out, 'INFO:') !== false) {
                $count++;
                $output .= $count . ". " . explode("INFO: ", $out)[1] . "\n";
            }
        }

        echo "Output: \n\n" . $output;
        return;

    }

    /**
     * The function will remove the container once its usage is complete.
     *
     * @return void
     */
    public function removeContainer()
    {
        // Killing the container
        echo $this->httpPost($this->url . "/containers/" . $this->container_id . "/kill");

        // Removing the container
        echo $this->httpDelete($this->url . "/containers/" . $this->container_id . "?force=1");

    }

}

$docker = New Docker();
$docker -> prepare();
$docker -> mysqlConnect();
$docker -> createContainer();
$docker -> execContainer();
#$docker -> removeContainer();
#$docker -> cleanup();
