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

function translate($string) {
	return $string;
}

function test_user_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  return $data;
}

// $sql = "CREATE TABLE login_admin (
// 	id INT(6) NOT NULL AUTO_INCREMENT PRIMARY KEY,
// 	username VARCHAR(30) NOT NULL,
// 	password VARCHAR(30) NOT NULL
// 	)";

// if($conn->query($sql)) {
// 	echo "succes";
// } else {
// 	echo "error" . $conn->error;
// }

// $sql = "INSERT INTO login_admin (username, password) values ('admin', sha1('admin'))";
// $conn->query($sql);

// $sql = "UPDATE login_admin set password=sha1('admin') where id=2";
// $conn->query($sql);
