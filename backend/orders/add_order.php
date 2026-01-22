<?php

header("Content-Type: application/json");
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helpers.php';
require_once __DIR__ . '/validators/order_validators.php';
require_once __DIR__ . '/validators/order_db_validators.php';
require_once __DIR__ . '/validators/order_lines_validators.php';
require_once __DIR__ . '/helpers/order_helpers.php';
require_once __DIR__ . '/helpers/order_db_helpers.php';


$order_payload = extract_order_payload($_POST);

// Data validation

// Validate order meta data

// Customer id
$order_customer_id_result = validate_order_customer($order_payload['order']['user_id']);
if(!$order_customer_id_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $order_customer_id_result['message']
    ]);
    exit;
}

// Status
$order_status_result = validate_order_status($order_payload['order']['status']);
if(!$order_status_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $order_status_result['message']
    ]);
    exit;
}

// Total Price
$order_total_price_result = validate_order_price($order_payload['order']['total_order_price']);
if(!$order_total_price_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $order_total_price_result['message']
    ]);
    exit;
}

// Date Added
$order_date_result = validate_order_date($order_payload['order']['date_added']);
if(!$order_date_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $order_date_result['message']
    ]);
    exit;
}

// Existing address
if($order_payload['address']['existing_address_id'])
{
    // Validate address id
    $order_address_id_result = validate_order_existing_address($order_payload['address']['existing_address_id']);
    if(!$order_address_id_result['success'])
    {
        echo json_encode([
            'success' => false,
            'message' => $order_address_id_result['message']
        ]);
        exit;
    }
    
    // Get the address
}
else
{
    // Validate address details

    // First Name
    $order_first_name_result = validate_order_ad_first_name($order_payload['address']['first_name']);
    if(!$order_first_name_result['success'])
    {
        echo json_encode([
            'success' => false,
            'message' => $order_first_name_result['message']
        ]);
        exit;
    }    
    
    // Last name
    $order_last_name_result = validate_order_ad_last_name($order_payload['address']['last_name']);
    if(!$order_last_name_result['success'])
    {
        echo json_encode([
            'success' => false,
            'message' => $order_last_name_result['message']
        ]);
        exit;
    }    
    
    // Email
    $order_email_result = validate_order_ad_email($order_payload['address']['email']);
    if(!$order_email_result['success'])
    {
        echo json_encode([
            'success' => false,
            'message' => $order_email_result['message']
        ]);
        exit;
    }    

    // Phone number
    $order_phone_number_result = validate_order_ad_phone($order_payload['address']['phone_number']);
    if(!$order_phone_number_result['success'])
    {
        echo json_encode([
            'success' => false,
            'message' => $order_phone_number_result['message']
        ]);
        exit;
    }    
    
    // State
    $order_state_result = validate_order_ad_state($order_payload['address']['state']);
    if(!$order_state_result['success'])
    {
        echo json_encode([
            'success' => false,
            'message' => $order_state_result['message']
        ]);
        exit;
    }    
    
    // City
    $order_city_result = validate_order_ad_city($order_payload['address']['city']);
    if(!$order_city_result['success'])
    {
        echo json_encode([
            'success' => false,
            'message' => $order_city_result['message']
        ]);
        exit;
    }    
    
    // Address Line 1 
    $order_address_line1_result = validate_order_ad_line1($order_payload['address']['address_line1']);
    if(!$order_address_line1_result['success'])
    {
        echo json_encode([
            'success' => false,
            'message' => $order_address_line1_result['message']
        ]);
        exit;
    }    
    
    // Address Line 2
    $order_address_line2_result = validate_order_ad_line2($order_payload['address']['address_line2']);
    if(!$order_address_line2_result['success'])
    {
        echo json_encode([
            'success' => false,
            'message' => $order_address_line2_result['message']
        ]);
        exit;
    }    
    
    // Additonal notes
    $order_additional_notes_result = validate_order_ad_notes($order_payload['address']['additional_notes']);
    if(!$order_additional_notes_result['success'])
    {
        echo json_encode([
            'success' => false,
            'message' => $order_additional_notes_result['message']
        ]);
        exit;
    }    
}

$order_lines_result = validate_order_lines($conn , $order_payload['order_lines']);
if(!$order_lines_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $order_lines_result['message']
    ]);
    exit;
}   

$DB_user_id = (int) $order_payload['order']['user_id'];
$order_lines = $order_payload['order_lines'];

// here we start a transaction to get the ability of rollback and commit
$conn->begin_transaction();

try
{
    // Address situation
    if(empty($order_payload['address']['existing_address_id']) || $order_payload['address']['existing_address_id'] === "null")
    {
        // Admin typed a new address
        $DB_address_id = insert_new_address($conn , $order_payload['address']);    
    } 
    else 
    {
        // There is an existing address id
        $DB_address_id = (int) $order_payload['address']['existing_address_id'];

        $address_ownership_validation = validate_address_ownership($conn , $DB_user_id , $DB_address_id);
        if(!$address_ownership_validation['success'])
        {
            throw new Exception($address_ownership_validation['message']);
        }
    }

    // Order code
    $order_code = generate_order_code();

    // Insert Order itself
    $order_id = insert_new_order($conn , $order_code , $order_payload['order'] , $DB_user_id , $DB_address_id);


    // Inserting Order Lines
    foreach($order_lines as $line)
    {
        // Insert a line
        insert_order_line($conn , $line , $order_id);

        $book_id = (int) $line['bookId'];
        $quantity = (int) $line['quantity'];
    
        // Update Stock
        decrease_book_stock($conn , $book_id , $quantity);
    }

    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Order created successfully'
    ]);
    exit;
} catch (Exception $e)
{
    $conn->rollback();

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}


?>