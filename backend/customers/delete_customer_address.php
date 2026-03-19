<?php

require_once __DIR__ . '../../../configuration/session.php';
require_once __DIR__ . '/../helpers.php';

header("Content-Type: application/json");

if(!isset($_SESSION['user_id']))
{
    respond(false , 401 , null , null , 'Not authorized to use api');
}

require_once __DIR__ . '../../../configuration/database.php';

if($_SERVER['REQUEST_METHOD'] === 'DELETE')
{
    $user_id = $_SESSION['user_id'];

    // Get address
    $address_query = "SELECT address_id FROM users WHERE id = ?";
    $select_stmt = $conn->prepare($address_query);
    $select_stmt->bind_param("i" , $user_id);
    $select_stmt->execute();
    $result = $select_stmt->get_result();

    if($result->num_rows === 1)
    {
        $address_id = $result->fetch_assoc()['address_id'];
    }
    else {
        respond(false , 404 , null , null , 'You dont own any address currently');
    }

    $conn->begin_transaction();

    try
    {
        // Soft delete the book from user_addresses
        $delete_query = "UPDATE shipping_addresses SET is_active = 0 WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i" , $address_id);
        $delete_stmt->execute();

        if($delete_stmt->affected_rows === 0)
        {
            throw new Exception('Address not found');
        }

        // Soft delete address from user table
        $user_ad_query = "UPDATE users SET address_id = NULL WHERE id = ?";
        $user_ad_stmt = $conn->prepare($user_ad_query);
        $user_ad_stmt->bind_param("i" , $user_id);
        $user_ad_stmt->execute();

        if($user_ad_stmt->affected_rows === 0)
        {
            throw new Exception('User update failed');
        }

        $conn->commit();

        respond(false , 200 , null , null , 'Address deleted successfully');

    }
    catch (Exception $e)
    {
        $conn->rollback();

        respond(false , 500 , null , null , 'Something went wrong in deleting address');
    }
}
else
{
    respond(false , 400 , null , null , 'Wrong method used');
}

?>