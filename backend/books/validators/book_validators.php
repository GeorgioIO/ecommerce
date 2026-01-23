<?php

require_once  __DIR__ .  '/../../../config/database.php';
require_once  __DIR__ . '/../../../config/helpers.php';


function validate_book_id($id)
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
            'message' => 'Invalid Book ID must be a positive integer'
        ];
    }

    if((int) $id <= 0)
    {
        return [
            'success' => false,
            'message' => 'Invalid Book ID must be higher than 0'
        ];
    }

    return [
        'success' => true,
        'value' => (int) $id
    ];
}

function validate_book_title($title)
{
    // Title is empty
    if($title === null || empty($title) || empty(trim($title)))
    {
        return [
            'success' => false,
            'message' => 'Title is required'
        ];
    }

    // Title above 100 characters
    if(strlen($title) > 100)
    {
        return [
            'success' => false,
            'message' => 'Title cannot succeed 100 characters'
        ];
    }

    return [
        'success' => true,
        'value' => ucfirst(trim($title))
    ];
}

function validate_book_isbn($isbn)
{
    // isbn is empty
    if($isbn === null || trim($isbn) === '')
    {
        return [
            'success' => false,
            'message' => 'ISBN is required'
        ];
    }

    // isbn is only digits
    if(!ctype_digit(trim($isbn)))
    {
        return [
            'success' => false,
            'message' => 'ISBN is invalid it must contain only digits'
        ];
    }

    // isbn either 10 or 13
    if(strlen($isbn) != 10 && strlen($isbn) != 13)
    {
        return [
            'success' => false,
            'message' => 'ISBN must be either 13 or 10 digits only'
        ];
    }

    return [
        'success' => true,
        'value' => trim($isbn)
    ];
}

function validate_book_sku($sku)
{
    if($sku === null || trim($sku) === '')
    {
        return [
            'success' => false,
            'message' => 'Sku is required'
        ];
    }

    if(strlen($sku) > 255)
    {
        return [
            'success' => false,
            'message' => 'SKU cannot succeed more than 255 characters'
        ];
    }

    return [
        'success' => true,
        'value' => $sku
    ];

}

function validate_book_language($language)
{
    
    if($language === null || trim($language) === '')
    {
        return [
            'success' => true,
            'value' => 'Not Defined'
        ];
    }

    $language = ucfirst(trim($language));

    if(strlen($language) > 100)
    {
        return [
            'success' => false,
            'message' => "Language cannot succeed 100 characters"
        ];
    }

    if($language !== "English" && $language !== "French")
    {
        return [
            'success' => false,
            'message' => 'Invalid Language'
        ];
    }

    return [
        'success' => true,
        'value' => $language 
    ];
}

function validate_book_author($id)
{
    if($id === null || trim($id) === '')
    {
        return [
            'success' => false,
            'message' => 'Invalid Author ID'
        ];
    }

    $id = trim($id);

    if(!ctype_digit($id))
    {
        return [
            'success' => false,
            'message' => 'Invalid Author ID must be a positive digit'
        ];
    }   

    if((int) $id <= 0)
    {
        return [
            'success' => false,
            'message' => 'Invalid Author ID must be higher than 0'
        ];
    }

    return [
        'success' => true,
        'value' => (int) $id
    ];
}

function validate_book_format($id)
{
    if($id === null || trim($id) === '')
    {
        return [
            'success' => false,
            'message' => 'Invalid Format ID'
        ];
    }

    $id = trim($id);

    if(!ctype_digit($id))
    {
        return [
            'success' => false,
            'message' => 'Invalid Format ID must be a positive digit'
        ];
    }   

    if((int) $id <= 0)
    {
        return [
            'success' => false,
            'message' => 'Invalid Format ID must be higher than 0'
        ];
    }

    return [
        'success' => true,
        'value' => (int) $id
    ];
}

function validate_book_genre($id)
{
    if($id === null || trim($id) === '')
    {
        return [
            'success' => false,
            'message' => 'Genre is required.'
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

function validate_book_quantity($quantity)
{
    if($quantity === null || trim($quantity) === '')
    {
        return [
            'success' => true,
            'value' => 0
        ];
    }

    $quantity = trim($quantity);

    if(!ctype_digit($quantity))
    {
        return [
            'success' => false,
            'message' => 'Quantity must be a non negative integer'
        ];
    }

    return [
        'success' => true,
        'value' => (int) $quantity
    ];
}

function validate_book_price($price)
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
            'message' => 'Price must be a valid number'
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

function validate_book_cover_file($cover)
{

    if(!$cover || $cover['error'] === UPLOAD_ERR_NO_FILE)
    {
        return [
            'success' => true,
            'value' => null
        ];
    }

    if($cover['error'] !== UPLOAD_ERR_OK)
    {
        return [
            'success' => false,
            'message' => 'Cover upload failed'
        ];
    }

    $allowed_types = ['image/png' , 'image/jpeg'];
    if(!in_array($cover['type']  , $allowed_types))
    {
        return [
            'success' => false,
            'message' => 'Only PNG and JPG formats are allowed'
        ];
    }


    return [
        'success' => true,
        'value' => $cover
    ];
}