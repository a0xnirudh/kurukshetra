<?php

if(isset($_GET['install']))
    die(header('Location: /installation/'));

header('Location: /login/index.php');