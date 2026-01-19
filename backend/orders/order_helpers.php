<?php

function update_stock($conn , $line)
{
    $book_id = (int) $line['bookId'];
    $quantity = (int) $line['quantity'];
    
    $query = <<<EOT
        UPDATE books SET stock_quantity  = stock_quantity - ? WHERE id = ? AND stock_quantity >= ?
    EOT;

    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii" , $quantity , $book_id , $quantity);
    $stmt->execute();

    if($stmt->affected_rows !== 1)
    {
        throw new Exception("Not enough stock for book ID $book_id");
    }

    $stmt->close();
}

function insert_order_line($conn , $line , $order_id)
{
    $book_id = (int) $line['bookId'];
    $quantity = (int) $line['quantity'];
    $price = (float) $line['totalPrice'];

    $query = <<<EOT

    INSERT INTO order_items (order_id , book_id ,quantity , price)
    VALUES 
    (? , ? , ? , ?)

    EOT;

    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiid" , $order_id , $book_id, $quantity , $price);
    $stmt->execute();
    $stmt->close();
}

function insert_new_order($conn , $order_code , $order_payload , $user_id , $address_id)
{
    $query = <<<EOT

    INSERT INTO orders 
    (order_code , status , total_price , user_id , address_id)
    VALUES
    (? , ? , ? , ? , ?)

    EOT;

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssdii",
                        $order_code,
                        $order_payload['status'],
                        $order_payload['total_price'],
                        $user_id,
                        $address_id);
    $stmt->execute();
    $DB_order_id = $conn->insert_id;
    $stmt->close();
    return $DB_order_id;
}

function insert_new_address($conn , $address_payload)
{
    $query = <<<EOT

    INSERT INTO shipping_addresses
    (first_name , last_name, email, phone_number, state, city, address_line1, address_line2 , additional_notes)
    VALUES
    (? , ? , ? , ? , ? , ? , ? , ? , ?)

    EOT;



    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssss", 
                        $address_payload['first_name'], 
                        $address_payload['last_name'], 
                        $address_payload['email'], 
                        $address_payload['phone_number'], 
                        $address_payload['state'], 
                        $address_payload['city'], 
                        $address_payload['address_line1'], 
                        $address_payload['address_line2'], 
                        $address_payload['additional_notes'], 
                        );
    
    $stmt->execute();
    $DB_address_id = $conn->insert_id;
    $stmt->close();
    return $DB_address_id;
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
            'total_price' => $post['total_price'] ?? null,
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