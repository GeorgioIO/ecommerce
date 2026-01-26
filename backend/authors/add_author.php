<?php

header('Content-Type: application/json');

session_start();

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

require_once __DIR__ .  '/../../config/database.php';
require_once __DIR__ . '/../../config/helpers.php';
require_once __DIR__ . '/validators/author_validators.php';
require_once  __DIR__ . '/validators/author_db_validators.php';
require_once  __DIR__ . '/helpers/author_helpers.php';

$author_payload = extract_author_payload($_POST);

// Validation of data
$author_name_result = validate_author_name($author_payload['name']);
if(!$author_name_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $author_name_result['message']
    ]);
    exit;
}

$DB_author_name = $author_name_result['value'];

// Validate DB name uniqueness
$DB_name_validation_result = DB_validate_author_name($conn , $DB_author_name);
if(!$DB_name_validation_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $DB_name_validation_result['message']
    ]);
    exit;
}

$query = <<<EOT

INSERT INTO authors
(name)
VALUES
(?);

EOT;

$stmt = $conn->prepare($query);

$stmt->bind_param("s" , $DB_author_name);

if($stmt->execute()){
    $response = ['success' => true , 'message' => 'New author is added!'];
}
else
{
    $response = ['success' => false , 'message' => 'Problem in adding author'];
}

$stmt->close();
$conn->close();

echo json_encode($response);
exit;

?>