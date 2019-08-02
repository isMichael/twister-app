<?php
// Connect to the database
$mysqli = new mysqli("localhost", "username", "password", "username");

// Output error info if there was a connection problem
if ($mysqli->connect_errno)
    die("Failed to connect to MySQL: ($mysqli->connect_errno) $mysqli->connect_error");
?>
