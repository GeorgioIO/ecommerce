<?php

require __DIR__ . '/../../configuration/session.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

require_once  __DIR__ .  '/../../configuration/database.php';
require_once  __DIR__ . '/../helpers.php';
require_once  __DIR__ . '/validators/genre_db_validators.php';

$genre_id = $_POST["id"] ?? null;

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

// sanitize genre id
$DB_genre_id = trim($genre_id);
$DB_genre_id = (int) $DB_genre_id;

$genre_has_books_validation = DB_validate_genre_has_books($conn , $DB_genre_id);
if(!$genre_has_books_validation['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $genre_has_books_validation['message']
    ]);
    exit;
}


$query = "DELETE FROM genres WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i" , $DB_genre_id);

if($stmt->execute())
{
    $response = ['success' => true , 'message' => "Genre #$DB_genre_id is deleted successsfully!"];
}
else
{
    $response = ['success' => false , 'message' => 'Problem in deleting genre'];
}

$stmt->close();
$conn->close();

echo json_encode($response);
exit;

?>