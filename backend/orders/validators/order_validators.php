<?php

require_once __DIR__ . '/../../../config/helpers.php';

function validate_order_customer($id)
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

function validate_order_status($status)
{
    
    if($status === null || trim($status) === '')
    {
        return [
            'success' => false,
            'value' => 'Invalid status cannot be empty'
        ];
    }

    $status = ucfirst(trim($status));

    if($status !== "Pending" && $status !== "Processing" && $status !== "Delivered" && $status !== "Shipped" && $status !== "Cancelled" && $status !== "Refunded")
    {
        return [
            'success' => false,
            'message' => 'Invalid Status'
        ];
    }

    return [
        'success' => true,
        'value' => $status 
    ];
}


function validate_order_price($price)
{
    if($price === null || trim($price) === '')
    {
        return [
            'success' => false,
            'message' => 'Price is required'
        ];
    }

    $price = trim($price);

    if(!is_numeric($price)){
        return [
            'success' => false,
            'message' => "Price must be a valid number $price"
        ];
    }

    if((float) $price < 0)
    {
        return [
            'success' => false,
            'message' => 'Price must be zero or greater'
        ];
    }

    return [
        'success' =>  true,
        'value' => (float) $price
    ];
}

function validate_order_date($date)
{
    $expectedFormat = 'Y-m-d';
    
    if(!isset($date) || trim($date) === '')
    {
        return [
            'success' => false,
            'message' => 'Date is required'
        ];
    }

    $dt = DateTime::createFromFormat($expectedFormat , $date);
    // Format + parsing validation
    if(!$dt || $dt->format($expectedFormat) !== $date)
    {
        return [
            'success' => false,
            'message' => 'Date format is invalid'
        ];
    }

    // Today validation
    if($dt->format('Y-m-d') !== date('Y-m-d'))
    {
        return [
            'success' => false,
            'message' => 'Date is invalid'
        ];
    }

    return [
        'success' => true,
        'value' => $dt->format('Y-m-d')
    ];
}

function validate_order_existing_address($id)
{
    if($id === null || $id === 'null' || empty($id) || empty(trim($id)))
    {
        return [
            'success' => true,
            'value' => null
        ];
    }

    $id = trim($id);

    if(!ctype_digit($id))
    {
        return [
            'success' => false,
            'message' => "Invalid Existing Address ID must be a positive integer "
        ];
    }

    if((int) $id <= 0)
    {
        return [
            'success' => false,
            'message' => 'Invalid Existing Address ID must be higher than 0'
        ];
    }

    return [
        'success' => true,
        'value' => (int) $id
    ];
}

function validate_order_ad_first_name($first_name)
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

function validate_order_ad_last_name($last_name)
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

function validate_order_ad_email($email)
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

function validate_order_ad_phone($phone)
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

function validate_order_ad_state($state)
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

function validate_order_ad_city($city)
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

function validate_order_ad_line1($ad_line1)
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

function validate_order_ad_line2($ad_line2)
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
        'value' => null
    ];   
}

function validate_order_ad_notes($notes)
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
        'value' => null
    ];   
}   

function validate_order_id($id)
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
            'message' => 'Invalid Order ID must be a positive integer'
        ];
    }

    if((int) $id <= 0)
    {
        return [
            'success' => false,
            'message' => 'Invalid Order ID must be higher than 0'
        ];
    }

    return [
        'success' => true,
        'value' => (int) $id
    ];
}

?>