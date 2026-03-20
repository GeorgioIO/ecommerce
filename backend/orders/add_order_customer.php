<?php
file_put_contents(
    __DIR__ . '/debug.log',
    "METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n" .
    "POST: " . print_r($_POST, true) .
    "TIME: " . date('H:i:s') . "\n\n" .
    "---\n",
    FILE_APPEND  // 👈 this is the key change
);
require_once __DIR__ . '/../../configuration/session.php';
require_once __DIR__ . '/../helpers.php';
header("Content-Type: application/json");

// User is not logged in
if(!isset($_SESSION['user_id']))
{
    respond(false , 401 , null , null , 'Log in required');
}

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    require_once __DIR__ . '/validators/order_validators.php';
    require_once __DIR__ . '/validators/order_db_validators.php';
    require_once __DIR__ . '/helpers/order_helpers.php';
    require_once __DIR__ . '/helpers/order_db_helpers.php';
    require_once __DIR__ . '/../customers/helpers/customers_db_helpers.php';
    require_once __DIR__ . '/../../configuration/database.php';
    require_once __DIR__ .  '/../cart/helpers/cart_helpers.php';
    require_once __DIR__ . '/../notifications/admin_notifications/helpers/admin_notifcations_db_helpers.php';
    require_once __DIR__ . '/../email/email_config.php';
    require_once __DIR__ . '/../books/helpers/book_db_helpers.php';

    // Get user id
    $user_id = $_SESSION['user_id'];
    $email_queue = [];
    $address_id = '';
    $address = '';

    if(isset($_POST['address_id']) && $_POST['address_id'] !== 'null')
    {
        // ! Current address id
        $address_id = $_POST['address_id'];

        // ! Existing address
        $address = get_customer_address($conn , $address_id , $user_id);

        if(!$address)
        {
            echo json_encode([
                'success' => false,
                'status' => 400,
                'message' => 'No existing default address'
            ]);
            exit;
        }

    }
    else if(isset($_POST['new_address']))
    {
        // ! New Address
        $address = json_decode($_POST['new_address'] , true);

        $address = normalize_address_payload($address);

        $address_validation = validate_address_details($address);

        if(!$address_validation['success'])
        {
            echo json_encode($address_validation);
            exit;
        }
    }
     
    
    // ! Fetch the user current active cart
    $current_cart_lines = get_current_cart($conn , $user_id);

    $total_price = calculate_total_cart($current_cart_lines);

    $total_price = $total_price + 2.00; // Shipping


    $conn->begin_transaction();
    try
    {
        // ! Using default address
        if(isset($_POST['address_id']) && $_POST['address_id'] !== 'null')
        {
            // Validate ownership
            $address_ownership_validation = validate_address_ownership($conn , $user_id  , $address_id);
            if(!$address_ownership_validation['success'])
            {
                throw new Exception($address_ownership_validation['message']);
            }
        }
        else // ! New address
        {
            // Add new address
            $address_id = insert_new_address($conn , $address , 0);
        }

        $order_code = generate_order_code();

        $order_metadata = [
            'status' => 'Processing',
            'total_order_price' => $total_price
        ];

        // ! Insert the order
        $order_id = insert_new_order($conn , $order_code , $order_metadata , $user_id , $address_id);


        // ! Insert order lines
        foreach($current_cart_lines as $line)
        {
            insert_order_line($conn , $line , $order_id);

            $book_id = (int) $line['bookId'];
            $quantity = (int) $line['quantity'];

            $current_book_data = get_book_data($conn , $book_id);

            $new_stock = decrease_book_stock($conn , $book_id , $quantity);

            handle_stock_transition($conn , $book_id , $current_book_data['title'] , $current_book_data['old_stock'] , $new_stock , $email_queue);
        }

        // ! Change current cart status to ordered 
        set_cart_ordered($conn , $user_id);

        // ! Create new cart for user
        $new_cart = create_new_cart($conn , $user_id);

        $conn->commit();

        insert_admin_notification($conn , 'new_order' , 'New Order Placed' , "Order {$order_code} was placed" , 'order' , $order_id);

        foreach($email_queue as $email)
        {
            sendEmail($email['type'] , $email['subject'] , $email['data']);
        }

        $order = get_single_order_by_id($conn , $order_id);
        $order_lines = get_order_lines_by_order($conn , $order_id);

        $email_data = [
            'order_data' => $order['value'],
            'order_lines' => $order_lines
        ];

        sendEmail('new_order' , "📦 New Order is Placed - #$order_id - $order_code" , $email_data);

        echo json_encode([
            'success' => true,
            'status' => 200,
            'message' => 'Order created successfully'
        ]);
        exit;
    }
    catch (Exception $e)
    {
        $conn->rollback();

        echo json_encode([
            'success' => false,
            'status' => 500,
            'message' => $e->getMessage()
        ]);
        exit;
    }
}
else
{
    respond(false , 400 , null , null , 'Wrong method used.');
}


?>