<?php

header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';

// Top valuable customers : they are the customers that have the top revenue in our store meaning the sum of their orders (NOT CANCELLED OR REFUNDED)

$result = $conn->query("
    WITH most_valuable_customers AS (
        SELECT
            u.id,
            u.name,
            SUM(o.total_price) AS total_orders_revenue
        FROM
            users u
        LEFT JOIN orders o ON u.id = o.user_id
        WHERE u.role = 'Customer' AND o.status NOT IN ('Cancelled' , 'Refunded')
        GROUP BY u.name , u.id
        ORDER BY total_orders_revenue DESC
        LIMIT 5
    ) SELECT name , total_orders_revenue FROM most_valuable_customers ORDER BY  total_orders_revenue ASC;

");
$conn->close();

$valuable_customers = [];

if($result)
{
    while($row = $result->fetch_assoc())
    {
        $valuable_customers[] = $row;
    }
}

echo json_encode($valuable_customers);
exit;

?>




