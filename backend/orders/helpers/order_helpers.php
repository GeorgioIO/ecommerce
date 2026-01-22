<?php

function validate_stock($conn , $insert_lines , $update_lines)
{
    // Validate for insert
    foreach($insert_lines as $line)
    {
        $book_id =  (int) $line['bookId'];
        $qty_to_insert = $line['quantity'];

        $current_book_stock = get_book_stock($conn , $book_id);

        if($current_book_stock['stock_quantity'] < $qty_to_insert)
        {
            throw new Exception("Not enough stock for book ID $book_id");
        }
    }

    // Validate for update
    foreach($update_lines as $line)
    {
        if($line['delta'] > 0)
        {
            $current_book_stock = get_book_stock($conn , $line['book_id']);

            if($current_book_stock['stock_quantity'] < $line['delta'])
            {
                throw new Exception("Not enough stock for book ID {$line['book_id']}");
            }
        }
    }
}

function is_order_editable($order)
{
    $allowed_statuses = array("Pending" , "Processing");

    if(!in_array(ucfirst($order['status']) , $allowed_statuses))
    {
        return false;
    }
    return true;
}

function generate_order_code()
{
    // target is to return order code with this format ORD-todaydate{randomletter}{ordercount}
    
    // today date in this format DMY
    $todayDate = new DateTime();
    $todayDate = $todayDate->format('mdY');

    // Random letter in uppercase
    $letter = chr(random_int(65,90));

    // Order Count
    $order_count = require __DIR__ . '/fetch_order_count.php';

    return "ORD-$todayDate$letter$order_count";
}

function extract_order_payload(array $post) : array
{
    return [
        'order' => [
            'id' => $post['id'] ?? null,
            'user_id' => $post['user_id'] ?? null,
            'status' => $post['status'] ?? null,
            'total_order_price' => $post['total_order_price'] ?? null,
            'date_added' => $post['date_added'] ?? null,
        ],
        'address' => [
            'existing_address_id' => $post['existing_address_id'] ?? null,
            'first_name' => $post['first_name'] ?? null,
            'last_name' => $post['last_name'] ?? null,
            'email' => $post['email'] ?? null,
            'phone_number' => $post['phone_number'] ?? null,
            'state' => $post['state'] ?? null,
            'city' => $post['city'] ?? null,
            'address_line1' => $post['address_line1'] ?? null,
            'address_line2' => $post['address_line2'] ?? null,
            'additional_notes' => $post['additional_notes'] ?? null,
        ],
        'order_lines' => isset($post['order_lines']) ? json_decode($post['order_lines'] , true) : []  
    ];
}

?>