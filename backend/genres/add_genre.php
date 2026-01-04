<?php

header('Content-Type: application/json');

require_once __DIR__ .  '/../../config/database.php';
require_once __DIR__ . '/../../config/helpers.php';
require_once __DIR__ . '/../validators/genre_validators.php';
require_once  __DIR__ . '/../validators/genre_db_validators.php';
require_once  __DIR__ . '/genre_helpers.php';


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
$DB_name_validation_result = DB_validate_genre_name($conn , $DB_genre_name);
if(!$DB_name_validation_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $DB_name_validation_result['message']
    ]);
    exit;
}

$DB_genre_filename = null;

if($genre_image_result['value'])
{
    $DB_genre_filename = upload_image($genre_image_result['value']);

    if($DB_genre_filename === false)
    {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to upload cover image'
        ]);
        exit;
    }
}

$query = <<<EOT

INSERT INTO genres
(name , image)
VALUES
(? , ?);

EOT;

$stmt = $conn->prepare($query);

$stmt->bind_param("ss" , $DB_genre_name , $DB_genre_filename);

if($stmt->execute()){
    $response = ['success' => true , 'message' => 'New genre is added!'];
}
else
{
    $response = ['success' => false , 'message' => 'Problem in adding genre'];
}

$stmt->close();
$conn->close();

echo json_encode($response);
exit;

?>