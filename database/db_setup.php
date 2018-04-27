<?php
function s($userinput) {

    global $conn;
    return mysqli_real_escape_string($conn, $userinput);
}

global $conn, $config;

# creating database
$query = "CREATE database ".$config['dbname']."";
if (mysqli_query($conn, $query)) {
    echo "[+] Database '".$config['dbname']."' created. \n";
    echo "<br><br>\n";
} else {
    echo "[+] Error creating database: " . mysqli_error($conn);
    echo "<br><br>\n";
}

mysqli_select_db($conn,$config['dbname']);

# Creating necessary tables

$query = "CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oauth_provider` varchar(255) NOT NULL,
  `oauth_uid` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100),
  `email` varchar(255) NOT NULL,
  `gender` varchar(10),
  `locale` varchar(10),
  `picture` varchar(255),
  `link` varchar(255),
  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `is_admin` tinyint(1) DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `updated_by` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";

if (mysqli_query($conn, $query)) {
        echo "[+] Created users table security_playground. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error creating new table: users #" . mysqli_error($conn);
        echo "<br><br>\n";
}

# categories table
$query = "CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `approved` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

if (mysqli_query($conn, $query)) {
        echo "[+] Created new table categories. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error creating new table: " . mysqli_error($conn);
        echo "<br><br>\n";
}


$query = "INSERT INTO `categories` (`name`) VALUES ('XSS'), ('SQL'), ('CSRF'), ('IDOR'), ('CRLF'), ('LFI/RFI'), ('Others')";

if (mysqli_query($conn, $query)) {
        echo "[+] Inserted dummy value to categories. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error inserting value to categories: " . mysqli_error($conn);
        echo "<br><br>\n";
}


# difficulties table
$query = "CREATE TABLE `difficulties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `points` int(11) DEFAULT '10',
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

if (mysqli_query($conn, $query)) {
        echo "[+] Created new table difficulties. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error creating new table: " . mysqli_error($conn);
        echo "<br><br>\n";
}


$query = "INSERT INTO `difficulties` (`name`,`points`) VALUES ('easy',10),('medium',20),('hard',30)";

if (mysqli_query($conn, $query)) {
        echo "[+] Inserted dummy value to difficulties. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error inserting value to difficulties: " . mysqli_error($conn);
        echo "<br><br>\n";
}

# faqs table
$query = "CREATE TABLE `faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `answer` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `enabled` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `question` (`question`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

if (mysqli_query($conn, $query)) {
        echo "[+] Created new table faqs. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error creating new table: " . mysqli_error($conn);
        echo "<br><br>\n";
}

# Challenges table
$query = "CREATE TABLE `challenges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `code` longblob NOT NULL,
  `intro` text COLLATE utf8_unicode_ci NOT NULL,
  `instructions` text COLLATE utf8_unicode_ci NOT NULL,
  `reference` text COLLATE utf8_unicode_ci NOT NULL,
  `approved` int(11) DEFAULT '1',
  `enabled` int(11) DEFAULT '1',
  `points` int(11) DEFAULT '10',
  `difficulty` varchar(6) COLLATE utf8_unicode_ci DEFAULT 'easy',
  `type` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

if (mysqli_query($conn, $query)) {
        echo "[+] Created new table challenges. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error creating new table: " . mysqli_error($conn);
        echo "<br><br>\n";
}


# Dummy data for challenges
$name = s("PHP XSS");
$code = 'PD9waHANCmNsYXNzIFNyYw0Kew0KICAgIGZ1bmN0aW9uIHNhbml0aXplKCR1c2VyX2lucHV0KQ0KICAgIHsNCiAgICAgICAgJHggPSBodG1sc3BlY2lhbGNoYXJzKHRyaW0oJHVzZXJfaW5wdXQpKTsNCiAgICAgICAgcmV0dXJuICI8c3BhbiB0aXRsZT0nIiAuICR4IC4gIic+VXNlciBpbnB1dCBhZGRlZCBhcyBzcGFuIHRpdGxlIDwvc3Bhbj4iOw0KICAgIH0NCn0=';
$intro = s("XSS permits a malevolent user to inject his own code in vulnerable web pages. According to the OWASP 2010 Top 10 Application Security Risks, XSS attacks rank 2nd in the \"most dangerous\" list.");
$instruction = s("This space contains the sample instruction.");
$reference = s("These are sample reference links !!");
$date = date("Y-m-d H:i:s");

$query = "INSERT INTO challenges (name, code, intro, instructions, reference, type, language, timestamp) VALUES ('$name','$code','$intro','$instruction','$reference', 'xss', 'php', '$date')";


if (mysqli_query($conn, $query)) {
        echo "[+] Inserted dummy value to challenges. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error inserting value to challenges: " . mysqli_error($conn);
        echo "<br><br>\n";
}

#languages table
$query = "CREATE TABLE `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `approved` int(11) DEFAULT '1',
  `enabled` int(11) DEFAULT '0',
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";

if (mysqli_query($conn, $query)) {
        echo "[+] Created new table languages. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error creating new table: " . mysqli_error($conn);
        echo "<br><br>\n";
}

$query = "INSERT INTO `languages` (`name`) VALUES ('php')";

if (mysqli_query($conn, $query)) {
        echo "[+] Inserted dummy value to languages. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error inserting value to languages: " . mysqli_error($conn);
        echo "<br><br>\n";
}


# hints table
$query = "CREATE TABLE `hints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `challenge_id` int(11) DEFAULT NULL,
  `hint_text` text COLLATE utf8_unicode_ci,
  `timestamp` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`),
  KEY `challenge_id` (`challenge_id`),
  CONSTRAINT `hints_ibfk_1` FOREIGN KEY (`challenge_id`) REFERENCES `challenges` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

if (mysqli_query($conn, $query)) {
        echo "[+] Created new table hints. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error creating new table: " . mysqli_error($conn);
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

if (mysqli_query($conn, $query)) {
        echo "[+] Created new table unittests. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error creating new table: " . mysqli_error($conn);
        echo "<br><br>\n";
}

// Dummy data for unittests
$unittest = 'PD9waHANCnVzZSBQSFBVbml0XEZyYW1ld29ya1xUZXN0Q2FzZTsNCiRjdXJyZW50X3BhdGggPSByZWFscGF0aChkaXJuYW1lKF9fRklMRV9fKSk7DQpyZXF1aXJlX29uY2UoJGN1cnJlbnRfcGF0aCAuICcvc3JjLnBocCcpOw0KY2xhc3MgU3JjVGVzdCBleHRlbmRzIFRlc3RDYXNlDQp7DQogICAgcHVibGljIGZ1bmN0aW9uIHNldFVwKCl7IH0NCiAgICBwdWJsaWMgZnVuY3Rpb24gdGVhckRvd24oKXsgfQ0KICAgIHB1YmxpYyBmdW5jdGlvbiB0ZXN0SXNTYW5pdGl6ZWQoKQ0KICAgIHsNCiAgICAgICAgJGNvbm5PYmogPSBuZXcgU3JjKCk7DQogICAgICAgICRwYXlsb2FkID0gIlwnb25sb2FkPVwnYWxlcnQoMikiOw0KICAgICAgICAkcmVzdWx0ID0gJGNvbm5PYmotPnNhbml0aXplKCRwYXlsb2FkKTsNCiAgICAgICAgJHRoaXMtPmFzc2VydE5vdENvbnRhaW5zKCRwYXlsb2FkLCAkcmVzdWx0KTsNCiAgICB9DQoNCiAgICBwdWJsaWMgZnVuY3Rpb24gdGVzdElzU2FuaXRpemVkMigpDQogICAgew0KICAgICAgICAkY29ubk9iaiA9IG5ldyBTcmMoKTsNCiAgICAgICAgJHBheWxvYWQgPSBtZDUocmFuZG9tX2J5dGVzKDEwKSk7DQogICAgICAgICRyZXN1bHQgPSAkY29ubk9iai0+c2FuaXRpemUoJHBheWxvYWQpOw0KICAgICAgICAkZXhwZWN0ZWQgPSAiPHNwYW4gdGl0bGU9JyIgLiAkcGF5bG9hZCAuICInPlVzZXIgaW5wdXQgYWRkZWQgYXMgc3BhbiB0aXRsZSA8L3NwYW4+IjsNCiAgICAgICAgJHRoaXMtPmFzc2VydEVxdWFscygkZXhwZWN0ZWQsICRyZXN1bHQpOw0KICAgIH0NCn0=';

$query = "INSERT INTO unittests (challenge_id, unittest) VALUES ('1', '$unittest')";

if (mysqli_query($conn, $query)) {
        echo "[+] Inserted data new table unittests. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error insert into unittest: " . mysqli_error($conn);
        echo "<br><br>\n";
}
