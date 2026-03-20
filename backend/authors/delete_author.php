<?php

header('Content-Type: application/json');

require __DIR__ . '/../../configuration/session.php';
require_once  __DIR__ . '/../helpers.php';

if (!isset($_SESSION['admin_id'])) {
    respond(false , 401 , null , null , 'Unauthorized to use api');
}


if($_SERVER['REQUEST_METHOD'] === "DELETE")
{
    require_once  __DIR__ .  '/../../configuration/database.php';
    require_once  __DIR__ . '/validators/author_db_validators.php';

    parse_str($_SERVER['QUERY_STRING'] , $query_params);

    if(!isset($query_params['id']))
    {
        respond(false , 400 , null , null , 'Missing author id');
    }

    $author_id = intval($query_params['id']);
    $author_id = trim($author_id);

    $author_id_validation = validate_entity_ID($author_id);

    if($author_id_validation['valid'] === false)
    {
        respond(false , 400 , null , null , $author_id_validation['message']);
    }

    // ! Author has books ?
    $has_books_validation = DB_validate_author_has_books($conn , $author_id);
    if(!$has_books_validation['success'])
    {
        respond(false , 400 , null , null , $has_books_validation['message']);
    }

    $query = "UPDATE authors SET is_deleted = 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $author_id);

    if($stmt->execute())
    {
        respond(true , 200 ,null , null , 'Author is deleted successsfully');
    }
    else
    {
        respond(false , 500 ,null , null , 'Something went wrong in deleting author');
    }
}
else
{
    respond(false , 400 ,null , null , 'Wrong method used in deleting author');
}

?>