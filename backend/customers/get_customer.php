<?php

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === 'GET')
{
    require_once  __DIR__ . '/../../configuration/database.php';
    require_once  __DIR__ . '/validators/customer_validators.php';
    require_once __DIR__ . '/../helpers.php';

    $id = $_GET['id'] ?? null;

    $customer_id_validation = validate_entity_ID($id);
    if(!$customer_id_validation['valid'])
    {
        respond(false , 400 , null , null , $customer_id_validation['message']);
    }

    $query = <<<EOT
        SELECT
            u.id,
            u.customer_code,
            u.name,
            u.email,
            u.phone_number,
            u.date_added,
            u.password,
            COALESCE(SUM(o.total_price), 0) AS total_spent,
            COUNT(o.id) AS total_orders
        FROM users u
        LEFT JOIN orders o ON u.id = o.user_id
        WHERE u.id = ?
        GROUP BY u.id
    EOT;

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows === 0){
        respond(false , 404 , null , null , 'Customer not found');
    }

    $customer = $result->fetch_assoc();

    $stmt->close();
    $conn->close();

    respond(true , 200 , $customer , null , null);
}
else
{
    respond(false , 400 , null , null , 'Wrong method used');
}

?>
