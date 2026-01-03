<?php

header('Content-Type: application/json');
require_once  __DIR__ .  '/../../config/database.php';
require_once  __DIR__ . '/../../config/helpers.php';

$book_id = $_POST["id"] ?? null;

// validate id
$validation_result = validate_entity_ID($book_id);

if($validation_result['valid'] === false)
{
    echo json_encode([
        'success' => false,
        'message' => $validation_result['message']
    ]);
    exit;
}

// sanitize book id
$DB_book_id = trim($book_id);
$DB_book_id = (int) $DB_book_id;

$query = "DELETE FROM books WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i" , $DB_book_id);

if($stmt->execute())
{
    $response = ['success' => true , 'message' => "Book #$DB_book_id is deleted successsfully!"];
}
else
{
    $response = ['success' => false , 'message' => 'Problem in deleting book'];
}

$stmt->close();
$conn->close();

echo json_encode($response);
exit;

?>