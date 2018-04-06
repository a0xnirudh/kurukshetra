<?php
/**
 * Created by Anirudh Anand.
 * User: a0xnirudh
 * Date: 23/10/17
 */

require __DIR__ . '/db-credentials.php';

//sanitize function
function s($userinput) {
    global $connection;
    return mysqli_real_escape_string($connection, $userinput);
}

# creating database
$query = "CREATE database security_playground";
if (mysqli_query($connection, $query)) {
    echo "[+] Database 'security_playground' created. \n";
    echo "<br><br>\n";
} else {
    echo "[+] Error creating database: " . mysqli_error($connection);
    echo "<br><br>\n";
}


# Creating necessary tables

$query = "CREATE TABLE `users` (
 `id` int NOT NULL AUTO_INCREMENT,
 `oauth_provider` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 `oauth_uid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 `first_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
 `last_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
 `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 `gender` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
 `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
 `picture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 `created` datetime NOT NULL,
 `modified` datetime NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($connection, $query)) {
        echo "[+] Created new table security_playground. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error creating new table: " . mysqli_error($connection);
        echo "<br><br>\n";
}

# categories table
$query = "CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `approved` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($connection, $query)) {
        echo "[+] Created new table categories. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error creating new table: " . mysqli_error($connection);
        echo "<br><br>\n";
}

# difficulties table
$query = "CREATE TABLE `difficulties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `points` int(11) DEFAULT '10',
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($connection, $query)) {
        echo "[+] Created new table difficulties. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error creating new table: " . mysqli_error($connection);
        echo "<br><br>\n";
}

# faqs table
$query = "CREATE TABLE `faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `answer` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($connection, $query)) {
        echo "[+] Created new table faqs. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error creating new table: " . mysqli_error($connection);
        echo "<br><br>\n";
}

# Challenges table
$query = "CREATE TABLE `challenges` (
`id` int NOT NULL AUTO_INCREMENT,
`name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
`code` TEXT COLLATE utf8_unicode_ci NOT NULL,
`intro` TEXT COLLATE utf8_unicode_ci NOT NULL,
`instruction` TEXT COLLATE utf8_unicode_ci NOT NULL,
`reference` TEXT COLLATE utf8_unicode_ci NOT NULL,
`approved` int COLLATE utf8_unicode_ci DEFAULT '0',
`enabled` int COLLATE utf8_unicode_ci DEFAULT '0',
`points` int COLLATE utf8_unicode_ci DEFAULT '10',
`difficulty` varchar(6) COLLATE utf8_unicode_ci DEFAULT 'easy',
`type` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
`language` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
`timestamp` datetime NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($connection, $query)) {
        echo "[+] Created new table challenges. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error creating new table: " . mysqli_error($connection);
        echo "<br><br>\n";
}


# Dummy data for challenges
$name = s("PHP XSS");
$code = s('function sanitize($user_input) {
    $x = htmlspecialchars(trim($user_input));
    return "<span title=\'".$x."\'>User input added as span title </span>";
}');
$intro = s("XSS permits a malevolent user to inject his own code in vulnerable web pages. According to the OWASP 2010 Top 10 Application Security Risks, XSS attacks rank 2nd in the \"most dangerous\" list.");
$instruction = s("This space contains the sample instruction.");
$reference = s("These are sample reference links !!");
$date = date("Y-m-d H:i:s");

$query = "INSERT INTO challenges (name, code, intro, instruction, reference, type, language, timestamp) VALUES ('$name','$code','$intro','$instruction','$reference', 'xss', 'php', '$date')";


if (mysqli_query($connection, $query)) {
        echo "[+] Inserted dummy value to challenges. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error inserting value to challenges: " . mysqli_error($connection);
        echo "<br><br>\n";
}


# hints table
$query = "CREATE TABLE `hints` (
`id` int NOT NULL AUTO_INCREMENT,
`challenge_id` int,
`hint_text` TEXT COLLATE utf8_unicode_ci,
`timestamp` datetime NOT NULL,
PRIMARY KEY (`id`),
FOREIGN KEY (`challenge_id`) REFERENCES challenges (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($connection, $query)) {
        echo "[+] Created new table hints. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error creating new table: " . mysqli_error($connection);
        echo "<br><br>\n";
}


# Unittest table
$query = "CREATE TABLE `unittests` (
`id` int NOT NULL AUTO_INCREMENT,
`challenge_id` int,
`unittest` TEXT COLLATE utf8_unicode_ci,
PRIMARY KEY (`id`),
FOREIGN KEY (`challenge_id`) REFERENCES challenges (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($connection, $query)) {
        echo "[+] Created new table unittests. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error creating new table: " . mysqli_error($connection);
        echo "<br><br>\n";
}

# Dummy data for unittests
$unittest = s('<?php
$current_path = realpath(dirname(__FILE__));
require_once($current_path . \'/src.php\');
class SrcTest extends PHPUnit_Framework_TestCase
{
    public function setUp(){ }
    public function tearDown(){ }
    public function testIsSanitized()
    {
        $connObj = new Src();
        $payload = "\'onload=\'alert(2)";
        $result = $connObj->sanitize($payload);
        $this->assertNotContains($payload, $result);
    }
}');

$query = "INSERT INTO unittests (challenge_id, unittest) VALUES ('1', '$unittest')";

if (mysqli_query($connection, $query)) {
        echo "[+] Inserted data new table unittests. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error insert into unittest: " . mysqli_error($connection);
        echo "<br><br>\n";
}
