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


if(isset($_SESSION["lastname"])){
    unset($_SESSION["lastname"]);
}