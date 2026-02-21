<?php


require_once  __DIR__ .  '/../../../configuration/database.php';
require_once  __DIR__ . '/../../helpers.php';


function validate_customer_ad_first_name($first_name)
{
    if($first_name === null || empty($first_name) || empty(trim($first_name)))
    {
        return [
            'success' => false,
            'message' => "First Name is required"
        ];
    }

    $first_name = ucfirst(trim($first_name)) ;

    if(strlen($first_name) > 255)
    {
        return [
            'success' => false,
            'message' => 'First name cannot succeed 255 characters'
        ];    
    }

    return [
        'success' => true,
        'value' => $first_name
    ];
}

function validate_customer_ad_last_name($last_name)
{
    if($last_name === null || empty($last_name) || empty(trim($last_name)))
    {
        return [
            'success' => false,
            'message' => "Last Name is required"
        ];
    }

    $last_name = ucfirst(trim($last_name)) ;

    if(strlen($last_name) > 255)
    {
        return [
            'success' => false,
            'message' => 'Last name cannot succeed 255 characters'
        ];    
    }

    return [
        'success' => true,
        'value' => $last_name
    ];
}

function validate_customer_ad_email($email)
{
    if($email === null || empty($email) || empty(trim($email)))
    {
        return [
            'success' => false,
            'message' => "Email is required"
        ];
    }
    
    $email = strtolower(trim($email));
    $email_validation_result = validate_email($email);

    if(!$email_validation_result['valid'])
    {
        return [
            'success' => false,
            'message' => 'Email is invalid'
        ];    
    }

    if(strlen($email) > 55)
    {
        return [
            'success' => false,
            'message' => 'Email cannot succeed 55 characters'
        ];    
    }

        return [
        'success' => true,
        'value' => $email
    ];
}   

function validate_customer_ad_phone($phone)
{
    $phone = trim($phone ?? '');

    if($phone === '')
    {
        return [
            'success' => false,
            'message' => 'Phone number is required'
        ];   
    }

    if(!isValidPhone($phone))
    {
        return [
            'success' => false,
            'message' => 'Invalid phone number'
        ];
    }

    return [
        'success' => true,
        'value' => $phone
    ];
}

function validate_customer_ad_state($state)
{
    $state = trim($state ?? '');

    if($state === '')
    {
        return [
            'success' => false,
            'message' => 'State is required'
        ];   
    }

    if(strlen($state) > 55)
    {
        return [
            'success' => false,
            'message' => 'State cannot succeed 55 characters'
        ];   
    }

    return [
        'success' => true,
        'value' => ucfirst($state)
    ];
}

function validate_customer_ad_city($city)
{
    $city = trim($city ?? '');

    if($city === '')
    {
        return [
            'success' => false,
            'message' => 'City is required'
        ];   
    }

    if(strlen($city) > 55)
    {
        return [
            'success' => false,
            'message' => 'City cannot succeed 55 characters'
        ];   
    }

    return [
        'success' => true,
        'value' => ucfirst($city)
    ];
}

function validate_customer_ad_line1($ad_line1)
{
    $ad_line1 = trim($ad_line1 ?? '');

    if($ad_line1 === '')
    {
        return [
            'success' => false,
            'message' => 'Address Line 1 is required'
        ];   
    }

    if(strlen($ad_line1) > 55)
    {
        return [
            'success' => false,
            'message' => 'Address Line 1 cannot succeed 255 characters'
        ];   
    }

    return [
        'success' => true,
        'value' => ucfirst($ad_line1)
    ];
}

function validate_customer_ad_line2($ad_line2)
{
    $ad_line2 = trim($ad_line2 ?? '');

    if($ad_line2 === '')
    {
        return [
            'success' => true,
            'value' => null
        ];   
    }

    if(strlen($ad_line2) > 255)
    {
        return [
            'success' => false,
            'message' => 'Address Line 2 cannot succeed 255 character'
        ];    
    }

    return [
        'success' => true,
        'value' => ucfirst($ad_line2)
    ];   
}

function validate_customer_ad_notes($notes)
{
    $notes = trim($notes ?? '');

    if($notes === '')
    {
        return [
            'success' => true,
            'value' => null
        ];   
    }

    if(strlen($notes) > 255)
    {
        return [
            'success' => false,
            'message' => 'Additional notes cannot succeed 255 character'
        ];    
    }

    return [
        'success' => true,
        'value' => ucfirst($notes)
    ];   
}

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