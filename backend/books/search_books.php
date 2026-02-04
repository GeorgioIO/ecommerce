<?php

header('Content-Type: application/json');

require_once  __DIR__ . '/../../configuration/database.php';

$search_input = $_POST['value'];

$query = <<<SQL
        SELECT
            b.id,
            b.isbn,
            b.sku,
            b.title,
            b.price,
            b.cover_image,
            a.name AS author_name,
            bf.name AS format_name,
            g.name AS genre_name,
            b.is_onSale,
            b.is_inStock,
            b.discount_percentage,
            CASE 
                WHEN b.is_onSale = 1
                    THEN ROUND(b.price - (b.price * b.discount_percentage) / 100 , 2)
                ELSE
                    b.price
            END AS final_price
        FROM books b
        JOIN genres g ON b.genre_id = g.id
        JOIN authors a ON b.author_id = a.id
        JOIN book_formats bf ON b.format_id = bf.id
        WHERE 
            b.title LIKE ?
            OR a.name LIKE ?
            OR g.name LIKE ?
            OR b.isbn LIKE ?
            OR b.sku LIKE ?
        ORDER BY b.title ASC;

SQL;

$search_input = '%' . $search_input . '%';

$stmt = $conn->prepare($query);
$stmt->bind_param("sssss" , $search_input , $search_input , $search_input , $search_input , $search_input );
$stmt->execute();
$result = $stmt->get_result();

$search_results = [];

if($result)
{
    while($row = $result->fetch_assoc())
    {
        $search_results[] = $row;
    }
}

$stmt->close();
$conn->close();

echo json_encode($search_results);
exit;

?>