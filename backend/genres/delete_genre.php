<?php

header('Content-Type: application/json');
require_once  __DIR__ .  '/../../config/database.php';
require_once  __DIR__ . '/../../config/helpers.php';

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