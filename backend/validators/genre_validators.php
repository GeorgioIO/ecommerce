<?php

require_once  __DIR__ .  '/../../config/database.php';
require_once  __DIR__ . '/../../config/helpers.php';


function validate_genre_id($id)
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
            'message' => 'Invalid Genre ID must be a positive integer'
        ];
    }

    if((int) $id <= 0)
    {
        return [
            'success' => false,
            'message' => 'Invalid Genre ID must be higher than 0'
        ];
    }

    return [
        'success' => true,
        'value' => (int) $id
    ];
}

function validate_genre_name($name)
{
    // Name is empty
    if($name === null || empty($name) || empty(trim($name)))
    {
        return [
            'success' => false,
            'message' => 'Name is required'
        ];
    }

    // Name above 45 characters
    if(strlen($name) > 45)
    {
        return [
            'success' => false,
            'message' => 'Genre cannot succeed 45 characters'
        ];
    }

    return [
        'success' => true,
        'value' => ucfirst(trim($name))
    ];
}

function validate_genre_image_file($image)
{

    if(!$image || $image['error'] === UPLOAD_ERR_NO_FILE)
    {
        return [
            'success' => true,
            'value' => null
        ];
    }

    if($image['error'] !== UPLOAD_ERR_OK)
    {
        return [
            'success' => false,
            'message' => 'Cover upload failed'
        ];
    }

    $allowed_types = ['image/png' , 'image/jpeg'];
    if(!in_array($image['type']  , $allowed_types))
    {
        return [
            'success' => false,
            'message' => 'Only PNG and JPG formats are allowed'
        ];
    }


    return [
        'success' => true,
        'value' => $image
    ];
}