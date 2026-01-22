<?php

header("Content-Type: application/json");

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/validators/order_validators.php';

// Get ID
$id = $_POST['id'] ?? null;


// Validate ID
$order_id_result = validate_order_id($id);

if(!$order_id_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $order_id_result['message']
    ]);
    exit;   
}

$DB_order_id = $order_id_result['value'];

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
$stmt->bind_param("i" , $DB_order_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0)
{
    echo json_encode([
        'success' => false,
        'message' => 'Order not found'
    ]);
    exit;
}

$order = $result->fetch_assoc();

$stmt->close();
$conn->close();

echo json_encode($order);
exit;



?>