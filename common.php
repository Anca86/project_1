<?php
session_start();
require_once('config.php');

$servername = _DB_HOST;
$username = _DB_USER;
$password = _DB_PASSWORD;
$dbname = _DB_DATABASE;

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conection error: " . $conn->connect_error);
}

$sql = "SELECT * FROM productsnew";

function translate($string) {
	return $string;
}

function test_user_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}



