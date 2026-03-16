<?php

header('Content-Type: application/json');

require __DIR__ . '/../../configuration/session.php';
require_once  __DIR__ . '/../helpers.php';


if (!isset($_SESSION['admin_id'])) {
    respond(false , 401 , 'Unauthorized to use api');
}

if($_SERVER['REQUEST_METHOD'] === "PATCH")
{
    require_once  __DIR__ .  '/../../configuration/database.php';
    require_once  __DIR__ . '/validators/author_db_validators.php';


    parse_str($_SERVER['QUERY_STRING'] , $query_params);

    if(!isset($query_params['id']))
    {
        respond(false , 400 , 'Missing author id');
    }

    $author_id = intval($query_params['id']);
    $author_id = trim($author_id);
    $author_id_validation = validate_entity_ID($author_id);

    if(!$author_id_validation['valid'])
    {
        respond(false , 400 , $author_id_validation['message']);
    }

    $query = "UPDATE authors SET is_deleted = 0 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $author_id);

    if($stmt->execute())
    {
        respond(true , 200 , 'Author is restored successsfully');
    }
    else
    {
        respond(false , 500 , 'Something went wrong in restoring author');
    }
}
else
{
    respond(false , 400 , 'Wrong method used');
}


?>