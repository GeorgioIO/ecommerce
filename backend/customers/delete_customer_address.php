<?php

require_once __DIR__ . '../../../configuration/session.php';

header("Content-Type: application/json");

if(!isset($_SESSION['user_id']))
{
    echo json_encode([
        'success' => false,
        'status' => 401,
        'message' => 'Unauthorized'
    ]);
    exit;
}

require_once __DIR__ . '../../../configuration/database.php';


// Collect user id
$user_id = $_SESSION['user_id'];

// Get address
$address_query = "SELECT address_id FROM user_addresses WHERE user_id = ? AND is_active = 1";
$select_stmt = $conn->prepare($address_query);
$select_stmt->bind_param("i" , $user_id);
$select_stmt->execute();
$result = $select_stmt->get_result();

if($result->num_rows === 1)
{
    $address_id = $result->fetch_assoc()['address_id'];
}
else {
    echo json_encode([
        'success' => false,
        'status' => 400,
        'message' => 'You dont own any address currently'
    ]);
    exit;
}

// Soft delete the book from user_addresses
$delete_query = "UPDATE user_addresses SET is_active = 0 WHERE address_id = ? AND user_id = ?";
$delete_stmt = $conn->prepare($delete_query);
$delete_stmt->bind_param("ii" , $address_id , $user_id);

if($delete_stmt->execute())
{
    $delete_stmt->close();
    $select_stmt->close();
    $conn->close();
    echo json_encode([
        'success' => true,
        'status' => 200,
        'message' => 'Address deleted successfully'
    ]);
    exit;
}
else
{
    $delete_stmt->close();
    $select_stmt->close();
    $conn->close();
    echo json_encode([
        'success' => false,
        'status' => 500,
        'message' => 'Problem in deleting address'
    ]);
    exit;
}


?>