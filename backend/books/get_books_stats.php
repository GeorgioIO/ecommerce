<?php

require_once __DIR__ . '/../helpers.php';
header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === 'GET')
{
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

    $data = [
        'books_count' => $row['books_count'],
        'in_stock' => $row['in_stock_count'],
        'out_of_stock' => $row['out_of_stock_count']
    ];


    respond(true , 200 , $data , null , null);
}
else
{
    respond(false , 400 , null , null , 'Wrong method used in getting book');
}


?>