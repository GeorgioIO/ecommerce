<?php

require __DIR__ . '/../../configuration/session.php';
require_once  __DIR__ . '/../helpers.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    respond(false , 401 , null , null , 'Unauthorized to use api');
}

if($_SERVER['REQUEST_METHOD'] === "DELETE")
{
    require_once  __DIR__ .  '/../../configuration/database.php';
    require_once  __DIR__ . '/validators/genre_db_validators.php';

    
    parse_str($_SERVER['QUERY_STRING'] , $query_params);

    if(!isset($query_params['id']))
    {
        respond(false , 400 , null , null , 'Missing genre id');
    }

    $genre_id = intval($query_params['id']);
    $genre_id = trim($genre_id);

    $genre_id_validation = validate_entity_ID($genre_id);

    if($genre_id_validation['valid'] === false)
    {
        respond(false , 400 , null , null , $genre_id_validation['message']);
    }

    $has_books_validation = DB_validate_genre_has_books($conn , $genre_id);
    if(!$has_books_validation['success'])
    {
        respond(false , 400 , null , null , $has_books_validation['message']);
    }


    $query = "UPDATE genres SET is_deleted = 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $genre_id);

    if($stmt->execute())
    {
        respond(true , 200 , null , null , 'Genre is deleted successfully');
    }
    else
    {
        respond(true , 500 , null , null , 'Something went wrong in deleting genre');
    }
}
else
{
    respond(false , 400 , null , null , 'Wrong method used in deleting genre');
}

?>