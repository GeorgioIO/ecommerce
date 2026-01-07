<?php

header('Content-Type: application/json');
require_once  __DIR__ . '/../../config/database.php';

$query = <<<EOT

SELECT
    u.id,
    u.customer_code,
    u.name,
    u.email,
    u.phone_number,
    u.date_added,
    COALESCE(SUM(o.total_price), 0) AS total_spent,
    COUNT(o.id) AS total_orders
FROM users u
LEFT JOIN orders o ON u.id = o.user_id
GROUP BY u.id
EOT;

$result = $conn->query($query);

$customers = [];

if($result && $result->num_rows > 0)
{
    while($row = $result->fetch_assoc())
    {
        $customers[] = $row;
    }
}

echo json_encode($customers);
$conn->close();


?>