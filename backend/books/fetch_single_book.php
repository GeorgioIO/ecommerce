<?php

header('Content-Type: application/json');
require_once  __DIR__ . '/../../config/database.php';
require_once  __DIR__ . '/../validators/book_validators.php';


$id = $_POST["id"] ?? null;

// validate book id
$book_id_result = validate_book_id($id);
if(!$book_id_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $book_id_result['message']
    ]);
    exit;
}

$DB_book_id = $book_id_result['value'];

$query = <<<EOT

SELECT 
    id,
    isbn,
    sku,
    title,
    description,
    language,
    stock_quantity,
    cover_image,
    price,
    genre_id,
    author_id,
    format_id
FROM
    books 
WHERE id = ?
EOT;

$stmt = $conn->prepare($query);
$stmt->bind_param("i" , $DB_book_id);
$stmt->execute();

$result = $stmt->get_result();

// id isnt found in database
if($result->num_rows === 0){
    echo json_encode([
        'success' => false, 
        'data' => 'Book not found'
    ]);
    exit;
}

$book = $result->fetch_assoc();

$stmt->close();
$conn->close();

echo json_encode([
    'success' => true,
    'data' => $book
]);
exit;

?>
