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
$stmt->bind_param("i" , $DB_order_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0)
{
    echo json_encode([
        'success' => false,
        'message' => 'Address not found'
    ]);
    exit;
}

$address = $result->fetch_assoc();

$stmt->close();
$conn->close();

echo json_encode($address);
exit;



?>