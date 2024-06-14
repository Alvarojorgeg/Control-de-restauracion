<?php
#error_reporting(E_ALL); ini_set('display_errors', 1);
$dbhost	= "localhost";
$dbuser	= "root";
$dbpass	= "rootroot";
$dbname	= "proyecto";

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
