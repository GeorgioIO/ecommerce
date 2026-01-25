<?php

header("Content-Type: application/json");
require_once __DIR__ . '/../../config/database.php';

$page = $_GET['page'] ?? 1;
$perPage = $_GET['perPage'] ?? 10;

$page = max(1 , (int) $page);
$perPage = min(50 , max(5 , (int) $perPage));
$offset = ($page - 1) * $perPage;

$query = <<<EOT
SELECT
    o.id,
    o.order_code,
    u.name,
    u.email,
    u.phone_number,
    o.status,
    o.total_price,
    o.date_added,
    DATE_FORMAT(o.date_added, '%m-%d-%Y') AS display_date
FROM
    orders o
JOIN users u ON o.user_id = u.id
LIMIT ? OFFSET ? 
EOT;

$orders = [];

$stmt = $conn->prepare($query);
$stmt->bind_param("ii" , $perPage , $offset);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0)
{
    while($row = $result->fetch_assoc())
    {
        $orders[] = $row;
    }
}

$result = $conn->query("SELECT COUNT(*) AS total_orders FROM orders");
$total_orders = $result->fetch_assoc()['total_orders'];

$conn->close();
$stmt->close();

echo json_encode([
    'success' => true,
    'data' => $orders,
    'pagination' => [
        'page' => $page,
        'perPage' => $perPage,
        'total' => $total_orders,
        'totalPages' => ceil($total_orders / $perPage)
    ]
]);

exit;
?>