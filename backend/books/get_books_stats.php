<?php

require __DIR__ . '/../../configuration/session.php';

header('Content-Type: application/json');


require_once  __DIR__ .  "/../../configuration/database.php";

$query = <<<EOT

SELECT
    COUNT(*) AS books_count,
    SUM(CASE WHEN is_InStock = 1 THEN 1 ELSE 0 END) AS in_stock_count,
    SUM(CASE WHEN is_InStock = 0 THEN 1 ELSE 0 END) AS out_of_stock_count
FROM books
WHERE is_deleted = 0;
EOT;

$result = $conn->query($query);
$row = $result->fetch_assoc();

echo json_encode([
    'books_count' => $row['books_count'],
    'in_stock' => $row['in_stock_count'],
    'out_of_stock' => $row['out_of_stock_count']
]);
$conn->close();

?>