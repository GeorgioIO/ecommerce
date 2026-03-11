<?php

function get_customer_data($conn , $user_id)
{
    $query = "
        SELECT
            name,  
            email,
            phone_number,
            password
        FROM users
        WHERE id = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    return $result;
}


function get_customer_address($conn , $address_id , $user_id)
{
    $query = "
        SELECT * 
        FROM shipping_addresses s
        JOIN users u ON s.id = u.address_id
        WHERE s.is_active = 1 AND u.id = ? AND s.id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii" , $user_id , $address_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    return $result;
}



?>