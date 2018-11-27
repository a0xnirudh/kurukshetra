<?php

require $_SERVER['DOCUMENT_ROOT'] . '/includes/core.php';
require $_SERVER['DOCUMENT_ROOT'] . '/database/db_credentials.php';

if (!check_login()) { //not logged in?
    header('Location: /login/index.php'); //redirect to login page
}

$id = intval($_REQUEST['id']);
check_enabled_level($id);

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
 * @license  GPL v3.0
 */
class UnitTest
{
    public $folder = "";
    public $unittest = "";
    public $srccode = "";

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
        $stmt->close();

        $query2 = "SELECT code from challenges where id=? LIMIT 1";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bind_param("d", $_POST['id']);
        $stmt2->execute();
        $stmt2->bind_result($this->srccode);
        $stmt2->fetch();
        $stmt2->close();
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
        $language = get_challenge_language($_POST['id']);

        if (isset($_POST['function'])) {
            if($language == 'php') {

                // Write the user submitted code into a PHP file
                mkdir(__DIR__ . "/uploads/" . $this->folder);
                $fh = fopen(__DIR__ . "/uploads/" . $this->folder . "/src.php", 'w+');

                $stringData = base64_decode(str_replace(" ", "+", $_POST['function']));
                fwrite($fh, $stringData);
                fclose($fh);

                // Write the corresponding unittest into a PHP file
                $fh = fopen("uploads/" . $this->folder . "/unittests.php", 'w+');
                fwrite($fh, base64_decode($this->unittest));
                fclose($fh);
            }

            elseif ($language == 'python') {
                // Write the user submitted code into a python file
                mkdir(__DIR__ . "/uploads/" . $this->folder);
                $fh = fopen(__DIR__ . "/uploads/" . $this->folder . "/src.py", 'w+');

                $stringData = base64_decode(str_replace(" ", "+", $_POST['function']));
                fwrite($fh, $stringData);
                fclose($fh);

                // Write the corresponding unittest into a python file
                $fh = fopen("uploads/" . $this->folder . "/unittests.py", 'w+');
                fwrite($fh, base64_decode($this->unittest));
                fclose($fh);
            }

            elseif ($language == 'nodejs') {
                // Write the user submitted code into a python file
                mkdir(__DIR__ . "/uploads/" . $this->folder);
                $fh = fopen(__DIR__ . "/uploads/" . $this->folder . "/src.js", 'w+');

                $stringData = base64_decode(str_replace(" ", "+", $_POST['function']));
                fwrite($fh, $stringData);
                fclose($fh);

                // Write the corresponding unittest into a python file
                $fh = fopen("uploads/" . $this->folder . "/test.js", 'w+');
                fwrite($fh, base64_decode($this->unittest));
                fclose($fh);
            }

            elseif ($language == 'ruby') {
                // Write the user submitted code into a python file
                mkdir(__DIR__ . "/uploads/" . $this->folder);
                $fh = fopen(__DIR__ . "/uploads/" . $this->folder . "/src.rb", 'w+');

                $stringData = base64_decode(str_replace(" ", "+", $_POST['function']));
                fwrite($fh, $stringData);
                fclose($fh);

                // Write the corresponding unittest into a python file
                $fh = fopen("uploads/" . $this->folder . "/test.rb", 'w+');
                fwrite($fh, base64_decode($this->unittest));
                fclose($fh);
            }

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
 * @license  GPL v3.0
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
            "User" => "kurukshetra",
            "WorkingDir" => "/var/www/html",
            "Env" => ["NODE_PATH=/usr/local/lib/node_modules"],
            "HostConfig" => ["Binds" => [__DIR__ . "/uploads/" . $this->folder . ":/var/www/html:ro"]]
        ];

        $res = json_decode(httpPost($this->url . "/containers/create", json_encode($params)), true);
        $this->container_id = $res["Id"];

        // Starting the container
        $res = httpPost($this->url . "/containers/" . $this->container_id . "/start");
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
        $language = get_challenge_language($_POST['id']);
        if ($language == 'php') {
            $params = (object)[
                "AttachStdin" => false,
                "AttachStdout" => true,
                "AttachStderr" => true,
                "User" => "kurukshetra",
                "Tty" => false,
                "Cmd" => ["phpunit", "/var/www/html/unittests.php"]
            ];
            $exec = json_decode(httpPost($this->url . "/containers/" . $this->container_id . "/exec", json_encode($params)), true);

            // Running the exec
            $params = (object)[
                "Detach" => false,
                "Tty" => false
            ];
            $output = httpPost($this->url . "/exec/" . $exec["Id"] . "/start", json_encode($params));
            $temp = explode("\n", $output);
            $error = "";
            $output = "";
            $count = 0;
            $phpErrors = ['PHP Parse error:'];

            foreach ($temp as $out) {
                foreach ($phpErrors as $err) {
                    if (strpos($out, $err) !== false) {
                        $error .= explode($err, $out)[1] . "\n";
                        echo $err . "\n\n" . $error;
                        return;
                    }
                }
            }

            foreach ($temp as $out) {
                if (stripos($out, 'INFO:') !== false) {
                    $count++;
                    $output .= $count . ". " . explode("INFO: ", $out)[1] . "\n";
                }

                if (strpos($out, 'Error:') !== false) {
                    $error .= explode("Error: ", $out)[1] . "\n";
                }
            }

            if ($error !== "") {
                echo "Error: \n\n" . $error;
                return;
            }

            if ($output == "" && $error == "") {
                update_user_challenge_status($_POST['id']);
                $output = "Congratulations ! You have successfully completed the challenge.";
            }

            echo "Output: \n\n" . $output;
            return;

        } elseif ($language == 'python') {
            $params = (object)[
                "AttachStdin" => false,
                "AttachStdout" => true,
                "AttachStderr" => true,
                "User" => "kurukshetra",
                "Tty" => false,
                "Cmd" => ["python3", "/var/www/html/unittests.py"]
            ];
            $exec = json_decode(httpPost($this->url . "/containers/" . $this->container_id . "/exec", json_encode($params)), true);

            // Running the exec
            $params = (object)[
                "Detach" => false,
                "Tty" => false
            ];
            $output = httpPost($this->url . "/exec/" . $exec["Id"] . "/start", json_encode($params));
            //echo $output;
            $temp = explode("\n", $output);
            $error = "";
            $output = "";
            $count = 0;
            $pythonErrors = ['NameError:', 'ImportError:', 'KeyError:'];

            foreach ($temp as $out) {
                foreach ($pythonErrors as $err) {
                    if (strpos($out, $err) !== false) {
                        $error .= explode($err, $out)[1] . "\n";
                        echo "Error: \n\n" . $err . $error;
                        return;
                    }
                }

                if (strpos($out, 'AssertionError:') !== false) {
                    $count++;
                    $error .= explode("AssertionError: ", $out)[1] . "\n";
                    $output .= $count . ". " . explode("INFO: ", $out)[1] . "\n";
                }
            }

            if ($output !== "") {
                echo "Output: \n\n" . $output;
                return;
            } elseif ($error !== "") {
                echo "Error: \n\n" . $error;
                return;
            }

            if ($output == "" && $error == "") {
                update_user_challenge_status($_POST['id']);
                $output = "Congratulations ! You have successfully completed the challenge.";
            }

            echo "Output: \n\n" . $output;
            return;
        } elseif ($language == 'nodejs') {
            $params = (object)[
                "AttachStdin" => false,
                "AttachStdout" => true,
                "AttachStderr" => true,
                "User" => "kurukshetra",
                "Tty" => false,
                "Cmd" => ["mocha", '--exit']
            ];
            $exec = json_decode(httpPost($this->url . "/containers/" . $this->container_id . "/exec", json_encode($params)), true);

            // Running the exec
            $params = (object)[
                "Detach" => false,
                "Tty" => false
            ];
            $output = httpPost($this->url . "/exec/" . $exec["Id"] . "/start", json_encode($params));
            //echo $output;
            $temp = explode("\n", $output);
            $error = "";
            $output = "";
            $count = 0;
            $nodeErrors = ['ReferenceError:', 'TypeError:', 'Error:'];

            foreach ($temp as $out) {
                foreach ($nodeErrors as $err) {
                    if (strpos($out, $err) !== false) {
                        $error .= explode($err, $out)[1] . "\n";
                        echo "Error: \n\n" . $error;
                        return;
                    }
                }

                if (strpos($out, 'AssertionError:') !== false) {
                    $count++;
                    $error .= explode("AssertionError: ", $out)[1] . "\n";
                    $output .= $count . ". " . explode("INFO: ", $out)[1] . "\n";
                }
            }

            if ($output !== "") {
                echo "Output: \n\n" . $output;
                return;
            } elseif ($error !== "") {
                echo "Error: \n\n" . $error;
                return;
            }

            if ($output == "" && $error == "") {
                update_user_challenge_status($_POST['id']);
                $output = "Congratulations ! You have successfully completed the challenge.";
            }

            echo "Output: \n\n" . $output;
            return;

        } elseif ($language == 'ruby') {
            $params = (object)[
                "AttachStdin" => false,
                "AttachStdout" => true,
                "AttachStderr" => true,
                "Tty" => false,
                "Cmd" => ["ruby", "test.rb"]
            ];
            $exec = json_decode(httpPost($this->url . "/containers/" . $this->container_id . "/exec", json_encode($params)), true);

            // Running the exec
            $params = (object)[
                "Detach" => false,
                "Tty" => false
            ];
            $output = httpPost($this->url . "/exec/" . $exec["Id"] . "/start", json_encode($params));
            $temp = explode("\n", $output);
            $error = "";
            $output = "";
            $count = 0;
            $rubyErrors = ['syntax error', 'undefined method'];

            foreach ($temp as $out) {
                foreach ($rubyErrors as $err) {
                    if (strpos($out, $err) !== false) {
                        $error .= explode($err, $out)[1] . "\n";
                        echo "Error: \n\n" . $err . $error;
                        return;
                    }
                }
            }


            foreach ($temp as $out) {
                if (stripos($out, 'INFO:') !== false) {
                    $count++;
                    $output .= $count . ". " . explode("INFO: ", $out)[1] . "\n";
                    if (strlen($output > 0)) {
                        echo "Output: \n\n" . $output;
                        return;
                    }
                }
            }

            if ($output == "" && $error == "") {
                update_user_challenge_status($_POST['id']);
                $output = "Congratulations ! You have successfully completed the challenge.";
            }

            echo "Output: \n\n" . $output;
            return;
        }
    }

    /**
     * The function will remove the container once its usage is complete.
     *
     * @return void
     */
    public function removeContainer()
    {
        // Killing the container
        httpPost($this->url . "/containers/" . $this->container_id . "/kill");

        // Removing the container
        httpDelete($this->url . "/containers/" . $this->container_id . "?force=1");

    }
    /**
    * Function openport() will check if a given port is open or not.
    */
    function openport($port, $ip='127.0.0.1')
    {
        $fp = @fsockopen($ip, $port, $errno, $errstr, 0.1);
        if (!$fp) {
            return false;
        }
        else {
            fclose($fp);
            return true;
        }
    }

    /**
    * Function hostChallenge() will create a new container using which
    * we can host our challenge. The uploads directory is mounted to the docker
    * as *read only* and challenges are run inside it.
    *
    * @var array $res    Response of the container creation request
    * @var array $params Parameters required for creating a container
    *
    * @return void
    */
    function hostChallenge()
    {
        global $conn;
        $action = "start";
        $port = $_SESSION['challenge_status'][(int)$_POST['id']]['port'];
        if (isset($port)) {
            header('Content-Type: application/json');
            $output = array("status" => True, "port" => $port, "action" => $action);
            echo json_encode($output);
            return;
        }

        do {
            $port = rand(10000, 50000);
        } while($this->openport($port));

        $container_name = "kurukshetra-" . (string)$port;

        $language = get_challenge_language($_POST['id']);

        if (!$language)
        {
            $output = array("status" => False, "port" => null, "action" => $action);
            header('Content-Type: application/json');
            echo json_encode($output);
            return;
        }

        if($language == "php"){
            $params = (object) [
            "Cmd" => ["sh", "-c", "/sbin/my_init; tail -f /dev/null"],
            "Image" => "kurukshetra",
            "WorkingDir" => "/var/www/html",
            "Env" => ["NODE_PATH=/usr/local/lib/node_modules"],
            "HostConfig" => ["Binds" => [__DIR__ . "/uploads/" . $this->folder . ":/var/www/html:ro"], "PortBindings" => ["80/tcp" => [["HostPort" => (string)$port]]]],
            "ExposedPorts" => ["80/tcp" => (object) Null],
            ];
        }

        if($language == "python"){
            $params = (object) [
            "Cmd" => ["python3", "src.py"],
            "User" => "kurukshetra",
            "Image" => "kurukshetra",
            "WorkingDir" => "/var/www/html",
            "Env" => ["NODE_PATH=/usr/local/lib/node_modules"],
            "HostConfig" => ["Binds" => [__DIR__ . "/uploads/" . $this->folder . ":/var/www/html:ro"], "PortBindings" => ["4000/tcp" => [["HostPort" => (string)$port]]]],
            "ExposedPorts" => ["4000/tcp" => (object) Null],
            ];
        }

        if($language == "nodejs"){
            $params = (object) [
            "Cmd" => ["node", "src.js"],
            "User" => "kurukshetra",
            "Image" => "kurukshetra",
            "WorkingDir" => "/var/www/html",
            "Env" => ["NODE_PATH=/usr/local/lib/node_modules"],
            "HostConfig" => ["Binds" => [__DIR__ . "/uploads/" . $this->folder . ":/var/www/html:ro"], "PortBindings" => ["4000/tcp" => [["HostPort" => (string)$port]]]],
            "ExposedPorts" => ["4000/tcp" => (object) Null],
            ];
        }

        if($language == "ruby"){
            $params = (object) [
            "Cmd" => ["ruby", "src.rb"],
            "User" => "kurukshetra",
            "Image" => "kurukshetra",
            "WorkingDir" => "/var/www/html",
            "Env" => ["NODE_PATH=/usr/local/lib/node_modules"],
            "HostConfig" => ["Binds" => [__DIR__ . "/uploads/" . $this->folder . ":/var/www/html:ro"], "PortBindings" => ["4000/tcp" => [["HostPort" => (string)$port]]]],
            "ExposedPorts" => ["4000/tcp" => (object) Null],
            ];
        }

        $res = json_decode(httpPost($this->url . "/containers/create?name=kurukshetra-" . (string)$port, json_encode($params)), true);
        $this->container_id = $res["Id"];

        if($this->container_id) {
            $this->container_id = substr($this->container_id, 0, 12);

            // Starting the container
            $res = httpPost($this->url . "/containers/" . $this->container_id . "/start");

            $_SESSION['challenge_status'][(int)$_POST['id']]['port'] = $port;
            $_SESSION['challenge_status'][(int)$_POST['id']]['container_id'] = $this->container_id;

            // Adding the container details to DB
            $query = "INSERT into container_details(email_id, container_id, container_name, port) VALUES (?,?,?,?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssss", $_SESSION["userData"]["email"], $this->container_id, $container_name, $port);
            $stmt->execute();
        }

        $output = array("status" => False,"port" => Null, "action" => $action);
        if($this->openport($port))
            $output = array("status" => True,"port" => $port, "action" => $action);

        header('Content-Type: application/json');
        echo json_encode($output);

        

        return;
    }

    function destroyChallenge()
    {
        global $conn;

        if (!isset($_SESSION['challenge_status'][(int)$_POST['id']]['container_id'])) {
            header('Content-Type: application/json');
            $output = array("status" => False,"port" => Null, "action" => "stop");
            echo json_encode($output);
            return;
        }

        $container_id = $_SESSION['challenge_status'][(int)$_POST['id']]['container_id'];

        // Killing the container
        httpPost($this->url . "/containers/" . $container_id . "/kill");

        // Removing the container
        httpDelete($this->url . "/containers/" . $container_id . "?force=1");

        // Removing container data from session
        unset($_SESSION['challenge_status'][(int)$_POST['id']]);

        $output = array("status" => True,"port" => Null, "action" => "stop");
        echo json_encode($output);

        //Update DB with the container details
        update_container_status($container_id);

        return;
    }

}

$docker = New Docker();
$docker -> mysqlConnect();

if ($_POST['action'] == 'start'){
    $_POST['function'] = $docker->srccode;
    $docker -> prepare();
    $docker -> hostChallenge();
}
else if ($_POST['action'] == 'stop') {
    $docker -> destroyChallenge();
}
else {
    $docker -> prepare();
    $docker -> createContainer();
    $docker -> execContainer();
    $docker -> removeContainer();
    $docker -> cleanup();
}
