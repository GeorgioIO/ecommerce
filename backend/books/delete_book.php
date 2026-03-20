<?php

header('Content-Type: application/json');
require __DIR__ . '/../../configuration/session.php';
require_once  __DIR__ . '/../helpers.php';

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    respond(false , 401 , null , null , 'Unauthorized to use api');
}

if($_SERVER['REQUEST_METHOD'] === "DELETE")
{
    
    require_once  __DIR__ .  '/../../configuration/database.php';

    parse_str($_SERVER['QUERY_STRING'] , $query_params);

    if(!isset($query_params['id']))
    {
        respond(false , 400 , null , null , 'Missing book id');
    }
    
    $book_id = intval($query_params['id']);
    $book_id = trim($book_id);

    $book_id_validation = validate_entity_ID($book_id);
    if(!$book_id_validation['valid'])
    {
        respond(false , 400 , null , null , $book_id_validation['message']);
    }

    $query = "UPDATE books SET is_deleted = 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $book_id);

    if($stmt->execute())
    {
        respond(true , 200 , null , null , 'Book is deleted');
    }
    else
    {
        respond(false , 500 , null , null , 'Something went wrong in deleting book');
    }
}
else
{
    respond(false , 400 , null , null , 'Wrong method used in deleting book');
}

?>