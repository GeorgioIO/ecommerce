<?php

require_once __DIR__ . '../../../configuration/session.php';
header("Content-Type: application/json");

if(!isset($_SESSION['user_id']))
{
    echo json_encode([
        'success' => false,
        'status' => 401,
        'message' => 'Log in required'
    ]);
    exit;
}

require_once __DIR__ . '../../../configuration/database.php';

// TODO : This api endpoint is responsible of loading the current active cart items for the user
/* 

- Get user id


*/

$user_id = $_SESSION['user_id'] ;


$query = <<<EOT
    SELECT
        c.id,
        ci.book_id,
        ci.quantity,
        ci.unit_price,
        b.price * ci.quantity AS price,
        b.title,
        b.slug,
        b.cover_image,
        b.is_onSale,
        CASE WHEN b.is_onSale = 1 THEN (ROUND(b.price - (b.price * b.discount_percentage) / 100 , 2)) * ci.quantity ELSE b.price * ci.quantity END AS final_price
    FROM
        carts c
    JOIN cart_items ci ON c.id = ci.cart_id
    JOIN books b ON ci.book_id = b.id
    WHERE
        c.status = 'active' AND c.user_id = ?

EOT;

$select_stmt = $conn->prepare($query);
$select_stmt->bind_param("i" , $user_id);
$select_stmt->execute();
$result = $select_stmt->get_result();

if($result->num_rows > 0)
{
    $cart_items = [];
    while($row = $result->fetch_assoc())
    {
        $cart_items[] = $row;
    }

    echo json_encode([
        'success' => true,
        'status' => 200,
        'data' => $cart_items
    ]);
    exit;
}
else
{
    echo json_encode([
        'success' => true,
        'status' => 200,
        'data' => ''
    ]);
    exit;
}



?>