<?php

function upload_image($image_file)
{
    $upload_directory = __DIR__ . "/../assets/images/";

    $extension = pathinfo($image_file['name'] , PATHINFO_EXTENSION);

    $filename = uniqid("cover_" , true) . "." . $extension;
    $target_path = $upload_directory . $filename;

    
    if(!move_uploaded_file($image_file['tmp_name'] , $target_path))
    {
        return false;
    }
    return $filename;
}

function isNumber($data)
{
    if(is_numeric($data))
    {
        return true;
    }
    return false;
}

function isNumberAllowed($data)
{
    if($data < 0)
    {
        return false;
    }
    return true;
}

function isValidISBN($data)
{
    if(strlen($data) != 13 && strlen($data) != 10)
    {
        return false;
    }
    return true;
}

function validate_entity_ID($data)
{
    if($data === null || trim($data) === '')
    {
        return [
            'valid' => false,
            'message' => "ID is empty"
        ];
    }

    if(!ctype_digit(trim($data)) )
    {
        return [
            'valid' => false,
            'message' => "ID must be a positive integer"
        ];
    }

    return [
        'valid' => true,
        'message' => ""
    ];
}

function validate_user_email($email)
{
    if($email === null || trim($email) === '')
    {
        return [
            'valid' => false,
            'message' => 'Email cannot be empty'
        ];
    }

    $email = trim($email);
    $regex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

    if(!preg_match($regex , $email))
    {
        return [
            'valid' => false,
            'message' => 'Invalid Email'
        ];
    }

    if(strlen($email) > 255)
    {
        return [
            'valid' => false,
            'message' => 'Email cannot succeed 255 characters'
        ];
    }

    return [
        'valid' => true,
        'value' => $email    
    ];

}

?>