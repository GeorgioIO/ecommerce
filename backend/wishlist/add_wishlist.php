<?php

// Header
header('Content-Type: application/json');

// Requires
require_once __DIR__ . '../../../configuration/database.php';
require_once __DIR__ . '../../../configuration/session.php';
require_once __DIR__ . '../../books/validators/book_validators.php';
require_once __DIR__ . '../../books/validators/book_db_validators.php';
require_once __DIR__ . '/validators/wishlist_db_validators.php';

// User not logged in
if(!isset($_SESSION['user_id']))
{
    echo json_encode([
        'success' => false,
        'message' => 'Log in is required'
    ]);
    exit;
}

// Collect data
$book_id = $_POST["id"];

// Validation
$book_id_validation = validate_book_id($book_id);
if(!$book_id_validation['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $book_id_validation['message']
    ]);
    exit;
}

// Book exist in DB ?
$book_existence_validation = DB_validate_book_exists($conn , $book_id);
if(!$book_existence_validation['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $book_existence_validation['message']
    ]);
    exit;
}

// Book exist in wishlist ?
$book_wishlist_existence_validation = validate_book_in_wishlist($conn , $_SESSION['user_id'] , $book_id);
if(!$book_wishlist_existence_validation['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $book_wishlist_existence_validation['message']
    ]);
    exit;
}

// Request
$query = "INSERT INTO wishlist_items (user_id , book_id) VALUES (? , ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii" , $_SESSION['user_id'] , $book_id);

if($stmt->execute())
{
    $response = [
        'success' => true,
        'message' => 'Book is added to wishlist'
    ];
}
else
{
    $response = [
        'success' => false,
        'message' => 'Book is not addded to wishlist'
    ];
}

$conn->close();
$stmt->close();
echo json_encode($response);
exit;

?>