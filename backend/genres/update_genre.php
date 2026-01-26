<?php

require __DIR__ . '/../../config/session.php';


header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

require_once  __DIR__ . '/../../config/database.php';
require_once  __DIR__ . '/../../config/helpers.php';
require_once  __DIR__ . '/validators/genre_validators.php';
require_once  __DIR__ . '/validators/genre_db_validators.php';
require_once  __DIR__ . '/helpers/genre_helpers.php';

$genre_payload = extract_genre_payload($_POST , $_FILES);

// Validation of data
$genre_name_result = validate_genre_name($genre_payload['name']);
if(!$genre_name_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $genre_name_result['message']
    ]);
    exit;
}

$genre_image_result = validate_genre_image_file($genre_payload['image']);
if(!$genre_image_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $genre_image_result['message']
    ]);
    exit;
}

$DB_genre_name = $genre_name_result['value'];

// Validate DB name uniqueness
$DB_name_validation_result = DB_validate_genre_name($conn , $DB_genre_name , $genre_payload['id']);
if(!$DB_name_validation_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $DB_name_validation_result['message']
    ]);
    exit;
}

$DB_image_filename = null;

if($genre_image_result['value'])
{
    $DB_image_filename = upload_image($genre_image_result['value']);

    if($DB_image_filename === false)
    {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to upload image'
        ]);
        exit;
    }
}

if($DB_image_filename === null)
{
    $query = <<<EOT
        UPDATE genres SET
            name = ?
        WHERE id = ?
    EOT;
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "si", 
        $DB_genre_name , $genre_payload['id']);
}
else
{
    $query = <<<EOT
        UPDATE genres SET
            name = ?,
            image = ?
        WHERE id = ?
    EOT;
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "ssi", 
        $DB_genre_name , $DB_image_filename , $genre_payload['id']);
}

if($stmt->execute())
{
    $response = ['success' => true , 'message' => 'Genre is updated successfully!'];
}
else
{
    $response = ['success' => false , 'message' => 'Problem in updating genre'];
}

$stmt->close();
$conn->close();

echo json_encode($response);
exit;
?>