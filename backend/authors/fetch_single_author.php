<?php

header('Content-Type: application/json');
require_once  __DIR__ . '/../../config/database.php';
require_once  __DIR__ . '/../validators/author_validators.php';


$id = $_POST["id"] ?? null;

// validate author id
$author_id_result = validate_author_id($id);
if(!$author_id_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $author_id_result['message']
    ]);
    exit;
}

$DB_author_id = $author_id_result['value'];

$query = <<<EOT

SELECT 
    id,
    name
FROM
    authors 
WHERE id = ?
EOT;

$stmt = $conn->prepare($query);
$stmt->bind_param("i" , $DB_author_id);
$stmt->execute();

$result = $stmt->get_result();

// id isnt found in database
if($result->num_rows === 0){
    echo json_encode([
        'success' => false, 
        'data' => 'Author not found'
    ]);
    exit;
}

$author = $result->fetch_assoc();

$stmt->close();
$conn->close();

echo json_encode([
    'success' => true,
    'data' => $author
]);
exit;

?>
