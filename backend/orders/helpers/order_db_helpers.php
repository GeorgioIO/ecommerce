<?php

function update_order_meta($conn , $id , $new_status , $new_price , $new_address = null)
{
    if($new_address)
    {
        $query = <<<EOT
            UPDATE orders 
            SET 
                status = ?, 
                total_price = ?,
                address_id = ?
            WHERE id = ?;
        EOT;

        $stmt = $conn->prepare($query);
        $stmt->bind_param("sdii" , $new_status , $new_price , $new_address , $id);
    }
    else
    {
        $query = <<<EOT
            UPDATE orders 
            SET 
                status = ?, 
                total_price = ? 
            WHERE id = ?;
        EOT;
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sdi" , $new_status , $new_price , $id);
    }


    $stmt->execute();

    if($stmt->affected_rows < 0)
    {
        throw new Exception("Problem in updating order meta $id , $new_status , $new_price");
    }

    $stmt->close();
}

function update_order_line($conn , $order_id , $book_id , $unit_price , $quantity)
{
    $query = <<<EOT

    UPDATE order_items 
    SET quantity = ? , total_line_price = (? * ?) 
    WHERE order_id = ? AND book_id = ?;

    EOT;

    $stmt = $conn->prepare($query);
    $stmt->bind_param("idiii" , $quantity , $unit_price , $quantity , $order_id , $book_id);
    $stmt->execute();

    if($stmt->affected_rows !== 1)
    {
        throw new Exception("Problem in updating order line");
    }

    $stmt->close();
}

function decrease_book_stock($conn , $book_id , $quantity)
{
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

function increase_book_stock($conn , $book_id , $quantity)
{
    $query = <<<EOT
        UPDATE books SET stock_quantity  = stock_quantity + ? WHERE id = ? 
    EOT;

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii" , $quantity , $book_id);
    $stmt->execute();

    if($stmt->affected_rows !== 1)
    {
        throw new Exception("Problem in increasing stock for book id #$book_id");
    }

    $stmt->close();
}

function delete_order_line($conn , $order_id , $book_id)
{
    $query = <<<EOT
        DELETE FROM order_items WHERE order_id = ? AND book_id = ?
    EOT;
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii" , $order_id , $book_id);
    $stmt->execute();

    if($stmt->affected_rows !== 1)
    {
        throw new Exception("Problem in deleting order line $order_id , $book_id");
    }

    $stmt->close();
}

function get_book_title($conn , $id)
{
    $query = <<<EOT
        SELECT
            title
        FROM 
            books
        WHERE id = ?
    EOT;
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc()['title'];
}


function get_book_stock($conn , $id)
{
    $query = <<<EOT
        SELECT
            stock_quantity
        FROM 
            books
        WHERE id = ?
    EOT;
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc()['stock_quantity'];
}

function get_book_price($conn , $id)
{
    $query = <<<EOT
        SELECT
            price
        FROM 
            books
        WHERE id = ?
    EOT;
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc();
}

function get_order_lines_by_order($conn , $id)
{
    $query = <<<EOT
        SELECT
            oi.book_id,
            b.title,
            oi.quantity,
            oi.selling_price,
            oi.total_line_price
        FROM 
            order_items oi
        JOIN books b ON oi.book_id = b.id
        WHERE oi.order_id = ?
    EOT;
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $order_lines = [];

    if($result->num_rows !== 0)
    {
        while($row = $result->fetch_assoc())
        {
            $order_lines[] = $row;    
        }
    }

    return $order_lines;
}

function get_single_order_by_id($conn , $id)
{
    $query = <<<EOT
        SELECT 
            total_price,
            status,
            address_id
        FROM
            orders
        WHERE id = ?
    EOT;

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if($result->num_rows === 1)
    {   
        return [
            'success' => true,
            'value' => $result->fetch_assoc()
        ];
    }
    return [
        'success' => false,
        'message' => 'Order doesnt exist'
    ];

}

function insert_order_line($conn , $line , $order_id)
{
    $book_id = (int) $line['bookId'];
    $quantity = (int) $line['quantity'];
    $selling_price = (float) $line['unitPrice'];
    $total_line_price = (float) $line['totalLinePrice'];

    $query = <<<EOT

    INSERT INTO order_items (order_id , book_id ,quantity , selling_price , total_line_price)
    VALUES 
    (? , ? , ? , ? , ?)

    EOT;

    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiidd" , $order_id , $book_id, $quantity , $selling_price , $total_line_price);
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
                        $order_payload['total_order_price'],
                        $user_id,
                        $address_id);
    $stmt->execute();
    $DB_order_id = $conn->insert_id;
    $stmt->close();
    return $DB_order_id;
}

function insert_new_address($conn , $address_payload)
{
    $admin_made = 1;
    $query = <<<EOT

    INSERT INTO shipping_addresses
    (first_name , last_name, email, phone_number, state, city, address_line1, address_line2 , additional_notes, admin_made)
    VALUES
    (? , ? , ? , ? , ? , ? , ? , ? , ? , ?)

    EOT;



    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssssi", 
                        $address_payload['first_name'], 
                        $address_payload['last_name'], 
                        $address_payload['email'], 
                        $address_payload['phone_number'], 
                        $address_payload['state'], 
                        $address_payload['city'], 
                        $address_payload['address_line1'], 
                        $address_payload['address_line2'], 
                        $address_payload['additional_notes'],
                        $admin_made
                        );
    
    $stmt->execute();
    $DB_address_id = $conn->insert_id;
    $stmt->close();
    return $DB_address_id;
}

?>