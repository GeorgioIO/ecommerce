<?php

require __DIR__ . '/../../configuration/session.php';
require_once __DIR__ . '/../helpers.php';
header("Content-Type: application/json");


if (!isset($_SESSION['admin_id'])) {
    respond(false , 401 , null ,null , 'Not authorized to use api');
}

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    require_once __DIR__ . '/../../configuration/database.php';
    require_once __DIR__ . '/validators/order_validators.php';
    require_once __DIR__ . '/validators/order_db_validators.php';
    require_once __DIR__ . '/validators/order_lines_validators.php';
    require_once __DIR__ . '/helpers/order_helpers.php';
    require_once __DIR__ . '/helpers/order_db_helpers.php';
    require_once __DIR__ . '/../notifications/admin_notifications/helpers/admin_notifcations_db_helpers.php';
    require_once __DIR__ . '/../email/email_config.php';
    require_once __DIR__ . '/../books/helpers/book_db_helpers.php';

    $order_payload = extract_order_payload($_POST);
    $email_queue = [];
    
    $customer_id_validation = validate_order_customer($order_payload['order']['user_id']);
    if(!$customer_id_validation['success'])
    {
        respond(false , 400 , null , null , $customer_id_validation['message']);
    }

    $order_status_validation = validate_order_status($order_payload['order']['status']);
    if(!$order_status_validation['success'])
    {
        respond(false , 400 , null , null , $order_status_validation['message']);
    }

    $order_total_price_validation = validate_order_price($order_payload['order']['total_order_price']);
    if(!$order_total_price_validation['success'])
    {
        respond(false , 400 , null , null , $order_total_price_validation['message']);
    }

    $order_date_validation = validate_order_date($order_payload['order']['date_added']);
    if(!$order_date_validation['success'])
    {
        respond(false , 400 , null , null , $order_date_validation['message']);
    }

    // Existing address
    if($order_payload['address']['existing_address_id'])
    {
        // Validate address id
        $order_address_id_validation = validate_order_existing_address($order_payload['address']['existing_address_id']);
        if(!$order_address_id_validation['success'])
        {
            respond(false , 400 , null , null , $order_address_id_validation['message']);
        }
        
    }
    else
    {

        $first_name_validation = validate_order_ad_first_name($order_payload['address']['first_name']);
        if(!$first_name_validation['success'])
        {
            respond(false , 400 , null , null , $first_name_validation['message']);
        }    
        
        $last_name_validation = validate_order_ad_last_name($order_payload['address']['last_name']);
        if(!$last_name_validation['success'])
        {
            respond(false , 400 , null , null , $last_name_validation['message']);
        }    
        
        $email_validation = validate_order_ad_email($order_payload['address']['email']);
        if(!$email_validation['success'])
        {
            respond(false , 400 , null , null , $email_validation['message']);
        }    

        $phone_number_validation = validate_order_ad_phone($order_payload['address']['phone_number']);
        if(!$phone_number_validation['success'])
        {
            respond(false , 400 , null , null , $phone_number_validation['message']);
        }    
        
        $state_validation = validate_order_ad_state($order_payload['address']['state']);
        if(!$state_validation['success'])
        {
            respond(false , 400 , null , null , $state_validation['message']);
        }    
        
        $city_validation = validate_order_ad_city($order_payload['address']['city']);
        if(!$city_validation['success'])
        {
            respond(false , 400 , null , null , $city_validation['message']);
        }    
        
        $ad_line1_validation = validate_order_ad_line1($order_payload['address']['address_line1']);
        if(!$ad_line1_validation['success'])
        {
            respond(false , 400 , null , null , $ad_line1_validation['message']);
        }    

        $ad_line2_validation = validate_order_ad_line2($order_payload['address']['address_line2']);
        if(!$ad_line2_validation['success'])
        {
            respond(false , 400 , null , null , $ad_line2_validation['message']);
        }    

        $additional_notes_validation = validate_order_ad_notes($order_payload['address']['additional_notes']);
        if(!$additional_notes_validation['success'])
        {
            respond(false , 400 , null , null , $additional_notes_validation['message']);
        }    
    }

    $order_lines_validation = validate_order_lines($conn , $order_payload['order_lines']);
    if(!$order_lines_validation['success'])
    {
        respond(false , 400 , null , null , $order_lines_validation['message']);
    }   

    $DB_user_id = (int) $order_payload['order']['user_id'];
    $order_lines = $order_payload['order_lines'];

    // ! Begin transaction
    $conn->begin_transaction();

    try
    {
        // Address situation
        if(empty($order_payload['address']['existing_address_id']) || $order_payload['address']['existing_address_id'] === "null")
        {
            // Admin typed a new address
            $DB_address_id = insert_new_address($conn , $order_payload['address'] , 1);    
        } 
        else 
        {
            // There is an existing address id
            $DB_address_id = (int) $order_payload['address']['existing_address_id'];

            // Validate that address belong to the user
            $address_ownership_validation = validate_address_ownership($conn , $DB_user_id , $DB_address_id);
            if(!$address_ownership_validation['success'])
            {
                throw new Exception($address_ownership_validation['message']);
            }
        }

        // Order code
        $order_code = generate_order_code();

        // ! Insert Order meta data
        $order_id = insert_new_order($conn , $order_code , $order_payload['order'] , $DB_user_id , $DB_address_id);

        // ! Insert Order Lines
        foreach($order_lines as $line)
        {
            insert_order_line($conn , $line , $order_id);

            $book_id = (int) $line['bookId'];
            $quantity = (int) $line['quantity'];

            $current_book_data = get_book_data($conn , $book_id);
            
            // ! Update Stock
            $new_stock = decrease_book_stock($conn , $book_id , $quantity);
            
            handle_stock_transition($conn , $book_id , $current_book_data['title'] , $current_book_data['old_stock'] , $new_stock , $email_queue);
        }

        $conn->commit();

        // Create notifications : one for the order , one for the stock
        insert_admin_notification($conn , 'new_order' , 'New Order Placed' , "Order {$order_code} was placed" , 'order' , $order_id);

        foreach($email_queue as $email)
        {
            sendEmail($email['type'] , $email['subject'] , $email['data']);
        }

        $order = get_single_order_by_id($conn , $order_id);
        $order_lines = get_order_lines_by_order($conn , $order_id);

        $emailData = [
            'order_data' => $order['value'],
            'order_lines' => $order_lines
        ];

        sendEmail('new_order' , "📦 New Order is Placed - #$order_id - $order_code" , $emailData);
        
        respond(true , 201 , null , null , 'Order created successfully');
    } catch (Exception $e)
    {
        $conn->rollback();

        respond(true , 500 , null , null , 'Something went wrong creating an order');
    }
}
else
{
    respond(true , 400 , null , null , 'Wrong method used in adding order');
}











?>