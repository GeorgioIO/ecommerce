<?php

require __DIR__ . '/../../configuration/session.php';
require_once __DIR__ . '/../helpers.php';
header("Content-Type: application/json");

if (!isset($_SESSION['admin_id']) && !isset($_SESSION['user_id'])) {
    respond(false , 401 , null , null , 'Not authorized to use api');
}

if($_SERVER['REQUEST_METHOD'] === 'GET')
{    
    require_once __DIR__ . '/../../configuration/database.php';


    $query = "SELECT COUNT(id) AS order_count FROM orders";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $order_count = (int) $row['order_count'];

    respond(true , 200 , $order_count , null , null);
}
else
{
    respond(false , 400 , null , null , 'Wrong method used');
}

?>