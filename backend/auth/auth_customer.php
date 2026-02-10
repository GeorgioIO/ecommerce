<?php

require_once __DIR__ . '/../../configuration/session.php';
require_once __DIR__ . '/../../configuration/database.php';

if(!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me']))
{
    $cookie_token = $_COOKIE['remember_me'];

    $query = "
        SELECT user_id , token_hash , expires_date
        FROM remember_tokens
        WHERE expires_date > NOW()
    ";

    $result = $conn->query($query);

    if($result)
    {
        while($row = $result->fetch_assoc())
        {
            if(password_verify($cookie_token , $row['token_hash']))
            {
                $_SESSION['user_id'] = $row['user_id'];
                break;
            }
        }
    }
}