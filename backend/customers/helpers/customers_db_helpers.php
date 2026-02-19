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


?>