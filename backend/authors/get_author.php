<?php

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === "GET")
{
    require_once  __DIR__ . '/../../configuration/database.php';
    require_once  __DIR__ . '/validators/author_validators.php';


    $id = $_GET['id'] ?? null;

    $author_id_validation = validate_entity_ID($id);
    if(!$author_id_validation['valid'])
    {
        respond(false , 400 , null , null , $author_id_validation['message']);  
    }


    $query = <<<EOT
        SELECT 
            id,
            name
        FROM
            authors 
        WHERE id = ? AND is_deleted = 0
    EOT;

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows === 0){
        respond(false , 404 , null , null , 'Author not found');
    }

    $author = $result->fetch_assoc();

    respond(true , 200 , $author , null , null);
}
else
{
    respond(false , 400 , null , null , 'Wrong method used in getting author');
}


?>
