<?php

header('Content-Type: application/json');

session_start();

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

require_once  __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/helpers/book_helpers.php';
require_once __DIR__ . '/helpers/book_db_helpers.php';
require_once __DIR__ . '/../../config/helpers.php';

$hasPagination = isset($_GET['page']) && isset($_GET['perPage']);

$author_id = $_GET['author_id'] ?? null; // 3
$genre_id = $_GET['genre_id'] ?? null; // null

$params = [];
$types = "";

if($hasPagination)
{

    $page = $_GET['page'] ?? 1;
    $perPage = $_GET['perPage'] ?? 10;

    $page = max(1 , (int) $page);
    $perPage = min(50 , max(5 , $perPage));
    $offset = ($page - 1) * $perPage;

    $query = form_load_books_query($author_id , $genre_id , $perPage , $offset);

    $query .= " LIMIT ? OFFSET ?";

    $params[] = $perPage;
    $params[] = $offset;
    $types .= "ii";

}
else
{
    $query = form_load_books_query($author_id , $genre_id);
}

$datastmt = $conn->prepare($query);

if(!$author_id && !$genre_id)
{
    $countstmt = $conn->prepare("SELECT COUNT(*) AS total_books FROM books");
    $countstmt->execute();
    $total_books = $countstmt->get_result()->fetch_assoc()['total_books'];

    if($hasPagination)
    {
        $datastmt->bind_param("ii" , $perPage , $offset);
    }
}
else if($author_id)        
{   
    // validate id
    $validation_result = validate_entity_ID($author_id);

    if($validation_result['valid'] === false)
    {
        echo json_encode([
            'success' => false,
            'message' => $validation_result['message']
        ]);
        exit;
    }

    $DB_author_id = trim($author_id);
    $DB_author_id = (int) $DB_author_id;

    $countstmt = $conn->prepare("SELECT COUNT(*) AS total_books FROM books WHERE author_id = ?");
    $countstmt->bind_param("i" , $DB_author_id);
    $countstmt->execute();
    $total_books = $countstmt->get_result()->fetch_assoc()['total_books'];

    $datastmt->bind_param('iii' , $DB_author_id  , $perPage , $offset);
}
elseif ($genre_id)
{
// validate id
    $validation_result = validate_entity_ID($genre_id);

    if($validation_result['valid'] === false)
    {
        echo json_encode([
            'success' => false,
            'message' => $validation_result['message']
        ]);
        exit;
    }

    $DB_genre_id = trim($genre_id);
    $DB_genre_id = (int) $DB_genre_id;


    $countstmt = $conn->prepare("SELECT COUNT(*) AS total_books FROM books WHERE genre_id = ?");
    $countstmt->bind_param("i" , $DB_genre_id);
    $countstmt->execute();
    $total_books = $countstmt->get_result()->fetch_assoc()['total_books'];
    
    $datastmt->bind_param('iii' , $DB_genre_id  , $perPage , $offset);
}


$datastmt->execute();

$result = $datastmt->get_result();
$books = [];


// Collect rows
if($result && $result->num_rows > 0)
{
    while($row = $result->fetch_assoc())
    {
        $books[] = $row;
    }
}

$pagination = $hasPagination ? [
    'page' => $page,
    'perPage' => $perPage,
    'total' => $total_books,
    'totalPages' => ceil($total_books / $perPage)
] : null;



$conn->close();
$datastmt->close();
$countstmt->close();

echo json_encode([
    'success' => true,
    'data' => $books,
    'pagination' => $pagination
]);


?>

