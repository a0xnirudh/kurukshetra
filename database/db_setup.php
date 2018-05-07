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

$query = "INSERT INTO challenges (name, code, intro, instructions, reference, type, language, timestamp) VALUES ('$name','$code','$intro','$instruction','$reference', 'xss', 'php', '$date')";

$name = s("Python - Rendering is fun !");
$code = 'ZnJvbSBmbGFzayBpbXBvcnQgRmxhc2sKZnJvbSBmbGFzayBpbXBvcnQgcmVxdWVzdApmcm9tIGZs
YXNrIGltcG9ydCBSZXNwb25zZQpmcm9tIGZsYXNrIGltcG9ydCByZW5kZXJfdGVtcGxhdGVfc3Ry
aW5nCgoKYXBwID0gRmxhc2soX19uYW1lX18pCmRhdGEgPSB7fQoKQGFwcC5yb3V0ZSgnLycpCmRl
ZiBoZWxsbygpOgogICAgcmV0dXJuIFJlc3BvbnNlKCJIZWxsbyBXb3JsZCEiLCBtaW1ldHlwZT0n
dGV4dC9odG1sJykKCkBhcHAucm91dGUoJy88bmFtZT4nKQpkZWYgaGVsbG9fbmFtZShuYW1lLCBz
dHJpbmc9Tm9uZSk6CiAgICBkYXRhWyduYW1lJ10gPSBuYW1lCiAgICBkYXRhWydzZWNyZXQnXSA9
IHJlcXVlc3QuYXJncy5nZXQoJ3NlY3JldCcpCiAgICB0ZW1wbGF0ZSA9ICI8aDI+SGVsbG8gJXMh
PC9oMj4iICUgZGF0YVsnbmFtZSddCiAgICByZXR1cm4gcmVuZGVyX3RlbXBsYXRlX3N0cmluZyh0
ZW1wbGF0ZSwgZGF0YT1kYXRhKQoKCmlmIF9fbmFtZV9fID09ICdfX21haW5fXyc6CiAgICBhcHAu
cnVuKCkK';
$intro = s("Server-side template injection occurs when user-controlled input is embedded into a server-side template, allowing users to inject template directives. This allows an attacker to inject malicious template directives and possibly execute arbitrary code on the affected server.");
$instruction = s("Modify and submit the update code by patching the vulnerability but should retain the functionality of the existing code.");
$reference = s("These are sample reference links !!");
$date = date("Y-m-d H:i:s");

$query2 = "INSERT INTO challenges (name, code, intro, instructions, reference, type, language, timestamp) VALUES ('$name','$code','$intro','$instruction','$reference', 'ssti', 'python', '$date')";


if (mysqli_query($conn, $query)) {
    if (mysqli_query($conn, $query2)) {
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

$query = "INSERT INTO `languages` (`name`) VALUES ('php'), ('python')";

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
$unittest = 'PD9waHAKdXNlIFBIUFVuaXRcRnJhbWV3b3JrXFRlc3RDYXNlOwoKJGN1cnJlbnRfcGF0aCA9IHJl
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

$query = "INSERT INTO unittests (challenge_id, unittest) VALUES ('1', '$unittest')";

$unittest = 'aW1wb3J0IG9zCmltcG9ydCBzeXMKaW1wb3J0IHRpbWUKaW1wb3J0IHJhbmRvbQppbXBvcnQgaGFz
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

$query2 = "INSERT INTO unittests (challenge_id, unittest) VALUES ('2', '$unittest')";

if (mysqli_query($conn, $query)) {
    if (mysqli_query($conn, $query2)) {
        echo "[+] Inserted data new table unittests. \n";
        echo "<br><br>\n";
    }
} else {
        echo "[+] Error insert into unittest: " . mysqli_error($conn);
        echo "<br><br>\n";
}
