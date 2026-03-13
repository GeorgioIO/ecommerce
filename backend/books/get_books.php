<?php

header('Content-Type: application/json');

require __DIR__ . '/../../configuration/session.php';



require_once  __DIR__ . '/../../configuration/database.php';
require_once __DIR__ . '/helpers/book_helpers.php';
require_once __DIR__ . '/helpers/book_db_helpers.php';
require_once __DIR__ . '/../helpers.php';

$hasPagination = isset($_GET['page']) && isset($_GET['perPage']);
$paginationText = $hasPagination ? " LIMIT ? OFFSET ?" : "";
$author_id = $_GET['author_id'] ?? null; // 3
$genre_id = $_GET['genre_id'] ?? null; // null
$is_deleted = $_GET['is_deleted'] ?? 0;

$filters = [
    'author' => $author_id,
    'genre' => $genre_id,
    'pagination' => $paginationText
];

$params = [$is_deleted];
$types = "i";

// ! Query source 
$query = form_load_books_query($filters);

$data_stmt = $conn->prepare($query);

if(!$author_id && !$genre_id)
{
    $count_query = "SELECT COUNT(*) AS total_books FROM books WHERE is_deleted = ?";
    $count_stmt = $conn->prepare($count_query);
    $count_stmt->bind_param("i" , $is_deleted);
    $count_stmt->execute();
    $result = $count_stmt->get_result();
    $total_books = $result->fetch_assoc()['total_books'];

}
else if($author_id)        
{   
    $author_id = trim($author_id);

    $validation_result = validate_entity_ID($author_id);

    if(!$validation_result['valid'])
    {
        echo json_encode([
            'success' => false,
            'status' => 400,
            'message' => $validation_result['message']
        ]);
        exit;
    }

    $author_id = (int) $author_id;
    $params[] = $author_id;
    $types .= 'i';

    $count_query = "SELECT COUNT(*) AS total_books FROM books WHERE author_id = ? AND is_deleted = ?";
    $count_stmt = $conn->prepare($count_query);
    $count_stmt->bind_param("ii" , $author_id , $is_deleted);
    $count_stmt->execute();
    $result = $count_stmt->get_result();
    $total_books = $result->fetch_assoc()['total_books'];

    
}
elseif ($genre_id)
{
    $genre_id = trim($genre_id);

    $validation_result = validate_entity_ID($genre_id);

    if($validation_result['valid'] === false)
    {
        echo json_encode([
            'success' => false,
            'status' => 400,
            'message' => $validation_result['message']
        ]);
        exit;
    }

    $genre_id = (int) $genre_id;
    $params[] = $genre_id;
    $types .= 'i';

    $count_query = "SELECT COUNT(*) AS total_books FROM books WHERE genre_id = ? AND is_deleted = ?";
    $count_stmt = $conn->prepare($count_query);
    $count_stmt->bind_param("ii" , $genre_id , $is_deleted);
    $count_stmt->execute();
    $result = $count_stmt->get_result();
    $total_books = $result->fetch_assoc()['total_books'];
}

if($hasPagination)
{
    $page = $_GET['page'] ?? 1;
    $perPage = $_GET['perPage'] ?? 10;

    $page = max(1 , (int) $page);
    $perPage = min(50 , max(5 , $perPage));
    $offset = ($page - 1) * $perPage;

    $params[] = $perPage;
    $params[] = $offset;
    $types .= "ii";
}

$data_stmt->bind_param($types , ...$params);
$data_stmt->execute();
$result = $data_stmt->get_result();
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
$data_stmt->close();
$count_stmt->close();

echo json_encode([
    'success' => true,
    'status' => 200,
    'data' => $books,
    'pagination' => $pagination
]);
exit;


?>

