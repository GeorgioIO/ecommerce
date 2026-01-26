<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';

// Top valuable customers : they are the customers that have the top revenue in our store meaning the sum of their orders (NOT CANCELLED OR REFUNDED)

$result = $conn->query("
        SELECT
            g.id,
            g.name,
            SUM(oi.total_line_price) AS total_orders_revenue
        FROM
            genres g
        JOIN books b ON g.id = b.genre_id
        LEFT JOIN order_items oi ON b.id = oi.book_id
        LEFT JOIN orders o ON o.id = oi.order_id
        GROUP BY g.id , g.name
        ORDER BY total_orders_revenue DESC
        LIMIT 5
");
$conn->close();

$most_selling_genres = [];

if($result)
{
    while($row = $result->fetch_assoc())
    {
        $most_selling_genres[] = $row;
    }
}

echo json_encode($most_selling_genres);
exit;

?>




