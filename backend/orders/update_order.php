<?php 

require __DIR__ . '/../../configuration/session.php';
require_once __DIR__ . '/../helpers.php';
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    respond(false , 200 , null , null , 'Not authorized to use api');
}

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    require_once __DIR__ . '/../../configuration/database.php';
    require_once __DIR__ . '/validators/order_db_validators.php';
    require_once __DIR__ . '/validators/order_validators.php';
    require_once __DIR__ . '/validators/order_lines_validators.php';
    require_once __DIR__ . '/helpers/order_db_helpers.php';
    require_once __DIR__ . '/helpers/order_helpers.php';
    require_once __DIR__ . '/../customers/validators/customer_validators.php';
    require_once __DIR__ . '/../notifications/admin_notifications/helpers/admin_notifcations_db_helpers.php';
    require_once __DIR__ . '/../email/email_config.php';

// 1. Fetch order (must exist)
// 2. Check status allows edit
// 3. Fetch existing order_items
// 4. Compare with new order_lines
// 5. Apply stock diffs
// 6. Update order_items
// 7. Update order total


/*

Data im expecting to get in EDIT ORDER :
- Price
- Status
- First Name (AD)
- Last Name (AD)
- Email (AD)
- Phone (AD)
- State (AD)
- City (AD)
- Line 1 (AD)
- Line 2 (AD)
- Additional notes (AD)
- Order Lines 


*/

    $email_queue = [];

    // Extract the payload of the request
    $order_payload = extract_order_payload($_POST);

    $order_id_validation = validate_order_id($order_payload['order']['id']);
    if(!$order_id_validation['success'])
    {
        respond(false , 400 , null , null , $order_id_validation['message']);
    }

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

    $order_lines_validation = validate_order_lines($conn , $order_payload['order_lines']);
    if(!$order_lines_validation['success'])
    {
        respond(false , 400 , null , null , $order_lines_validation['message']);
    }  

    // Collect data 
    $order_id = $order_id_validation['value'];
    $user_id = (int) $order_payload['order']['user_id'];
    $order_status = $order_status_validation['value'];
    $order_price = $order_total_price_validation['value'];
    $order_first_name_ad = $first_name_validation['value'];
    $order_last_name_ad = $last_name_validation['value'];
    $order_email_ad = $email_validation['value'];
    $order_phone_number_ad = $phone_number_validation['value'];
    $order_state_ad = $state_validation['value'];
    $order_city_ad = $city_validation['value'];
    $order_address_line1_ad = $ad_line1_validation['value'];
    $order_address_line2_ad = $ad_line2_validation['value'];
    $order_additional_notes_ad = $additional_notes_validation['value'];

    $conn->begin_transaction();

    try
    {
        // Fetch the order
        $order = get_single_order_by_id($conn , $order_id);

        if(!$order['success'])
        {
            throw new Exception($order['message']);
        }

        // Check order allowed to be edited
        if(!is_order_editable($order['value']))
        {
            throw new Exception('Order is not allowed to be edited');
        }

        // Fetch order lines that belong to that order
        $existing_order_lines = get_order_lines_by_order($conn , $order_id);

        // Groups of operations
        $to_insert = [];
        $to_update = [];
        $to_delete = [];

        $old_map = [];
        foreach ($existing_order_lines as $line) {
            $old_map[$line['book_id']] = $line;
        }

        $new_map = [];
        foreach($order_payload['order_lines'] as $line)
        {
            $book_id = (int) $line['bookId'];

            if(isset($new_map[$book_id]))
            {
                throw new Exception('Duplicate book in order lines');
            }

            $new_map[$book_id] = $line;
        }

        // Collect line to insert
        foreach($new_map as $book_id => $new_line)
        {
            if(!isset($old_map[$book_id]))
            {
                $to_insert[] = $new_line;
            }
        }

        // Collect line to delete
        foreach($old_map as $book_id => $old_line)
        {
            if(!isset($new_map[$book_id]))
            {
                $to_delete[] = $old_line;
            }
        }

        // Collect line to update
        foreach($new_map as $book_id => $new_line)
        {
            if(isset($old_map[$book_id]))
            {
                $old_qty = (int) $old_map[$book_id]['quantity'];
                $unit_price = (float) $new_map[$book_id]['unitPrice'];
                $new_qty = (int) $new_line['quantity'];

                if($old_qty !== $new_qty)
                {
                    $to_update[] = [
                        'book_id' => $book_id,
                        'unitPrice' => $unit_price,
                        'old_qty' => $old_qty,
                        'new_qty' => $new_qty,
                        'delta' => $new_qty - $old_qty
                    ];
                }
            }
        }

        // Validate stock for both insert and update
        validate_stock($conn , $to_insert , $to_update);

        // Delete order lines
        foreach($to_delete as $line)
        {
            delete_order_line($conn , $order_id , $line['book_id']);
            $current_book_data = get_book_data($conn , $line['book_id']);

            $new_stock = increase_book_stock($conn , $line['book_id'] , $line['quantity']);
            // Handle book transition to emails and notifications
            handle_stock_transition($conn , $line['book_id'] , $current_book_data['title'] , $current_book_data['old_stock'] , $new_stock , $email_queue);
        }

        // Update order lines
        foreach($to_update as $line)
        {
            update_order_line($conn , $order_id , $line['book_id'] , $line['unitPrice'] , $line['new_qty']);
            $current_book_data = get_book_data($conn , $line['book_id']);

            if($line['delta'] > 0)
            {
                $new_stock = decrease_book_stock($conn , $line['book_id'] , $line['delta']);
                handle_stock_transition($conn , $line['book_id'] , $current_book_data['title'] , $current_book_data['old_stock'] , $new_stock , $email_queue);
            }
            else
            {   
                $new_stock = increase_book_stock($conn , $line['book_id'] , abs($line['delta']));
                handle_stock_transition($conn , $line['book_id'] , $current_book_data['title'] , $current_book_data['old_stock'] , $new_stock , $email_queue);
                
            }
        }

        // Insert New Lines
        foreach($to_insert as $line)
        {
            insert_order_line($conn , $line , $order_id);
            $current_book_data = get_book_data($conn , $line['bookId']);
            
            $new_stock = decrease_book_stock($conn , $line['bookId'] , $line['quantity']);
            
            handle_stock_transition($conn , $line['bookId'] , $current_book_data['title'] , $current_book_data['old_stock'] , $new_stock , $email_queue);
        }   


        // If admin typed a complete new address
        if(empty($order_payload['address']['existing_address_id']) || $order_payload['address']['existing_address_id'] === "null")
        {
            $address_id = insert_new_address($conn , $order_payload['address'] , 1);
            update_order_meta($conn , $order_id , $order_status , $order_price , $address_id);

        }
        // Admin picked another existing address id
        elseif($order['value']['address_id'] !== (int) $order_payload['address']['existing_address_id'])
        {
            $address_id = (int) $order_payload['address']['existing_address_id'];

            $address_ownership_validation = validate_address_ownership($conn , $user_id , $address_id);
            if(!$address_ownership_validation['success'])
            {
                throw new Exception($address_ownership_validation['message']);
            }

            update_order_meta($conn , $order_id , $order_status , $order_price , $address_id);
        }

        update_order_meta($conn , $order_id , $order_status , $order_price );

        $conn->commit();

        
        insert_admin_notification($conn , 'order_status' , 'Order is updated' , "Order $order_id is updated" , 'order' , $order_id);

        foreach($email_queue as $email)
        {
            sendEmail($email['type'] , $email['subject'] , $email['data']);
        }

        $new_order_data = get_single_order_by_id($conn , $order_id)['value'];
        $new_order_lines = get_order_lines_by_order($conn , $order_id);

        $emailData = [
            'order_data' => $new_order_data,
            'order_lines' => $new_order_lines
        ];

        sendEmail('order_update' , "🟦 Your Order is updated - #{$order['value']['id']} -  {$order['value']['order_code']}" , $emailData , $order_email_ad);

        respond(true , 200 , null , null , 'Order is updated');
    }
    catch (Exception $e)
    {
        $conn->rollback();

        respond(false , 500 , null , null , 'Something went wrong updating order');
    }
}
else
{
    respond(false , 400 , null , null , 'Wrong method used');
}


?>