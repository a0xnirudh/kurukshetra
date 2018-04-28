<?php

error_reporting(0);

if (!function_exists('write_ini_file')) {
    /**
     * Write an ini configuration file
     *
     * @param string $file  Filename where configuration is to be written
     * @param array  $array Configuration
     *
     * @return bool
     */
    function write_ini_file($file, $array = [])
    {
        // check first argument is string
        if (!is_string($file)) {
            throw new \InvalidArgumentException('First argument must be a string.');
        }

        // check second argument is array
        if (!is_array($array)) {
            throw new \InvalidArgumentException('Second argument must be an array.');
        }

        // process array
        $data = array();
        foreach ($array as $key => $val) {
            if (is_array($val)) {
                $data[] = "[$key]";
                foreach ($val as $skey => $sval) {
                    if (is_array($sval)) {
                        foreach ($sval as $_skey => $_sval) {
                            if (is_numeric($_skey)) {
                                $data[] = $skey.'[] = '.(is_numeric($_sval) ? $_sval : (ctype_upper($_sval) ? $_sval : '"'.$_sval.'"'));
                            } else {
                                $data[] = $skey.'['.$_skey.'] = '.(is_numeric($_sval) ? $_sval : (ctype_upper($_sval) ? $_sval : '"'.$_sval.'"'));
                            }
                        }
                    } else {
                        $data[] = $skey.' = '.(is_numeric($sval) ? $sval : (ctype_upper($sval) ? $sval : '"'.$sval.'"'));
                    }
                }
            } else {
                $data[] = $key.' = '.(is_numeric($val) ? $val : (ctype_upper($val) ? $val : '"'.$val.'"'));
            }
            // empty line
            $data[] = null;
        }

        // open file pointer, init flock options
        $fp = fopen($file, 'w');
        $retries = 0;
        $max_retries = 100;

        if (!$fp) {
            return false;
        }

        // loop until get lock, or reach max retries
        do {
            if ($retries > 0) {
                usleep(rand(1, 5000));
            }
            $retries += 1;
        } while (!flock($fp, LOCK_EX) && $retries <= $max_retries);

        // couldn't get the lock
        if ($retries == $max_retries) {
            return false;
        }

        // got lock, write data
        fwrite($fp, implode(PHP_EOL, $data).PHP_EOL);

        // release lock
        flock($fp, LOCK_UN);
        fclose($fp);

        return true;
    }
}


$dbhost = $_POST['host'];
$dbuser = $_POST['user'];
$dbpass = $_POST['pass'];

$clientId = $_POST['clientId'];
$clientSecret = $_POST['clientSecret'];

try{
    $failed = ['test'=>'failed'];
    $succeed = ['test'=>'succeed'];
    header('Content-Type: application/json');

    $connect = mysqli_connect($dbhost, $dbuser, $dbpass) or die(json_encode($failed));

    if ($clientId != "" && $clientSecret != "") {

        $config['database']['servername'] = $dbhost;
        $config['database']['username'] = $dbuser;
        $config['database']['password'] = $dbpass;
        $config['database']['dbname'] = "kurukshetra";

        $config['google_oauth']['clientId'] = $clientId;
        $config['google_oauth']['clientSecret'] = $clientSecret;

        write_ini_file('/var/config/.kurukshetra.ini', $config);
    }

    die(json_encode($succeed));
}
catch(Exception $ae){
    echo $ae->getMessage();
}
?>