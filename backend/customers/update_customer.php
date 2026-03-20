<?php

header("Content-Type: application/json");

require_once __DIR__ . '../../../configuration/session.php';
require_once __DIR__ . '/../helpers.php';

if(!isset($_SESSION['user_id']))
{
    respond(false , 401 , null , null , 'Not authorized to use api');
}

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    require_once __DIR__ . '../../../configuration/database.php';
    require_once __DIR__ . '/helpers/customers_helpers.php';
    require_once __DIR__ . '/helpers/customers_db_helpers.php';
    require_once __DIR__ . '/validators/customer_db_validators.php';
    require_once __DIR__ . '/validators/customer_validators.php';

    // Collect data
    $account_payload = extract_account_data($_POST);

    // Get user previous data
    $user_data = get_customer_data($conn , $_SESSION['user_id']);

    $name_changed = $account_payload['name'] === $user_data['name'] ? false : true;
    $email_changed = $account_payload['email'] === $user_data['email'] ? false : true;
    $phone_changed = $account_payload['phone_number'] === $user_data['phone_number'] ? false : true;

    if(!$name_changed && !$email_changed && !$phone_changed && !$account_payload['current_password'])
    {
        respond(false , 401 , null , null , 'No data to be changed');
    }

    // Initial query 
    $types = "";
    $params = [];
    $fields = [];

    /*
    Validate name , email , phone , currentPass if correct , newPass + confirm pass
    */

    if($account_payload['name'] && $name_changed)
    {
        $name_validation = validate_customer_name($account_payload['name']);
        if(!$name_validation['success'])
        {
            respond(false , 400 , null , null , $name_validation);
        }

        $fields[] = "name = ?";
        $types .= "s";
        $params[] = $account_payload['name'];
    }

    if($account_payload['email'] && $email_changed)
    {
        $email_validation = validate_customer_email($account_payload['email']);
        if(!$email_validation['success'])
        {
            respond(false , 400 , null , null , $email_validation);
        }

    }

    if($account_payload['phone_number'] && $phone_changed)
    {
        $phone_validation = validate_customer_phone($account_payload['phone_number']);
        if(!$phone_validation['success'])
        {
            respond(false , 400 , null , null , $phone_validation);
        }


    }

    // Verify credentials (email , phone number) doesnt exist in db
    if($email_changed)
    {
        $email_existence_validation = validate_email_existence($conn , $account_payload['email']);
        if(!$email_existence_validation['success'])
        {
            respond(false , 404 , null , null , $email_existence_validation);
        }

        $fields[] = "email = ?";
        $types .= "s";
        $params[] = $account_payload['email'];
    }

    if($phone_changed)
    {
        $phone_existence_validation = validate_phone_existence($conn , $account_payload['phone_number']);
        if(!$phone_existence_validation['success'])
        {
            respond(false , 404 , null , null , $phone_existence_validation);
        }

        $fields[] = "phone_number = ?";
        $types .= "s";
        $params[] = $account_payload['phone_number'];
    }

    if($account_payload['current_password'])
    {
        // Verify current password is correct
        if(!password_verify($account_payload['current_password'] , $user_data['password'])) {
            respond(false , 400 , null , null , 'Incorrect credentials');
        }

        // Validate new password and confirmation
        if($account_payload['new_password'] !== $account_payload['confirm_password'])
        {
            respond(false , 400 , null , null , 'Password confirmation is incorrect');
        }

        $password_validation = validate_customer_password($account_payload['new_password']);
        if(!$password_validation['success'])
        {
            respond(false , 400 , null , null , $password_validation['message']);
        }

        $hashedPassword = password_hash($account_payload['new_password'] , PASSWORD_DEFAULT);

        $fields[] = "password = ?";
        $types .= "s";
        $params[] = $hashedPassword;
    }

    $query = "UPDATE users SET " . implode(", " , $fields) . " WHERE id = ?";
    $types .= "i";
    $params[] = $_SESSION['user_id'];

    $update_stmt = $conn->prepare($query);
    $update_stmt->bind_param($types , ...$params);

    if($update_stmt->execute())
    {
        $update_stmt->close();
        $conn->close();

        respond(true , 200 , null , null , 'Account updated successfully');
    } else {
        $update_stmt->close();
        $conn->close();

        respond(false , 500 , null , null , 'Something went wrong in updating profile');
    }
}
else
{
    respond(false , 400 , null , null , 'Wrong method used in updating customer');
}
?>