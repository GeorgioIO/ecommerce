<?php

require __DIR__ . '/../../configuration/session.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

require_once  __DIR__ . '/../../configuration/database.php';
require_once  __DIR__ . '/validators/genre_validators.php';


$id = $_POST["id"] ?? null;

// validate genre id
$genre_id_result = validate_genre_id($id);
if(!$genre_id_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $genre_id_result['message']
    ]);
    exit;
}

$DB_genre_id = $genre_id_result['value'];

$query = <<<EOT

SELECT 
    id,
    name,
    image
FROM
    genres 
WHERE id = ?
EOT;

$stmt = $conn->prepare($query);
$stmt->bind_param("i" , $DB_genre_id);
$stmt->execute();

$result = $stmt->get_result();

// id isnt found in database
if($result->num_rows === 0){
    echo json_encode([
        'success' => false, 
        'data' => 'Genre not found'
    ]);
    exit;
}

$genre = $result->fetch_assoc();

$stmt->close();
$conn->close();

echo json_encode([
    'success' => true,
    'data' => $genre
]);
exit;

?>
