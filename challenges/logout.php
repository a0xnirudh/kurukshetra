<?php

session_destroy(); //invalidating session
session_start(); //setting new session
unset($_SESSION); //clearing session variable values if any
?>

<?php
header('Location: home.php'); //displaying login form
?>