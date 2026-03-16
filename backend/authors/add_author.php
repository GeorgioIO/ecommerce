<?php

header('Content-Type: application/json');

require __DIR__ . '/../../configuration/session.php';
require_once __DIR__ . '/../helpers.php';

if (!isset($_SESSION['admin_id'])) {
    respond(false , 401 , null , null , 'Unauthorized to use api');
}

if($_SERVER['REQUEST_METHOD'] === "POST")
{
    require_once __DIR__ .  '/../../configuration/database.php';
    require_once __DIR__ . '/validators/author_validators.php';
    require_once  __DIR__ . '/validators/author_db_validators.php';
    require_once  __DIR__ . '/helpers/author_helpers.php';

    $input = json_decode(file_get_contents("php://input") , true);

    if(!$input)
    {
        respond(false , 400 , null , null , "Invalid JSON Body.");
    }

    $author_payload = extract_author_payload($input);

    $author_name_validation = validate_author_name($author_payload['name']);
    if(!$author_name_validation['success'])
    {
        respond(false , 400 , null , null , $author_name_validation['message']);
        exit;
    }

    $author_name = $author_name_validation['value'];

    // ! Validate name uniqueness in Database 
    $name_is_unique = DB_validate_author_name($conn , $author_name);
    if(!$name_is_unique['success'])
    {
        respond(false , 400 , null , null ,$name_is_unique['message']);
    }

    $query = <<<EOT
        INSERT INTO authors
        (name)
        VALUES
        (?);
    EOT;

    $stmt = $conn->prepare($query);

    $stmt->bind_param("s" , $author_name);

    if($stmt->execute()){
        respond(true , 201 , null , null , 'New author is added');
    }
    else
    {
        respond(false , 500 , null , null , 'Something went wrong in adding author');
    }
}
else
{
    respond(false , 400 , null , null , 'Wrong method used');
}

?>