<?php

require_once  __DIR__ .  '/../../config/database.php';
require_once  __DIR__ . '/../../config/helpers.php';


function validate_author_id($id)
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
            'message' => 'Invalid Author ID must be a positive integer'
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

function validate_author_name($name)
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
            'message' => 'Name cannot succeed 100 characters'
        ];
    }

    return [
        'success' => true,
        'value' => ucfirst(trim($name))
    ];
}

?>