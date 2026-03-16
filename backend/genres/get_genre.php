<?php


if($_SERVER['REQUEST_METHOD'] === "GET")
{
    header('Content-Type: application/json');

    require_once  __DIR__ . '/../../configuration/database.php';
    require_once  __DIR__ . '/validators/genre_validators.php';
    require_once __DIR__ . '/../helpers.php';

    $genre_id = $_GET["id"] ?? null;


    $genre_id_validation = validate_entity_ID($genre_id);
    if(!$genre_id_validation['valid'])
    {
        echo json_encode([
            'success' => false,
            'message' => $genre_id_result['message']
        ]);
        exit;
    }

    $query = <<<EOT
        SELECT 
            id,
            name,
            image
        FROM
            genres 
        WHERE id = ? AND is_deleted = 0
    EOT;

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $genre_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows === 0){
        respond(false , 404 , null , null , 'Genre not found');
    }

    $genre = $result->fetch_assoc();

    $stmt->close();
    $conn->close();

    respond(true , 200 , $genre , null , null);
}
else
{
    respond(false , 400 , null , null , 'Wrong method used');
}

?>
