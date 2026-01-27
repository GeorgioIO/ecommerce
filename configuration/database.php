<?php

$host = "localhost";
$username = "root";
$dbname = "booknest";
$password = "";
$port = 3307;

$conn = new mysqli($host , $username , $password , $dbname , $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

