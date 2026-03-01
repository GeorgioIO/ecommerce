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


// ADD TO CART
/*
- user id -- DONE
- book id -- DONE
- cart id -- DONE
- quantity -- DONE
- unit price -- DONE


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
        'message' => 'No current active cart'
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

if(!validate_book_in_stock($conn , $book_id))
{
    echo json_encode([
        'success' => false,
        'status' => 400,
        'message' => 'Book not in stock'
    ]);
    exit;
}


// Validate book quantity
if(!is_numeric($quantity) || (int) $quantity < 1)
{
    echo json_encode([
        'success' => false,
        'status' => 400,
        'message' => 'Invalid quantity'
    ]);
    exit;
}


$conn->begin_transaction();
try
{

    // Get if this book already in cart
    $query = "
        SELECT id
        FROM cart_items
        WHERE cart_id = ? AND book_id = ?;
    ";
    $check_stmt = $conn->prepare($query);
    $check_stmt->bind_param("ii" , $active_cart_id , $book_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    // Book doesnt exist in cart = INSERT
    if($result->num_rows === 0)
    {
        // Extract unit price 
        $query = "
            SELECT
                CASE
                    WHEN is_onSale = 1
                        THEN ROUND(price - (price * discount_percentage) / 100 , 2)
                    ELSE
                        price
                    END AS final_price
            FROM books
            WHERE id = ?
            ";
        $price_stmt = $conn->prepare($query);
        $price_stmt->bind_param("i" , $book_id);
        $price_stmt->execute();
        $price_result = $price_stmt->get_result();
        $unit_price = $price_result->fetch_assoc()['final_price'];

        $insert_query = "
            INSERT INTO cart_items
            (cart_id , book_id , quantity , unit_price)
            VALUES
            (? , ? , ? , ?)
        ";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("iiid" , $active_cart_id , $book_id , $quantity , $unit_price);
        
        if($insert_stmt->execute())
        {
            $conn->commit();
            echo json_encode([
                'success' => true,
                'status' => 200,
                'message' => 'Book added to cart'
            ]);
            exit;
        }
        else
        {
            throw new Exception("Problem in adding to cart");
        }

    }
    else
    // Book exist in cart = UPDATE
    {
        $update_query = "
            UPDATE cart_items SET quantity = quantity + ? WHERE cart_id = ? AND book_id = ?
        ";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("iii" , $quantity , $active_cart_id , $book_id);
        
        if($update_stmt->execute())
        {
            $conn->commit();
            echo json_encode([
                'success' => true,
                'status' => 200,
                'message' => 'Book added to cart'
            ]);
            exit;
        }
        else
        {
            throw new Exception("Problem in adding to cart");
        }
    }



}
catch (Exception $e)
{
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'status' => 200,
        'message' => $e
    ]);
    exit;
}


?>