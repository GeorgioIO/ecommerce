<?php

require __DIR__ . '/../../configuration/session.php';

header("Content-Type: application/json");


if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}


require_once __DIR__ . '/../../configuration/database.php';
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
        oi.book_id,
        b.title,
        oi.quantity,
        oi.selling_price,
        oi.total_line_price
    FROM order_items oi
    JOIN books b ON oi.book_id = b.id
    WHERE oi.order_id = ?;
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

$order_lines = [];

while($row = $result->fetch_assoc())
{
    $order_lines[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($order_lines);
exit;



?>