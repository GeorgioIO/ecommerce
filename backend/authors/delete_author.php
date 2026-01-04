<?php

header('Content-Type: application/json');
require_once  __DIR__ .  '/../../config/database.php';
require_once  __DIR__ . '/../../config/helpers.php';
require_once  __DIR__ . '/../validators/author_db_validators.php';

$author_id = $_POST["id"] ?? null;

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

// sanitize author id
$DB_author_id = trim($author_id);
$DB_author_id = (int) $DB_author_id;

$author_has_books_validation = DB_validate_author_has_books($conn , $DB_author_id);
if(!$author_has_books_validation['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $author_has_books_validation['message']
    ]);
    exit;
}

$query = "DELETE FROM authors WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i" , $DB_author_id);

if($stmt->execute())
{
    $response = ['success' => true , 'message' => "Author #$DB_author_id is deleted successsfully!"];
}
else
{
    $response = ['success' => false , 'message' => 'Problem in deleting author'];
}

$stmt->close();
$conn->close();

echo json_encode($response);
exit;

?>