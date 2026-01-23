<?php

header("Content-Type: application/json");
require_once __DIR__ . '/../../config/database.php';

$result = $conn->query("
SELECT
        o.order_code,
        u.name AS customer_name,
        o.total_price,
        o.status,
        o.date_added,
        DATE_FORMAT(o.date_added, '%m-%d-%Y') AS display_date,
        CASE
            WHEN TIMESTAMPDIFF(MINUTE , o.date_added , NOW()) < 60
                THEN CONCAT(TIMESTAMPDIFF(MINUTE ,o.date_added , NOW()), ' minutes ago')
            WHEN TIMESTAMPDIFF(HOUR , o.date_added , NOW()) < 24
                THEN CONCAT(TIMESTAMPDIFF(HOUR , o.date_added , NOW()) , ' hours ago')
            ELSE 
                CONCAT(TIMESTAMPDIFF(DAY , o.date_added , NOW()) , ' days ago')
        END AS time_ago
    FROM
        orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.date_added  DESC
    LIMIT 5;

");

$recent_five_orders = [];

if($result->num_rows > 0)
{
    while($row = $result->fetch_assoc())
    {
        $recent_five_orders[] = $row;
    }
}

echo json_encode($recent_five_orders);

$conn->close();
exit;
?>