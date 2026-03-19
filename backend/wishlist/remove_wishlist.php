<?php

// Header
header('Content-Type: application/json');

// Requires
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '../../../configuration/session.php';


// User not logged in
if(!isset($_SESSION['user_id']))
{
    respond(false, 400 , null , null , 'Not logged in');
}

if($_SERVER['REQUEST_METHOD'] === "DELETE")
{
    
    require_once __DIR__ . '../../../configuration/database.php';
    require_once __DIR__ . '../../books/validators/book_validators.php';
    require_once __DIR__ . '../../books/validators/book_db_validators.php';
    require_once __DIR__ . '/validators/wishlist_db_validators.php';

    parse_str($_SERVER['QUERY_STRING'] , $query_params);

    if(!isset($query_params['id']))
    {
        respond(false , 400 , null , null , 'Missing product id');
    }

    $book_id = intval($query_params['id']);
    $book_id = trim($book_id);

    $book_id_validation = validate_entity_ID($book_id);

    if($book_id_validation['valid'] === false)
    {
        respond(false , 400 , null , null , $book_id_validation['message']);
    }

    // Book exist in DB ?
    $book_existence_validation = DB_validate_book_exists($conn , $book_id);
    if(!$book_existence_validation['success'])
    {
        respond(false , 404 , null , null , $book_existence_validation['message']);
    }

    // Book exist in wishlist ?
    // $book_wishlist_existence_validation = validate_book_in_wishlist($conn , $_SESSION['user_id'] , $book_id);
    // if($book_wishlist_existence_validation['success'])
    // {
    //     http_response_code(404);

    //     echo json_encode([
    //         'success' => false,
    //         'message' => $book_wishlist_existence_validation['message']
    //     ]);
    //     exit;
    // }

    // Request
    $query = "DELETE FROM wishlist_items WHERE user_id = ? AND book_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii" , $_SESSION['user_id'] , $book_id);

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
    respond(false , 400 , null , null , 'Wrong method used');
}
?>