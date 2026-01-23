<?php


require_once  __DIR__ .  '/../../../config/database.php';
require_once  __DIR__ . '/../../../config/helpers.php';

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