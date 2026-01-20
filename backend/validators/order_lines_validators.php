<?php

require_once __DIR__ . '/../../config/helpers.php';
require_once __DIR__ . '/book_db_validators.php';

function validate_order_lines($conn , array $lines) : array
{
    if(empty($lines))
    {
        return [
            'success' => false,
            'message' => 'Order must have at least one line'
        ];
    }    

    foreach( $lines as $index => $line) {

        // Book ID
        $book_id = $line['bookId'] ?? null;
        if(!is_numeric($book_id) || (int) $book_id <= 0)
        {
            return [
                'success' => false,
                'message' => 'Invalid book id in order line #' . ($index + 1)
            ];   
        }

        $book_id_DB_validation = DB_validate_book_exists($conn , $book_id);
        if(!$book_id_DB_validation['success'])
        {
            return [
                'success' => false,
                'message' => $book_id_DB_validation['message']
            ];
        }

        // Quantity
        $quantity = $line['quantity'] ?? null;
        if(!is_numeric($quantity) || (int) $quantity < 1)
        {
            return [
                'success' => false,
                'message' => 'Invalid quantity in order line #' . ($index + 1)
            ];
        }

        $unit_price = $line['unitPrice'] ?? null;
        if(!is_numeric($unit_price) || (float) $unit_price < 0)
        {
            return [
                'success' => false,
                'message' => 'Invalid unit price in order line #' . ($index + 1)
            ];
        }

        // Price
        $total_price = $line['totalLinePrice'] ?? null;
        if(!is_numeric($total_price) || (float) $total_price < 0)
        {
            return [
                'success' => false,
                'message' => 'Invalid total price in order line #' . ($index + 1)
            ];
        }
    }

    return ['success' => true];
}