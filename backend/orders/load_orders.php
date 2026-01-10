<?php

header("Content-Type: application/json");
require_once __DIR__ . '/../../config/database.php';

$query = <<<EOT
SELECT
    o.id,
    o.order_code,
    u.name,
    u.email,
    u.phone_number,
    o.status,
    o.total_price,
    o.date_added
FROM
    orders o
JOIN users u ON o.user_id = u.id 
EOT;

$orders = [];

$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0)
{
    while($row = $result->fetch_assoc())
    {
        $orders[] = $row;
    }
}

echo json_encode($orders);

$conn->close();
$stmt->close();
exit;
?>