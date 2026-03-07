<?php

header('Content-Type: application/json');

require_once  __DIR__ . '/../../configuration/database.php';
require_once __DIR__ . '/../../configuration/session.php';

$search_input = $_POST['value'];

$user_id = 0;

if(isset($_SESSION['user_id']))
{
    $user_id = $_SESSION['user_id'];
}

$query = <<<SQL
        SELECT
            b.id AS book_id,
            b.isbn,
            b.sku,
            b.title,
            b.price,
            b.cover_image,
            a.name AS author_name,
            bf.name AS format_name,
            g.name AS genre_name,
            b.is_onSale,
            b.slug,
            b.is_inStock,
            b.discount_percentage,
            CASE WHEN wi.book_id IS NOT NULL THEN 1 ELSE 0 END AS is_inWishlist,
            CASE WHEN ci.book_id IS NOT NULL THEN 1 ELSE 0 END AS is_inCart,
            CASE WHEN b.is_onSale = 1 THEN ROUND(b.price - (b.price * b.discount_percentage) / 100 , 2) ELSE b.price END AS final_price
        FROM books b
        JOIN genres g ON b.genre_id = g.id
        JOIN authors a ON b.author_id = a.id
        JOIN book_formats bf ON b.format_id = bf.id
        LEFT JOIN wishlist_items wi ON b.id = wi.book_id AND wi.user_id = ?
        LEFT JOIN carts c ON c.user_id = ? AND c.status = 'active'
        LEFT JOIN cart_items ci ON c.id = ci.cart_id AND ci.book_id = b.id
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
$stmt->bind_param("iisssss" , $user_id , $user_id , $search_input , $search_input , $search_input , $search_input , $search_input );
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