<?php

function validate_phone_existence($conn , $phone)
{
    $query  = "
    SELECT 
        phone_number
    FROM users
    WHERE phone_number = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s" , $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows !== 0)
    {
        return [
            'success' => false,
            'message' => 'Phone number already exist'
        ];
    }
    else
    {
        return [
            'success' => true,
        ];
    }
}

function validate_email_existence($conn , $email)
{
    $query  = "
    SELECT 
        email
    FROM users
    WHERE email = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s" , $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows !== 0)
    {
        return [
            'success' => false,
            'message' => 'Email address already exist'
        ];
    }
    else
    {
        return [
            'success' => true,
        ];
    }
}

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