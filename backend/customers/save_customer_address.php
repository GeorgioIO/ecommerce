<?php

require_once __DIR__ . '../../../configuration/session.php';

header("Content-Type: application/json");

if(!isset($_SESSION['user_id']))
{
    echo json_encode([
        'success' => false,
        'status' => 401,
        'message' => 'Unauthorized'
    ]);
    exit;
}

require_once __DIR__ . '../../../configuration/database.php';
require_once __DIR__ . '/validators/customer_validators.php';
require_once __DIR__ . '/helpers/customers_helpers.php';

// Collect data
$address_data = extract_address_data($_POST);
$user_id = $_SESSION['user_id'];

// Validate data
$first_name_validation = validate_customer_ad_first_name($address_data['first_name']);
if(!$first_name_validation['success'])
{
    echo json_encode([
        'success' => false,
        'status' => 400,
        'message' => $first_name_validation['message']
    ]);
    exit;
}

$last_name_validation = validate_customer_ad_last_name($address_data['last_name']);
if(!$last_name_validation['success'])
{
    echo json_encode([
        'success' => false,
        'status' => 400,
        'message' => $last_name_validation['message']
    ]);
    exit;
}

$email_validation = validate_customer_ad_email($address_data['email']);
if(!$email_validation['success'])
{
    echo json_encode([
        'success' => false,
        'status' => 400,
        'message' => $email_validation['message']
    ]);
    exit;
}

$phone_validation = validate_customer_ad_phone($address_data['phone_number']);
if(!$phone_validation['success'])
{
    echo json_encode([
        'success' => false,
        'status' => 400,
        'message' => $phone_validation['message']
    ]);
    exit;
}

$state_validation = validate_customer_ad_state($address_data['state']);
if(!$state_validation['success'])
{
    echo json_encode([
        'success' => false,
        'status' => 400,
        'message' => $state_validation['message']
    ]);
    exit;
}

$city_validation = validate_customer_ad_city($address_data['city']);
if(!$city_validation['success'])
{
    echo json_encode([
        'success' => false,
        'status' => 400,
        'message' => $city_validation['message']
    ]);
    exit;
}

$address_line_1_validation = validate_customer_ad_line1($address_data['address_line1']);
if(!$address_line_1_validation['success'])
{
    echo json_encode([
        'success' => false,
        'status' => 400,
        'message' => $address_line_1_validation['message']
    ]);
    exit;
}

$address_line_2_validation = validate_customer_ad_line2($address_data['address_line2']);
if(!$address_line_2_validation['success'])
{
    echo json_encode([
        'success' => false,
        'status' => 400,
        'message' => $address_line_2_validation['message']
    ]);
    exit;
}
$additional_notes_validation = validate_customer_ad_notes($address_data['additional_notes']);
if(!$additional_notes_validation['success'])
{
    echo json_encode([
        'success' => false,
        'status' => 400,
        'message' => $additional_notes_validation['message']
    ]);
    exit;
}


$first_name = $first_name_validation['value'];
$last_name = $last_name_validation['value'];
$email = $email_validation['value'];
$phone_number = $phone_validation['value'];
$state = $state_validation['value'];
$city = $city_validation['value'];
$address_line1 = $address_line_1_validation['value'];
$address_line2 = $address_line_2_validation['value'];
$additional_notes = $additional_notes_validation['value'];

// check if user has an address
$check_query = "SELECT address_id FROM users WHERE id = ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("i" , $user_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

// Request
$conn->begin_transaction();

try
{
    // User already has an address so goal is to update
    if(isset($result->fetch_assoc()['address_id']))
    {
        $address_id = $result->fetch_assoc()['address_id'];
        $update_query = "
            UPDATE shipping_addresses
            SET 
                first_name = ?,
                last_name = ?,
                email = ?,
                phone_number = ?,
                state = ?,
                city = ?,
                address_line1 = ?,
                address_line2 = ?,
                additional_notes = ?
            WHERE id = ?;
        ";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("sssssssssi" , $first_name , $last_name , $email , $phone_number , $state , $city , $address_line1 , $address_line2 , $additional_notes , $address_id);
        
        if($update_stmt->execute())
        {
            $conn->commit();

            echo json_encode(
                ['success' => true,
                'status' => 200,
                'message' => 'Address is updated successfully here ' . $result->num_rows
                ]
            );
            exit;
        }
        else
        {
            $conn->rollback();

            echo json_encode(
                ['success' => false,
                'status' => 500,
                'message' => 'Updating address failed']
            );
            exit;
        }

    }

    // User doesnt have an address so goal is to insert
    $is_active = 1;
    $insert_address_query = "
        INSERT INTO
            shipping_addresses
        (first_name , last_name , email , phone_number , state , city , address_line1, address_line2 , additional_notes , is_active) VALUES (? , ? , ? , ? , ? , ? , ? , ? , ? , ?)";
    $insert_address_stmt = $conn->prepare($insert_address_query);
    $insert_address_stmt->bind_param("sssssssssi" , $first_name , $last_name , $email , $phone_number , $state , $city , $address_line1 , $address_line2 , $additional_notes , $is_active);
    $insert_address_stmt->execute();
    $new_address_id = $conn->insert_id;


    $insert_user_address = "UPDATE users SET address_id = ? WHERE id = ?";
    $insert_user_address_stmt = $conn->prepare($insert_user_address);
    $insert_user_address_stmt->bind_param("ii" , $new_address_id  , $user_id);
    $insert_user_address_stmt->execute();

    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'status' => 200,
        'message' => 'Address created successfully'
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
    exit();
}




?>