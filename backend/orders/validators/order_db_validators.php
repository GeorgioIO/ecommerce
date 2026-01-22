<?php

function validate_address_ownership($conn , $customer_id , $address_id)
{
    $query = <<<EOT
        SELECT address_id FROM user_addresses WHERE user_id = ? AND address_id = ?
    EOT;
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii" , $customer_id , $address_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows !== 1)
    {
        return [
            'success' => false,
            'message' => "Address doesnt belong to the user with id #$customer_id"
        ];
    }
    
    return [
        'success' => true
    ];
}



?>