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


$query = "SELECT COUNT(id) AS order_count FROM orders";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$order_count = (int) $row['order_count'];

$stmt->close();
$conn->close();

return $order_count;

?>