<?php

require_once __DIR__ . '/../../configuration/session.php';
require_once  __DIR__ . '/../helpers.php';


header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    respond(false , 401 , null , null , 'Unauthorized to use api');
}

if($_SERVER['REQUEST_METHOD'] === "POST")
{
    require_once  __DIR__ . '/../../configuration/database.php';
    require_once  __DIR__ . '/validators/genre_validators.php';
    require_once  __DIR__ . '/validators/genre_db_validators.php';
    require_once  __DIR__ . '/helpers/genre_helpers.php';

    $genre_payload = extract_genre_payload($_POST , $_FILES);

    $genre_name_validation = validate_genre_name($genre_payload['name']);
    if(!$genre_name_validation['success'])
    {
        respond(false , 400 , null , null , "{$genre_payload['name']}");
    }

    $genre_image_validation = validate_genre_image_file($genre_payload['image']);
    if(!$genre_image_validation['success'])
    {
        respond(false , 400 , null , null , $genre_image_validation['message']);
    }

    $genre_name = $genre_name_validation['value'];

    // Validate DB name uniqueness
    $name_is_unique = DB_validate_genre_name($conn , $genre_name , $genre_payload['id']);
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

    if($genre_filename === null)
    {
        $query = <<<EOT
            UPDATE genres SET
                name = ?
            WHERE id = ?
        EOT;
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "si", 
            $genre_name , $genre_payload['id']);
    }
    else
    {
        $query = <<<EOT
            UPDATE genres SET
                name = ?,
                image = ?
            WHERE id = ?
        EOT;
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "ssi", 
            $genre_name , $genre_filename , $genre_payload['id']);
    }

    if($stmt->execute())
    {
        respond(true , 200 , null , null , 'Genre is updated successfully.');
    }
    else
    {
        respond(false , 500 , null , null , 'Something went wrong in updating genre');
    }
}
else
{
    respond(false , 400 , null , null , 'Wrong method used');
}

?>