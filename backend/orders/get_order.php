<?php

require __DIR__ . '/../../configuration/session.php';
require_once __DIR__ . '/../helpers.php';

header("Content-Type: application/json");

if (!isset($_SESSION['admin_id'])) {
    respond(false , 401 , null , null , 'Not authorized to use api');
}

if($_SERVER['REQUEST_METHOD'] === "GET")
{
    
    require_once __DIR__ . '/../../configuration/database.php';
    require_once __DIR__ . '/validators/order_validators.php';

    $id = $_GET['id'] ?? null;

    $order_id_validation = validate_entity_ID($id);
    if(!$order_id_validation['valid'])
    {
        respond(false , 400 , null , null , $order_id_validation['message']);  
    }


    $query = <<<EOT
        SELECT
            o.id,
            o.order_code,
            o.status,
            o.total_price,
            o.date_added,
            DATE_FORMAT(o.date_added , '%m-%d-%Y') AS display_date,
            o.user_id,
            u.role,
            u.email,
            u.name as customer_name
        FROM orders o
        JOIN users u ON o.user_id = u.id
        WHERE o.id = ?;
    EOT;

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 0)
    {
        respond(false , 404 , null , null , 'Order not found');
    }

    $order = $result->fetch_assoc();

    respond(true , 200 , $order , null , null);
}
else
{
    respond(false , 400 , null , null , 'Wrong method used');
}



?>