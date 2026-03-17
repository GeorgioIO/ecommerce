<?php

require_once __DIR__ . '/../../configuration/session.php';
require_once __DIR__ . '/../helpers.php';

header("Content-Type: application/json");

if (!isset($_SESSION['admin_id'])) {
    respond(false , 401 , null ,null , 'Not authorized to use api');
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
            o.address_id,
            sa.first_name,
            sa.last_name,
            sa.phone_number,
            sa.email,
            sa.state,
            sa.city,
            sa.address_line1,
            sa.address_line2,
            sa.additional_notes,
            sa.admin_made
        FROM orders o
        JOIN shipping_addresses sa ON o.address_id = sa.id
        WHERE o.id = ?;
    EOT;

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 0)
    {
        respond(false , 404 , null , null , 'Address not found');
    }

    $address = $result->fetch_assoc();

    $stmt->close();
    $conn->close();

    respond(true , 200 , $address , null , null);
}
else
{
    respond(false , 400 , null , null , 'Wrong method used');
}

?>