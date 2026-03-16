<?php

require_once __DIR__ . '/../../configuration/session.php';
require_once __DIR__ . '/../helpers.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    respond(false , 401 , null , null , 'Unauthorized to use api');
}

if($_SERVER['REQUEST_METHOD'] === "POST")
{
    require_once __DIR__ .  '/../../configuration/database.php';
    require_once __DIR__ . '/validators/genre_validators.php';
    require_once  __DIR__ . '/validators/genre_db_validators.php';
    require_once  __DIR__ . '/helpers/genre_helpers.php';


    $genre_payload = extract_genre_payload($_POST , $_FILES);

    // Validation of data
    $genre_name_validation = validate_genre_name($genre_payload['name']);
    if(!$genre_name_validation['success'])
    {
        respond(false , 400 , null , null , $genre_name_validation['message']);
    }

    $genre_image_validation = validate_genre_image_file($genre_payload['image']);
    if(!$genre_image_validation['success'])
    {
        respond(false , 400 , null , null , $genre_image_validation['message']);
    }

    $genre_name = $genre_name_validation['value'];

    // ! Validate genre name uniqueness
    $name_is_unique = DB_validate_genre_name($conn , $genre_name);
    if(!$name_is_unique['success'])
    {
        respond(false , 400 , null , null , $name_is_unique['message']);
    }

    $genre_filename = null;

    if($genre_image_validation['value'])
    {
        $genre_filename = upload_image($genre_image_validation['value']);

        if($genre_filename === false)
        {
            respond(false , 400 , null , null , 'Failed to upload image');
        }
    }

    $query = <<<EOT
        INSERT INTO genres
        (name , image)
        VALUES
        (? , ?);
    EOT;

    $stmt = $conn->prepare($query);

    $stmt->bind_param("ss" , $genre_name , $genre_filename);

    if($stmt->execute()){
        respond(true , 201 , null , null , 'New genre is added');
    }
    else
    {
        respond(false , 500 , null , null , 'Problem in adding genre');
    }
}
else
{
    respond(false , 400 , null , null , 'Wrong method used');
}

?>