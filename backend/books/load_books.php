<?php

header('Content-Type: application/json');
require_once  __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/book_helpers.php';
require_once __DIR__ . '/../../config/helpers.php';

$author_id = $_GET['author_id'] ?? null; // 3
$genre_id = $_GET['genre_id'] ?? null; // null


$query = form_load_books_query($author_id , $genre_id);

$stmt = $conn->prepare($query);

if($author_id)        
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

    $stmt = $conn->prepare($query);
    $stmt->bind_param('i' , $DB_author_id);
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

    $stmt = $conn->prepare($query);
    $stmt->bind_param('i' , $DB_genre_id);
}

$stmt->execute();

$result = $stmt->get_result();
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

