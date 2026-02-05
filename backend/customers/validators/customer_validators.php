<?php


require_once  __DIR__ .  '/../../../configuration/database.php';
require_once  __DIR__ . '/../../helpers.php';

function validate_customer_name($name)
{
    // Name is empty
    if($name === null || empty($name) || empty(trim($name)))
    {
        return [
            'success' => false,
            'message' => 'Username is required'
        ];
    }

    // Name above 255 characters
    if(strlen($name) > 255)
    {
        return [
            'success' => false,
            'message' => 'name cannot succeed 255 characters'
        ];
    }

    return [
        'success' => true,
    ];
}

function validate_customer_email($email)
{
    // Email is empty
    if($email === null || empty($email) || empty(trim($email)))
    {
        return [
            'success' => false,
            'message' => 'Email is required'
        ];
    }

    // Email above 255 characters
    if(strlen($email) > 255)
    {
        return [
            'success' => false,
            'message' => 'Email cannot succeed 255 characters'
        ];
    }

    if(!validate_email($email)['valid'])
    {
        return [
            'success' => false,
            'message' => 'Invalid email'
        ];
    }

    return [
        'success' => true,
    ];
}

function validate_customer_phone($phone)
{
    if(!$phone) return ['success' => true];

    if(strlen($phone) > 25)
    {
        return [
            'success' => false,
            'message' => 'Phone number cannot success 25 characters (+9611234567)'
        ];
    }

    if(!isValidPhone($phone))
    {
        return [
            'success' => false,
            'message' => 'Invalid phone number'
        ];
    }

    return ['success' => true]; 
}

function validate_customer_password($password)
{
    $commonPassword = ["password" , "123456" , "qwerty" , "admin"];

    if($password === null || empty($password) || empty(trim($password)))
    {
        return [
            'success' => false,
            'message' => 'Password is required'
        ];
    }

    if(strlen($password) < 8)
    {
        return [
            'success' => false,
            'message' => 'Password must be atleast 8 characters'
        ];
    }

    if(in_array($password , $commonPassword))
    {
        return [
            'success' => false,
            'message' => 'Password is common'
        ];
    }

    if(!is_valid_password($password))
    {
        return [
            'success' => false,
            'message' => 'Password must atleast have 1 Uppercase , 1 Lowercase , 1 number , 1 special character'
        ];
    }

    return ['success' => true];
}

function validate_customer_id($id)
{
    if($id === null || empty($id) || empty(trim($id)))
    {
        return [
            'success' => false,
            'message' => 'Invalid ID'
        ];
    }

    $id = trim($id);

    if(!ctype_digit($id))
    {
        return [
            'success' => false,
            'message' => 'Invalid Customer ID must be a positive integer'
        ];
    }

    if((int) $id <= 0)
    {
        return [
            'success' => false,
            'message' => 'Invalid Customer ID must be higher than 0'
        ];
    }

    return [
        'success' => true,
        'value' => (int) $id
    ];
}

?>