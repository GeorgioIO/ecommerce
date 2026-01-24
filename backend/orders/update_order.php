<?php 

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/validators/order_db_validators.php';
require_once __DIR__ . '/validators/order_validators.php';
require_once __DIR__ . '/validators/order_lines_validators.php';
require_once __DIR__ . '/helpers/order_db_helpers.php';
require_once __DIR__ . '/helpers/order_helpers.php';
require_once __DIR__ . '/../customers/validators/customer_validators.php';

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

// Extract the payload of the request
$order_payload = extract_order_payload($_POST);

$order_id_result = validate_order_id($order_payload['order']['id']);
if(!$order_id_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $order_id_result['message']
    ]);
    exit;
}

$order_customer_id_result = validate_customer_id($order_payload['order']['user_id']);
if(!$order_customer_id_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $order_customer_id_result['message']
    ]);
    exit;
}


$order_status_result = validate_order_status($order_payload['order']['status']);
if(!$order_status_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $order_status_result['message']
    ]);
    exit;
}

$order_total_price_result = validate_order_price($order_payload['order']['total_order_price']);
if(!$order_total_price_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $order_total_price_result['message']
    ]);
    exit;
}

$order_first_name_result = validate_order_ad_first_name($order_payload['address']['first_name']);
if(!$order_first_name_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $order_first_name_result['message']
    ]);
    exit;
}    

$order_last_name_result = validate_order_ad_last_name($order_payload['address']['last_name']);
if(!$order_last_name_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $order_last_name_result['message']
    ]);
    exit;
}   

$order_email_result = validate_order_ad_email($order_payload['address']['email']);
if(!$order_email_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $order_email_result['message']
    ]);
    exit;
}    


$order_phone_number_result = validate_order_ad_phone($order_payload['address']['phone_number']);
if(!$order_phone_number_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $order_phone_number_result['message']
    ]);
    exit;
}    


$order_state_result = validate_order_ad_state($order_payload['address']['state']);
if(!$order_state_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $order_state_result['message']
    ]);
    exit;
}    


$order_city_result = validate_order_ad_city($order_payload['address']['city']);
if(!$order_city_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $order_city_result['message']
    ]);
    exit;
}    


$order_address_line1_result = validate_order_ad_line1($order_payload['address']['address_line1']);
if(!$order_address_line1_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $order_address_line1_result['message']
    ]);
    exit;
}    


$order_address_line2_result = validate_order_ad_line2($order_payload['address']['address_line2']);
if(!$order_address_line2_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $order_address_line2_result['message']
    ]);
    exit;
}    

$order_additional_notes_result = validate_order_ad_notes($order_payload['address']['additional_notes']);
if(!$order_additional_notes_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $order_additional_notes_result['message']
    ]);
    exit;
}    

// Validate order lines
$order_lines_result = validate_order_lines($conn , $order_payload['order_lines']);
if(!$order_lines_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $order_lines_result['message']
    ]);
    exit;
}  

// Collect data 
$order_id = $order_id_result['value'];
$DB_user_id = (int) $order_payload['order']['user_id'];
$DB_order_status = $order_status_result['value'];
$DB_order_price = $order_total_price_result['value'];
$DB_order_first_name_ad = $order_first_name_result['value'];
$DB_order_last_name_ad = $order_last_name_result['value'];
$DB_order_email_ad = $order_email_result['value'];
$DB_order_phone_number_ad = $order_phone_number_result['value'];
$DB_order_state_ad = $order_state_result['value'];
$DB_order_city_ad = $order_city_result['value'];
$DB_order_address_line1_ad = $order_address_line1_result['value'];
$DB_order_address_line2_ad = $order_address_line2_result['value'];
$DB_order_additional_notes_ad = $order_additional_notes_result['value'];

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
        increase_book_stock($conn , $line['book_id'] , $line['quantity']);
    }

    // Update order lines
    foreach($to_update as $line)
    {
        update_order_line($conn , $order_id , $line['book_id'] , $line['unitPrice'] , $line['new_qty']);

        if($line['delta'] > 0)
        {
            decrease_book_stock($conn , $line['book_id'] , $line['delta']);
        }
        else
        {
            increase_book_stock($conn , $line['book_id'] , abs($line['delta']));
        }
    }

    // Insert New Lines
    foreach($to_insert as $line)
    {
        insert_order_line($conn , $line , $order_id);

        decrease_book_stock($conn , $line['bookId'] , $line['quantity']);
    }


    // If admin typed a complete new address
    if(empty($order_payload['address']['existing_address_id']) || $order_payload['address']['existing_address_id'] === "null")
    {
        $DB_address_id = insert_new_address($conn , $order_payload['address']);

        update_order_meta($conn , $order_id , $DB_order_status , $DB_order_price , $DB_address_id);

    }
    // Admin picked another existing address id
    elseif($order['value']['address_id'] !== (int) $order_payload['address']['existing_address_id'])
    {
        $DB_address_id = (int) $order_payload['address']['existing_address_id'];

        $address_ownership_validation = validate_address_ownership($conn , $DB_user_id , $DB_address_id);
        if(!$address_ownership_validation['success'])
        {
            throw new Exception($address_ownership_validation['message']);
        }

        update_order_meta($conn , $order_id , $DB_order_status , $DB_order_price , $DB_address_id);
    }

    update_order_meta($conn , $order_id , $DB_order_status , $DB_order_price );

    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Order Updated successfully'
    ]);
    exit;
}
catch (Exception $e)
{
    $conn->rollback();

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}


?>