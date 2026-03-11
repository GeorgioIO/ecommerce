<?php

function create_new_cart($conn , $user_id)
{
    $query = 
    "
    INSERT INTO carts (user_id , status)
    VALUES (? , 'active');
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $user_id);
    
    if($stmt->execute())
    {
        return $conn->insert_id;
    }
    else
    {
        throw new Exception("Problem creating a new cart");
    }
}

function set_cart_ordered($conn , $user_id)
{
    $query = "
        UPDATE carts
        SET status = 'ordered' WHERE user_id = ? AND status = 'active'
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $user_id);
    $stmt->execute();

    if($stmt->affected_rows > 0)
    {
        return [
            'success' => true
        ];
    }
    
    throw new Exception("Problem updating current cart");
}

function calculate_total_cart($cart)
{
    $total = 0;
    foreach($cart as $cart_line)
    {
        $total += (int) $cart_line['quantity'] * (float) $cart_line['unitPrice'];
    }

    return $total;
}

function get_current_cart($conn , $user_id)
{
    $query = "
        SELECT 
            c.id,
            ci.book_id AS bookId,
            ci.quantity,
            ci.unit_price AS unitPrice,
            ci.quantity * ci.unit_price AS totalLinePrice
        FROM carts c
        JOIN cart_items ci ON c.id = ci.cart_id
        WHERE c.status = 'active' AND c.user_id = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

?>