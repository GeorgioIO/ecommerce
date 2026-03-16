<?php

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === "GET")
{
    require_once  __DIR__ . '/../../configuration/database.php';
    require_once  __DIR__ . '/validators/author_validators.php';


    $id = $_GET["id"] ?? null;

    $author_id_result = validate_author_id($id);
    if(!$author_id_result['success'])
    {
        echo json_encode([
            'success' => false,
            'status' => 400,
            'message' => $author_id_result['message']
        ]);
        exit;
    }

    $author_id = $author_id_result['value'];

    $query = <<<EOT
        SELECT 
            id,
            name
        FROM
            authors 
        WHERE id = ? AND is_deleted = 0
    EOT;

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $author_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows === 0){
        echo json_encode([
            'success' => false,
            'status' => 404, 
            'data' => 'Author not found'
        ]);
        exit;
    }

    $author = $result->fetch_assoc();

    $stmt->close();
    $conn->close();

    echo json_encode([
        'success' => true,
        'status' => 200,
        'data' => $author
    ]);
    exit;
}
else
{
    echo json_encode([
        'success' => false,
        'status' => 400,
        'message' => 'Wrong method used.'
    ]);
    exit;
}


?>
