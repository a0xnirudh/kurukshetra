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
        echo "[+] Created users table. \n";
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


$query = "INSERT INTO `categories` (`name`) VALUES ('XSS'), ('SQLi'), ('SSTI'), ('IDOR'), ('CRLF'), ('LFI/RFI'), ('Others')";

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
  `hints` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

if (mysqli_query($conn, $query)) {
        echo "[+] Created new table challenges. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error creating new table: " . mysqli_error($conn);
        echo "<br><br>\n";
}


# Container Tables
$query = "CREATE TABLE `container_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_id` varchar(100),
  `container_id` varchar(250),
  `container_name` varchar(50),
  `port` varchar(10),
  `status` enum('running','exited') default 'running',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);";

if (mysqli_query($conn, $query)) {
        echo "[+] Created new table Container details. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error creating new table: " . mysqli_error($conn);
        echo "<br><br>\n";
}


# Dummy data for challenges
$name = s("PHP XSS");
$code = 'PD9waHAKY2xhc3MgU3JjIHsKICAgIGZ1bmN0aW9uIHNhbml0aXplKCR1c2VyX2lucHV0KSB7CiAg
ICAgICAgJHggPSBodG1sc3BlY2lhbGNoYXJzKHRyaW0oJHVzZXJfaW5wdXQpKTsKICAgICAgICBy
ZXR1cm4gIjxzcGFuIHRpdGxlPSciLiR4LiInPlVzZXIgaW5wdXQgYWRkZWQgYXMgc3BhbiB0aXRs
ZSA8L3NwYW4+IjsKICAgIH0KfQo=';
$intro = s("XSS permits a malevolent user to inject his own code in vulnerable web pages. According to the OWASP 2010 Top 10 Application Security Risks, XSS attacks rank 2nd in the \"most dangerous\" list.");
$instruction = s("This space contains the sample instruction.");
$reference = s("These are sample reference links !!");
$date = date("Y-m-d H:i:s");

$query = "INSERT INTO challenges (name, code, intro, instructions, reference, type, language, difficulty, timestamp) VALUES ('$name','$code','$intro','$instruction','$reference', 'xss', 'php','easy', '$date')";

$name = s("Python - Rendering is fun !");
$code = 'ZnJvbSBmbGFzayBpbXBvcnQgRmxhc2sKZnJvbSBmbGFzayBpbXBvcnQgcmVxdWVzdApmcm9tIGZs
YXNrIGltcG9ydCBSZXNwb25zZQpmcm9tIGZsYXNrIGltcG9ydCByZW5kZXJfdGVtcGxhdGVfc3Ry
aW5nCgoKYXBwID0gRmxhc2soX19uYW1lX18pCmRhdGEgPSB7fQoKQGFwcC5yb3V0ZSgnLycpCmRl
ZiBoZWxsbygpOgogICAgcmV0dXJuIFJlc3BvbnNlKCJIZWxsbyBXb3JsZCEiLCBtaW1ldHlwZT0n
dGV4dC9odG1sJykKCkBhcHAucm91dGUoJy88bmFtZT4nKQpkZWYgaGVsbG9fbmFtZShuYW1lKToK
ICAgIGRhdGFbJ25hbWUnXSA9IG5hbWUKICAgIGRhdGFbJ3NlY3JldCddID0gcmVxdWVzdC5hcmdz
LmdldCgnc2VjcmV0JykKICAgIHRlbXBsYXRlID0gIjxoMj5IZWxsbyAlcyE8L2gyPiIgJSBkYXRh
WyduYW1lJ10KICAgIHJldHVybiByZW5kZXJfdGVtcGxhdGVfc3RyaW5nKHRlbXBsYXRlLCBkYXRh
PWRhdGEpCgoKaWYgX19uYW1lX18gPT0gJ19fbWFpbl9fJzoKICAgIGFwcC5ydW4oKQo=';
$intro = s("Server-side template injection occurs when user-controlled input is embedded into a server-side template, allowing users to inject template directives. This allows an attacker to inject malicious template directives and possibly execute arbitrary code on the affected server.");
$instruction = s("Modify and submit the update code by patching the vulnerability but should retain the functionality of the existing code.");
$reference = s("These are sample reference links !!");
$date = date("Y-m-d H:i:s");

$query2 = "INSERT INTO challenges (name, code, intro, instructions, reference, type, language, difficulty, timestamp) VALUES ('$name','$code','$intro','$instruction','$reference', 'ssti', 'python', 'hard', '$date')";


# Dummy data for challenges
$name = s("NodeXSS");
$code = 'dmFyIGV4cHJlc3MgICAgPSByZXF1aXJlKCdleHByZXNzJyk7CnZhciBib2R5UGFyc2VyID0gcmVx
dWlyZSgnYm9keS1wYXJzZXInKTsKCnZhciBhcHAgPSBleHByZXNzKCk7CgphcHAudXNlKGJvZHlQ
YXJzZXIuanNvbigpKTsKYXBwLnVzZShib2R5UGFyc2VyLnVybGVuY29kZWQoeyBleHRlbmRlZDog
dHJ1ZSB9KSk7CgphcHAuZ2V0KCIvIiwgZnVuY3Rpb24gKHJlcSwgcmVzKSB7CiAgICByZXMuc3Rh
dHVzKDIwMCkuc2VuZCgnPGgxPkhlbGxvIFdvcmxkITwvaDE+Jyk7CiAgfSk7CgphcHAuZ2V0KCcv
Om5hbWUnLCBmdW5jdGlvbihyZXEsIHJlcykgewogICAgdmFyIG5hbWUgPSByZXEucGFyYW1zLm5h
bWU7CiAgICByZXMudHlwZSgnaHRtbCcpLnNlbmQoJ0hlbGxvICcgKyBuYW1lKTsKfSk7CgppZiAo
cmVxdWlyZS5tYWluID09PSBtb2R1bGUpIHsgICAgCiAgICBhcHAubGlzdGVuKDQwMDAsIGZ1bmN0
aW9uKCkgewogICAgICAgIGNvbnNvbGUubG9nKCAnVGhlIHNlcnZlciBpcyB1cCEnICk7CiAgICB9
ICk7CiAgICAKfSBlbHNlIHsKICAgIG1vZHVsZS5leHBvcnRzID0gYXBwOyAgIAp9Cgo=';
$intro = s("Cross-site Scripting (XSS) refers to client-side code injection attack wherein an attacker can execute malicious scripts (also commonly referred to as a malicious payload) into a legitimate website or web application. XSS is amongst the most rampant of web application vulnerabilities and occurs when a web application makes use of unvalidated or unencoded user input within the output it generates.");
$instruction = s("Modify and submit the update code by patching the vulnerability but should retain the functionality of the existing code.");
$reference = s("These are sample reference links !!");
$date = date("Y-m-d H:i:s");

$query3 = "INSERT INTO challenges (name, code, intro, instructions, reference, type, language, difficulty, timestamp) VALUES ('$name','$code','$intro','$instruction','$reference', 'xss', 'nodejs', 'medium', '$date')";


$name = s("RubyXSS");
$code = 'cmVxdWlyZSAnc2luYXRyYScKCnNldCA6YmluZCwgJzAuMC4wLjAnCnNldCA6cG9ydCwgNDAwMAoK
YmVmb3JlIGRvCiAgICBjb250ZW50X3R5cGUgJ3RleHQvaHRtbCcKZW5kCgpnZXQgJy8nIGRvCiAg
ICAnSGVsbG8gV29ybGQnCmVuZAoKZ2V0ICcvOm5hbWUnIGRvCiAgICAnSGVsbG8gJyArIHBhcmFt
c1s6bmFtZV0KZW5kCg==';
$intro = s("Cross-site Scripting (XSS) refers to client-side code injection attack wherein an attacker can execute malicious scripts (also commonly referred to as a malicious payload) into a legitimate website or web application. XSS is amongst the most rampant of web application vulnerabilities and occurs when a web application makes use of unvalidated or unencoded user input within the output it generates.");
$instruction = s("Modify and submit the update code by patching the vulnerability but should retain the functionality of the existing code.");
$reference = s("These are sample reference links !!");
$date = date("Y-m-d H:i:s");

$query4 = "INSERT INTO challenges (name, code, intro, instructions, reference, type, language, difficulty, timestamp) VALUES ('$name','$code','$intro','$instruction','$reference', 'xss', 'ruby', 'easy', '$date')";

if (mysqli_query($conn, $query)) {
    if (mysqli_query($conn, $query2)) {
        mysqli_query($conn, $query3);
        mysqli_query($conn, $query4);
        echo "[+] Inserted dummy value to challenges. \n";
        echo "<br><br>\n";
    }
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

$query = "INSERT INTO `languages` (`name`) VALUES ('php'), ('python'), ('nodejs'), ('ruby')";

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
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `enabled` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `challenge_id` (`challenge_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($conn, $query)) {
        echo "[+] Created new table hints. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error creating new table: " . mysqli_error($conn);
        echo "<br><br>\n";
}

# user_level_enabled_challenges table
$query = "CREATE TABLE `user_level_enabled_challenges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level_id` longblob NOT NULL,
  `email_id` longblob NOT NULL,
  `enabled` int(11) DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `chall_code` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($conn, $query)) {
        echo "[+] Created new table user_level_enabled_challenges. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error creating new table: " . mysqli_error($conn);
        echo "<br><br>\n";
}


# user_challenges table
$query = "CREATE TABLE `user_challenges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `chall_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($conn, $query)) {
        echo "[+] Created new table user_challenges. \n";
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
$unittest1 = 'PD9waHAKdXNlIFBIUFVuaXRcRnJhbWV3b3JrXFRlc3RDYXNlOwoKJGN1cnJlbnRfcGF0aCA9IHJl
YWxwYXRoKGRpcm5hbWUoX19GSUxFX18pKTsKCnRyeSB7CnJlcXVpcmVfb25jZSgkY3VycmVudF9w
YXRoIC4gJy9zcmMucGhwJyk7Cn0gY2F0Y2goRXhjZXB0aW9uICRlKSB7CiAgICBlY2hvICdJTkZP
OiAnIC4kZS0+Z2V0TWVzc2FnZSgpOwp9CgpjbGFzcyBTcmNUZXN0IGV4dGVuZHMgVGVzdENhc2UK
ewogICAgcHVibGljIGZ1bmN0aW9uIHNldFVwKCl7IH0KICAgIHB1YmxpYyBmdW5jdGlvbiB0ZWFy
RG93bigpeyB9CiAgICBwdWJsaWMgZnVuY3Rpb24gdGVzdElzU2FuaXRpemVkKCkKICAgIHsKICAg
ICAgICAkY29ubk9iaiA9IG5ldyBTcmMoKTsKICAgICAgICAkcGF5bG9hZCA9ICInb25sb2FkPSdh
bGVydCgyKSI7CiAgICAgICAgJHJlc3VsdCA9ICRjb25uT2JqLT5zYW5pdGl6ZSgkcGF5bG9hZCk7
CiAgICAgICAgJHRoaXMtPmFzc2VydE5vdENvbnRhaW5zKCRwYXlsb2FkLCAkcmVzdWx0LCAiSU5G
TzogWFNTIFRyaWdnZXJlZC4gRnVuY3Rpb24gc2FuaXRpemUoKSBpcyB2dWxuZXJhYmxlICEiKTsK
ICAgIH0KCiAgICBwdWJsaWMgZnVuY3Rpb24gdGVzdElzU2FuaXRpemVkMigpCiAgICB7CiAgICAg
ICAgJGNvbm5PYmogPSBuZXcgU3JjKCk7CiAgICAgICAgJHBheWxvYWQgPSBtZDUocmFuZG9tX2J5
dGVzKDEwKSk7CiAgICAgICAgJHJlc3VsdCA9ICRjb25uT2JqLT5zYW5pdGl6ZSgkcGF5bG9hZCk7
CiAgICAgICAgJGV4cGVjdGVkID0gIjxzcGFuIHRpdGxlPSciIC4gJHBheWxvYWQgLiAiJz5Vc2Vy
IGlucHV0IGFkZGVkIGFzIHNwYW4gdGl0bGUgPC9zcGFuPiI7CiAgICAgICAgJHRoaXMtPmFzc2Vy
dEVxdWFscygkZXhwZWN0ZWQsICRyZXN1bHQsICJJTkZPOiBGdW5jdGlvbmFsaXR5IG9mIHNhbml0
aXplKCkgaXMgbm90IGJlaW5nIHJldGFpbmVkICEiKTsKICAgIH0KfQo=';

$unittest2 = 'aW1wb3J0IG9zCmltcG9ydCBzeXMKaW1wb3J0IHRpbWUKaW1wb3J0IHJhbmRvbQppbXBvcnQgaGFz
aGxpYgppbXBvcnQgdW5pdHRlc3QKCnN5cy5wYXRoLmluc2VydCgwLCBvcy5wYXRoLmRpcm5hbWUo
b3MucGF0aC5hYnNwYXRoKF9fZmlsZV9fKSkpCmZyb20gc3JjIGltcG9ydCBhcHAKZnJvbSBzcmMg
aW1wb3J0IGRhdGEKCgojIFNlZWQgZm9yIHJhbmRvbQpyYW5kb20uc2VlZCh0aW1lLnRpbWUoKSkK
CmNsYXNzIFNyY1Rlc3QodW5pdHRlc3QuVGVzdENhc2UpOgoKICAgIGRlZiBzZXRVcChzZWxmKToK
ICAgICAgICBzZWxmLmFwcCA9IGFwcC50ZXN0X2NsaWVudCgpCgogICAgZGVmIHRlYXJEb3duKHNl
bGYpOgogICAgICAgIHBhc3MKCgogICAgIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyBGdW5j
dGlvbmFsaXR5IHRlc3RzICMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMKCiAgICBkZWYgdGVz
dF9tYWluX3BhZ2Uoc2VsZik6CiAgICAgICAgcmVzcG9uc2UgPSBzZWxmLmFwcC5nZXQoJy8nKQog
ICAgICAgIHNlbGYuYXNzZXJ0RXF1YWwocmVzcG9uc2Uuc3RhdHVzX2NvZGUsIDIwMCwgJ0lORk86
IFJlcXVlc3QgaXMgZ2l2aW5nIGludmFsaWQgc3RhdHVzIGNvZGU6ICcuZW5jb2RlKCkgKyBzdHIo
cmVzcG9uc2Uuc3RhdHVzX2NvZGUpLmVuY29kZSgpKQoKCiAgICBkZWYgdGVzdF9uYW1lX3BhZ2Uo
c2VsZik6CiAgICAgICAgbmFtZSA9IGIndW5pdHRlc3QnCiAgICAgICAgc2VjcmV0ID0gaGFzaGxp
Yi5zaGExKHN0cihyYW5kb20ucmFuZG9tKCkpLmVuY29kZSgndXRmLTgnKSkuaGV4ZGlnZXN0KCku
ZW5jb2RlKCkKICAgICAgICByZXNwb25zZSA9IHNlbGYuYXBwLmdldChiJy8nICsgbmFtZSArIGIn
P3NlY3JldD0nICsgc2VjcmV0KQogICAgICAgIHNlbGYuYXNzZXJ0SW4obmFtZSwgcmVzcG9uc2Uu
ZGF0YSwgYidJTkZPOiBGdW5jdGlvbiBpcyBub3QgcmV0dXJuaW5nIG5hbWUgY29ycmVjdGx5IScp
CiAgICAgICAgc2VsZi5hc3NlcnRFcXVhbChkYXRhWydzZWNyZXQnXS5lbmNvZGUoKSwgc2VjcmV0
LCBiJ0lORk86IGhlbGxvX25hbWUoKSwgc2VjcmV0IGlzIG5vdCBnZXR0aW5nIGluaXRpYWxpemVk
IScpCiAgICAgICAgCgogICAgIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyBTZWN1cml0
eSB0ZXN0cyAjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjCgogICAgZGVmIHRlc3RfbmFt
ZV9zZWN1cml0eShzZWxmKToKICAgICAgICBzZWNyZXQgPSBoYXNobGliLm1kNShzdHIocmFuZG9t
LnJhbmRvbSgpKS5lbmNvZGUoJ3V0Zi04JykpLmhleGRpZ2VzdCgpCiAgICAgICAgcmVzcG9uc2Ug
PSBzZWxmLmFwcC5nZXQoJy97e2RhdGEuc2VjcmV0fX0/c2VjcmV0PScgKyBzZWNyZXQpCiAgICAg
ICAgc2VsZi5hc3NlcnROb3RJbihzZWNyZXQuZW5jb2RlKCksIHJlc3BvbnNlLmRhdGEsIGInSU5G
TzogRnVuY3Rpb24gaGVsbG9fbmFtZSgpIGlzIHZ1bG5lcmFibGUgdG8gU1NUSSAhJykKCgoKaWYg
X19uYW1lX18gPT0gIl9fbWFpbl9fIjoKICAgIHVuaXR0ZXN0Lm1haW4oKQ==';

$unittest3 = 'dmFyIGFzc2VydCA9IHJlcXVpcmUoJ2NoYWknKS5hc3NlcnQ7CnZhciBzdXBlcmFnZW50ID0gcmVx
dWlyZSgnc3VwZXJhZ2VudCcpOwp2YXIgcmFuZG9tc3RyaW5nID0gcmVxdWlyZSgicmFuZG9tc3Ry
aW5nIik7CnZhciBzZXJ2ZXIgPSByZXF1aXJlKCcuL3NyYycpOwoKZGVzY3JpYmUoJ3NlcnZlcics
IGZ1bmN0aW9uKCkgewogICAgdmFyIGluc3RhbmNlOyAgIAogCiAgICBiZWZvcmVFYWNoKGZ1bmN0
aW9uKCBkb25lICkgewogICAgICAgIGluc3RhbmNlID0gc2VydmVyLmxpc3Rlbig0MDAwLCBmdW5j
dGlvbihlcnIpIHsKICAgICAgICAgICAgZG9uZShlcnIpOwogICAgICAgIH0pCiAgICB9KTsKICAg
IAogICAgYWZ0ZXJFYWNoKGZ1bmN0aW9uKCBkb25lICkgewogICAgICAgIGluc3RhbmNlLmNsb3Nl
KGZ1bmN0aW9uKGVycikgewogICAgICAgICAgICBkb25lKGVycik7CiAgICAgICAgfSk7CiAgICB9
KTsKICAgIAogICAgCiAgICBpdCgnc2hvdWxkIHJldHVybiBIZWxsbyBXb3JsZCBhdCAvJywgZnVu
Y3Rpb24oIGRvbmUgKSB7IAogICAgICAgIHN1cGVyYWdlbnQuZ2V0KCdodHRwOi8vbG9jYWxob3N0
OjQwMDAvJykuZW5kKGZ1bmN0aW9uKGVyciwgcmVzKSB7CiAgICAgICAgICAgIGlmIChlcnIpIHsg
cmV0dXJuIGRvbmUoZXJyKTsgfQogICAgICAgIHRyeXsgICAgICAgICAgICAKICAgICAgICAgICAg
YXNzZXJ0LmVxdWFsKHJlcy5zdGF0dXMsIDIwMCk7CiAgICAgICAgICAgIGFzc2VydC5lcXVhbChy
ZXMudGV4dCwgJzxoMT5IZWxsbyBXb3JsZCE8L2gxPicpOwogICAgICAgICAgICBkb25lKCk7CiAg
ICAgICAgfSBjYXRjaCAoZXJyb3IpewogICAgICAgICAgICBlcnJvci5tZXNzYWdlID0gIklORk86
IFBhdGggLyBpcyBub3Qgd29ya2luZyEiOwogICAgICAgIH0KICAgICAgICB9KTsKICAgICAgICAK
ICAgIH0pOwoKCiAgICBpdCgnc2hvdWxkIHJldHVybiBIZWxsbyA8cmFuZG9tPiBhdCAvPHJhbmRv
bT4nLCBmdW5jdGlvbiggZG9uZSApIHsKICAgICAgICB2YXIgcmFuZG9tID0gcmFuZG9tc3RyaW5n
LmdlbmVyYXRlKCk7CiAgICAgICAgc3VwZXJhZ2VudC5nZXQoJ2h0dHA6Ly9sb2NhbGhvc3Q6NDAw
MC8nICsgcmFuZG9tKS5lbmQoZnVuY3Rpb24oZXJyLCByZXMpIHsKCiAgICAgICAgICAgaWYgKGVy
cikgeyByZXR1cm4gZG9uZShlcnIpOyB9CiAgICAgICAgICAgCiAgICAgICAgICAgIHRyeSB7CiAg
ICAgICAgICAgICAgICBhc3NlcnQuZXF1YWwocmVzLnN0YXR1cywgMjAwKTsKICAgICAgICAgICAg
ICAgIGFzc2VydC5lcXVhbChyZXMudGV4dCwgJ0hlbGxvICcgKyByYW5kb20pOwogICAgICAgICAg
ICB9IGNhdGNoIChlcnJvcikgewogICAgICAgICAgICAgICAgZXJyb3IubWVzc2FnZSA9ICJJTkZP
OiBQYXRoIC86bmFtZSBpcyBub3Qgd29ya2luZyEiOwogICAgICAgICAgICAgICAgcmV0dXJuIGRv
bmUoZXJyb3IpOwogICAgICAgICAgICB9CiAgICAgICAgICAgIGRvbmUoKTsKICAgICAgICB9KTsK
CiAgICB9KTsKCgogICAgaXQoJ3Nob3VsZCBub3QgZXhlY3V0ZSBhbGVydCgpJywgZnVuY3Rpb24o
IGRvbmUgKSB7CiAgICAgICAgdmFyIHJhbmRvbSA9IHJhbmRvbXN0cmluZy5nZW5lcmF0ZSgpOwog
ICAgICAgIHZhciBzdHJpbmcgPSAnPHN2Zy9vbmxvYWQ9YWxlcnQoIicgKyByYW5kb20gKyAnIik7
JzsKICAgICAgICBzdXBlcmFnZW50LmdldCgnaHR0cDovL2xvY2FsaG9zdDo0MDAwLycgKyBlbmNv
ZGVVUklDb21wb25lbnQoc3RyaW5nKSkuZW5kKGZ1bmN0aW9uKGVyciwgcmVzKSB7CgogICAgICAg
ICAgIGlmIChlcnIpIHsgcmV0dXJuIGRvbmUoZXJyKTsgfQoKICAgICAgICAgICAgdHJ5IHsKICAg
ICAgICAgICAgICAgIGFzc2VydC5lcXVhbChyZXMuc3RhdHVzLCAyMDApOwogICAgICAgICAgICAg
ICAgYXNzZXJ0Lm5vdEVxdWFsKHJlcy50ZXh0LCAnSGVsbG8gJyArIHN0cmluZyk7CiAgICAgICAg
ICAgIH0gY2F0Y2ggKGVycm9yKSB7CiAgICAgICAgICAgICAgICBlcnJvci5tZXNzYWdlID0gIklO
Rk86IFBhdGggLzpuYW1lIGlzIHZ1bG5lcmFibGUgdG8gWFNTISI7CiAgICAgICAgICAgICAgICBy
ZXR1cm4gZG9uZShlcnJvcik7CiAgICAgICAgICAgIH0KICAgICAgICAgICAgZG9uZSgpOwogICAg
ICAgIH0pOwoKICAgIH0pOwoKCn0pOwo=';

$unittest4 = 'RU5WWydSQUNLX0VOViddID0gJ3Rlc3QnCgpyZXF1aXJlICd1cmknIApyZXF1aXJlICdtaW5pdGVz
dC9hdXRvcnVuJwpyZXF1aXJlICdyYWNrL3Rlc3QnCnJlcXVpcmUgJ3NlY3VyZXJhbmRvbScKcmVx
dWlyZSAndGVzdC91bml0JwpyZXF1aXJlX3JlbGF0aXZlICcuL3NyYycKIApjbGFzcyBNYWluQXBw
VGVzdCA8IFRlc3Q6OlVuaXQ6OlRlc3RDYXNlCiAgaW5jbHVkZSBSYWNrOjpUZXN0OjpNZXRob2Rz
IAogCiAgZGVmIGFwcAogICAgU2luYXRyYTo6QXBwbGljYXRpb24KICBlbmQKIAogIGRlZiB0ZXN0
X21haW5fcGFnZQogICAgZ2V0ICcvJwogICAgYXNzZXJ0X2VxdWFsKGxhc3RfcmVzcG9uc2UuYm9k
eSwgJ0hlbGxvIFdvcmxkJywgIklORk86IFBhdGggJy8nIGlzIG5vdCB3b3JraW5nIHByb3Blcmx5
LiIpCiAgZW5kCgoKICBkZWYgdGVzdF9wYXJhbQogICAgcmFuZG9tX3N0cmluZyA9IFNlY3VyZVJh
bmRvbS5oZXgKICAgIGdldCAnLycgKyByYW5kb21fc3RyaW5nCiAgICBhc3NlcnQgbGFzdF9yZXNw
b25zZS5vaz8KICAgIGFzc2VydF9lcXVhbChsYXN0X3Jlc3BvbnNlLmJvZHksICdIZWxsbyAnICsg
cmFuZG9tX3N0cmluZywgJ0lORk86IFBhdGggLzpuYW1lIGlzIG5vdCB3b3JraW5nIHByb3Blcmx5
JykKICBlbmQKCgogIGRlZiB0ZXN0X3hzcwogICAgeHNzID0gJzxzdmclMjBvbmxvYWQ9YWxlcnQo
NSk+JwogICAgZ2V0ICcvJyArIFVSSTo6ZW5jb2RlKHhzcykKICAgIGFzc2VydCBsYXN0X3Jlc3Bv
bnNlLm9rPwogICAgYXNzZXJ0X25vdF9lcXVhbChsYXN0X3Jlc3BvbnNlLmJvZHksICdIZWxsbyAn
ICsgeHNzLCAnSU5GTzogUGF0aCAvOm5hbWUgaXMgdnVsbmVyYWJsZSB0byBYU1MnKQogIGVuZAoK
ZW5kCg==';

$query = "INSERT INTO unittests (challenge_id, unittest) VALUES ('1', '".$unittest1."'), ('2', '".$unittest2."'), ('3', '".$unittest3."'), ('4', '".$unittest4."')";

if (mysqli_query($conn, $query)) {
        echo "[+] Inserted data new table unittests. \n";
        echo "<br><br>\n";
} else {
        echo "[+] Error insert into unittest: " . mysqli_error($conn);
        echo "<br><br>\n";
}
