<?php

header('Content-Type: application/json');

require __DIR__ . '/../../configuration/session.php';



require_once  __DIR__ . '/../../configuration/database.php';
require_once __DIR__ . '/helpers/book_helpers.php';
require_once __DIR__ . '/helpers/book_db_helpers.php';
require_once __DIR__ . '/../helpers.php';

$page = $_GET['page'] ?? 1;
$perPage = $_GET['perPage'] ?? 10;
$page = max(1 , (int) $page);
$perPage = min(50 , max(5 , $perPage));
$offset = ($page - 1) * $perPage;

$finalPriceExpression = "
CASE
    WHEN b.is_onSale = 1
        THEN ROUND(b.price - (b.price * b.discount_percentage) / 100 , 2)
    ELSE
        b.price
END
";

// For dynamic filterings
$filters = [];
$params = [];

$types = "";

if(isset($_GET['stock']))
{
    $filters[] = "b.is_inStock = ?";
    $params[] = (int) $_GET['stock'];
    $types .= "i"; 
}

if(isset($_GET['title']))
{
    $filters[] = "b.title LIKE ?";
    $title_search = '%' . $_GET['title'] . '%';
    $params[] = $title_search;
    $types .= "s"; 
}

if(isset($_GET['minPrice']))
{
    $filters[] = "$finalPriceExpression >= ?";
    $params[] = (float) $_GET['minPrice'];
    $types .= "d";
}

if(isset($_GET['maxPrice']))
{
    $filters[] = "$finalPriceExpression <= ?";
    $params[] = (float) $_GET['maxPrice'];
    $types .= "d";
}

if(isset($_GET['author']))
{
    $filters[] = "b.author_id = ?";
    $params[] = (int) $_GET['author'];
    $types .= "i";
}

if(isset($_GET['genre']))
{
    $filters[] = "b.genre_id = ?";
    $params[] = (int) $_GET['genre'];
    $types .= "i";
}

if(isset($_GET['format']))
{
    $filters[] = "b.format_id = ?";
    $params[] = (int) $_GET['format'];
    $types .= "i";
}

if(isset($_GET['language']))
{
    $filters[] = "b.language = ?";
    $params[] =  $_GET['language'];
    $types .= "s";
}

// Build the where clause
$where_sql = "";
if(count($filters) > 0)
{
    $where_sql = "WHERE b.is_deleted = 0 AND " . implode(" AND ", $filters);
}

// Sorting options
$sortOptions = [
    'alpha-a-z' => 'b.title ASC',
    'alpha-z-a' => 'b.title DESC',
    'low-to-high-price' => "$finalPriceExpression ASC",
    'high-to-low-price' => "$finalPriceExpression DESC",
    'old-to-new-date' => 'b.id ASC',
    'new-to-old-date' => 'b.id DESC'
];

$order_by = $sortOptions[$_GET['sortOption'] ?? 'alpha-a-z'];

// Main query
$query = <<<SQL
    SELECT 
        b.id,
        b.isbn,
        b.sku,
        g.name AS genre_title,
        b.title,
        b.author_id,
        a.name AS author_name,
        bf.name AS format_name,
        b.description,
        b.slug,
        b.language,
        b.stock_quantity,
        b.is_inStock,
        b.cover_image,
        b.price,
        b.is_onSale,
        b.discount_percentage,
        CASE WHEN w.book_id IS NOT NULL THEN 1 ELSE 0 END AS is_inWishlist,
        CASE WHEN b.is_onSale = 1 THEN ROUND(b.price - (b.price * b.discount_percentage) / 100 , 2) ELSE b.price END AS final_price,
        CASE WHEN ci.book_id IS NOT NULL THEN 1 ELSE 0 END AS is_inCart  
    FROM books b
    LEFT JOIN genres g ON b.genre_id = g.id
    LEFT JOIN authors a ON b.author_id = a.id
    LEFT JOIN book_formats bf ON b.format_id = bf.id
    LEFT JOIN wishlist_items w ON b.id = w.book_id AND w.user_id = ?
    LEFT JOIN carts c ON c.user_id = ? AND c.status = 'active' 
    LEFT JOIN cart_items ci ON b.id = ci.book_id AND ci.cart_id = c.id
    $where_sql
    ORDER BY $order_by
    LIMIT ? OFFSET ?;
SQL;

// Filtering params
$filters_params = $params;
$fitlers_types = $types;


if(isset($_SESSION['user_id']))
{
    // User is logged in we use his id
    array_unshift($params , (int) $_SESSION['user_id']);
    array_unshift($params , (int) $_SESSION['user_id']);
}
else
{
    // User is not logged in we use 0 (no one id)
    array_unshift($params , 0);
    array_unshift($params , 0);
}

$types = "ii" . $types;

// Pagination params
$params[] = $perPage;
$params[] = $offset;
$types .= "ii";

$datastmt = $conn->prepare($query);
if($types)
{
    $datastmt->bind_param($types , ...$params);
}
$datastmt->execute();
$result = $datastmt->get_result();

// Collect rows
$books = [];
if($result && $result->num_rows > 0)
{
    while($row = $result->fetch_assoc())
    {
        $books[] = $row;
    }
}

$count_query = "SELECT COUNT(*) AS total_books FROM books b $where_sql";
$count_stmt = $conn->prepare($count_query);
if($fitlers_types){
    $count_stmt->bind_param($fitlers_types , ...$filters_params);
}
$count_stmt->execute();
$total_books = $count_stmt->get_result()->fetch_assoc()['total_books'];


$pagination =  [
    'page' => $page,
    'perPage' => $perPage,
    'total' => $total_books,
    'totalPages' => ceil($total_books / $perPage)
] ;



$conn->close();
$datastmt->close();
$count_stmt->close();

echo json_encode([
    'success' => true,
    'data' => $books,
    'pagination' => $pagination
]);


?>

