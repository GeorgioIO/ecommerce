<?php

require_once __DIR__ . '../../../configuration/session.php';
require_once __DIR__ . '/../helpers.php';
header("Content-Type: application/json");

if(!isset($_SESSION['user_id']))
{
    respond(false , 401 , null , null , 'Not authorized to use api');
}

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
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
        respond(false , 400 , null , null , $first_name_validation['message']);
    }

    $last_name_validation = validate_customer_ad_last_name($address_data['last_name']);
    if(!$last_name_validation['success'])
    {
        respond(false , 400 , null , null , $last_name_validation['message']);
    }

    $email_validation = validate_customer_ad_email($address_data['email']);
    if(!$email_validation['success'])
    {
        respond(false , 400 , null , null , $email_validation['message']);
    }

    $phone_validation = validate_customer_ad_phone($address_data['phone_number']);
    if(!$phone_validation['success'])
    {
        respond(false , 400 , null , null , $phone_validation['message']);
    }

    $state_validation = validate_customer_ad_state($address_data['state']);
    if(!$state_validation['success'])
    {
        respond(false , 400 , null , null , $state_validation['message']);
    }

    $city_validation = validate_customer_ad_city($address_data['city']);
    if(!$city_validation['success'])
    {
        respond(false , 400 , null , null , $city_validation['message']);
    }

    $address_line_1_validation = validate_customer_ad_line1($address_data['address_line1']);
    if(!$address_line_1_validation['success'])
    {
        respond(false , 400 , null , null , $address_line_1_validation['message']);
    }

    $address_line_2_validation = validate_customer_ad_line2($address_data['address_line2']);
    if(!$address_line_2_validation['success'])
    {
        respond(false , 400 , null , null , $address_line_2_validation['message']);
    }
    $additional_notes_validation = validate_customer_ad_notes($address_data['additional_notes']);
    if(!$additional_notes_validation['success'])
    {
        respond(false , 400 , null , null , $additional_notes_validation['message']);
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

                respond(false , 200 , null , null , 'Address is saved');
            }
            else
            {
                $conn->rollback();

                respond(false , 500 , null , null , 'Something went wrong in saving address');
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
        
        respond(false , 201 , null , null , 'Address is created');
    }
    catch (Exception $e)
    {
        $conn->rollback();

        respond(false , 500 , null , null , 'Something went wrong creating address');
    }
}
else
{
    respond(false , 400 , null , null , 'Wrong method used');
}


?>