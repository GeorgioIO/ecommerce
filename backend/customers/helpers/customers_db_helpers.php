<?php

function validate_creds_existence($conn , $email , $phone)
{
    $types = "s";
    $params = [$email];
    $query = <<<SQL

    SELECT
        id,
        email,
        phone_number
    FROM users
    WHERE 
        email = ? 
    SQL;

    if($phone)
    {
        $query .= " OR phone_number = ?";
        $types .= "s";
        $params[] = $phone;
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types , ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0)
    {
        return [
            'success' => false,
            'message' => 'Credentials already exists'
        ];
    }
    else
    {
        return [
            'success' => true
        ];
    }

}

?>