<?php

header("Content-Type: application/json");

require_once __DIR__ . '../../../configuration/session.php';
require_once __DIR__ . '../../../configuration/database.php';


// User logged in ?
if(!isset($_SESSION['user_id']))
{

    echo json_encode([
        'success' => false,
        'status' => 401,
        'message' => 'Please log in to use wishlist'
    ]);
    exit;
}

$query = "
    SELECT
        w.book_id,
        b.title,
        b.description,
        b.language,
        b.cover_image,
        b.price,
        b.slug,
        g.name AS genre_name,
        a.name AS author_name,
        bf.name AS format_name,
        b.is_inStock,
        b.is_onSale,
        b.discount_percentage,
        CASE WHEN b.is_onSale = 1 THEN ROUND(b.price - (b.price * b.discount_percentage) / 100 , 2) ELSE b.price END AS final_price,
        CASE WHEN ci.book_id IS NOT NULL THEN 1 ELSE 0 END AS is_inCart
    FROM wishlist_items w
    JOIN books b ON w.book_id = b.id
    JOIN authors a ON b.author_id = a.id
    JOIN genres g ON b.genre_id = g.id
    JOIN book_formats bf ON b.format_id = bf.id
    LEFT JOIN carts c ON c.user_id = ? AND c.status = 'active'
    LEFT JOIN cart_items ci ON c.id = ci.cart_id AND ci.book_id = w.book_id 
    WHERE w.user_id = ?
    ORDER BY b.title ASC
    
";

$params = [$_SESSION['user_id'] , $_SESSION['user_id']];
$types = "ii";

$hasPagination = isset($_GET['page']) && isset($_GET['perPage']);

if($hasPagination)
{
    $page = $_GET['page'] ?? 1;
    $perPage = $_GET['perPage'] ?? 5;

    $page = max(1 , (int) $page);
    $perPage = min(50 , max(5 , $perPage));
    $offset = ($page - 1) * $perPage;

    $params[] = $perPage;
    $params[] = $offset;
    $types .= "ii";

    $query .= "LIMIT ? OFFSET ?";
}



$stmt = $conn->prepare($query);
$stmt->bind_param($types , ...$params);
$stmt->execute();
$result = $stmt->get_result();

$wishlist_items = [];

if($result)
{
    while($row = $result->fetch_assoc())
    {
        $wishlist_items[] = $row;
    }
}

$query = "SELECT COUNT(*) AS items_count FROM wishlist_items WHERE user_id = ?";
$count_stmt = $conn->prepare($query);
$count_stmt->bind_param("i" , $_SESSION['user_id']);
$count_stmt->execute();
$wishlist_items_count = $count_stmt->get_result()->fetch_assoc()['items_count'];

$pagination = $hasPagination ? [
    'page' => $page,
    'perPage' => $perPage,
    'total' => $wishlist_items_count,
    'totalPages' => ceil($wishlist_items_count / $perPage)
] : null;

$count_stmt->close();
$stmt->close();
$conn->close();

echo json_encode([
    'success' => true,
    'status' => 200,
    'pagination' =>$pagination,
    'data' => $wishlist_items
]);
exit;

?>


