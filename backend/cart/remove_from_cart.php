<?php

require_once __DIR__ . '../../../configuration/session.php';

header("Content-Type: application/json");

if(!isset($_SESSION['user_id']))
{
    echo json_encode([
        'success' => false,
        'status' => 400,
        'message' => 'Log in required'
    ]);
    exit;   
}

require_once __DIR__ . '../../../configuration/database.php';
require_once __DIR__ . '../../books/validators/book_validators.php';
require_once __DIR__ . '../../books/validators/book_db_validators.php';
require_once __DIR__ . '../../books/helpers/book_db_helpers.php';


// REMOVE FROM CART
/*
- user id -- DONE
- book id -- DONE
- cart id -- DONE


*/

$user_id = $_SESSION['user_id'];

// Get current active cart id
$cart_query = "SELECT id FROM carts WHERE user_id = ? AND status = 'active'";
$cart_stmt = $conn->prepare($cart_query);
$cart_stmt->bind_param("i" , $user_id);
$cart_stmt->execute();
$result = $cart_stmt->get_result();
$active_cart_id = $result->fetch_assoc()['id'] ?? null;

if(!$active_cart_id)
{
    echo json_encode([
        'success' => false,
        'status' => 401,
        'message' => 'No current active cart'
    ]);
    exit;
}


$book_id = $_POST['book_id'] ?? null;
$quantity = $_POST['quantity'] ?? null;


// Validate book id + existence in db + in stock
$book_id_validation = validate_book_id($book_id);
if(!$book_id_validation['success']){
    echo json_encode([
        'success' => false,
        'status' => 400,
        'message' => 'Invalid book'
    ]);
    exit;
}

$book_db_existence = DB_validate_book_exists($conn , $book_id);
if(!$book_db_existence['success'])
{
    echo json_encode([
        'success' => false,
        'status' => 400,
        'message' => 'Invalid book'
    ]);
    exit;
}



if($result->num_rows !== 0)
{
    $delete_query = "DELETE FROM cart_items WHERE book_id = ? AND cart_id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("ii" , $book_id , $active_cart_id);
    $delete_stmt->execute();
    
    if($delete_stmt->affected_rows !== 0)
    {
        echo json_encode([
            'success' => true,
            'status' => 200,
            'message' => 'Book removed from cart'
        ]);
        exit;
    }
    else
    {
        echo json_encode([
            'success' => false,
            'status' => 500,
            'message' => 'Problem in removing book from cart'
        ]);
        exit;
    }
}
else
{
    echo json_encode([
        'success' => false,
        'status' => 400,
        'message' => 'This book does not belong to your cart'
    ]);
    exit;
}



?>