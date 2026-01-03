<?php

header('Content-Type: application/json');
require_once  __DIR__ . '/../../config/database.php';

$query = <<<EOT
    SELECT 
        b.id,
        b.isbn,
        b.sku,
        g.name AS genre_title,
        b.title,
        a.name AS author_name,
        bf.name AS format,
        b.description,
        b.language,
        b.stock_quantity,
        b.cover_image,
        b.price
    FROM books b
    LEFT JOIN genres g ON b.genre_id = g.id
    LEFT JOIN authors a ON b.author_id = a.id
    LEFT JOIN book_formats bf ON b.format_id = bf.id
    ORDER BY b.id;
EOT;


$result = $conn->query($query);
$books = [];

if($result && $result->num_rows > 0)
{
    while($row = $result->fetch_assoc())
    {
        $books[] = $row;
    }
}


echo json_encode($books);
$conn->close();

?>

