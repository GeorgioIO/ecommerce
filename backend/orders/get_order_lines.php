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
    require_once __DIR__ . '/validators/order_validators.php';

    $id = $_GET['id'] ?? null;

    $order_id_validation = validate_entity_ID($id);
    if(!$order_id_validation['valid'])
    {
        respond(false , 400 , null , null , $order_id_validation['message']);
    }

    $query = <<<EOT
        SELECT
            oi.book_id,
            b.cover_image,
            b.title,
            oi.quantity,
            oi.selling_price,
            oi.total_line_price
        FROM order_items oi
        JOIN books b ON oi.book_id = b.id
        WHERE oi.order_id = ?;
    EOT;

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 0)
    {
       respond(false , 404 , null , null , 'Order not found');
    }

    $order_lines = [];

    while($row = $result->fetch_assoc())
    {
        $order_lines[] = $row;
    }

    $stmt->close();
    $conn->close();

    respond(true , 200 , $order_lines , null , null);
}
else
{
    respond(false , 400 , null , null , 'Wrong method used');
}

?>