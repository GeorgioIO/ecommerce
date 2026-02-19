<?php

header("Content-Type: application/json");

require_once __DIR__ . '../../../configuration/session.php';

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
    echo json_encode([
        'success' => false,
        'status' => 400,
        'message' => 'No data to be changed'
    ]);
    exit;
}

// Initial query 
$types = "";
$params = [];
$fields = [];
// Validate data 

/*
Validate name , email , phone , currentPass if correct , newPass + confirm pass
*/

if($account_payload['name'] && $name_changed)
{
    $name_validation = validate_customer_name($account_payload['name']);
    if(!$name_validation['success'])
    {
        echo json_encode([
            'success' => false,
            'status' => 400,
            'message' => $name_validation['message']
        ]);
        exit;
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
        echo json_encode([
            'success' => false,
            'status' => 400,
            'message' => $email_validation['message']
        ]);
        exit;
    }

}

if($account_payload['phone_number'] && $phone_changed)
{
    $phone_validation = validate_customer_phone($account_payload['phone_number']);
    if(!$phone_validation['success'])
    {
        echo json_encode([
            'success' => false,
            'status' => 400,
            'message' => $phone_validation['message']
        ]);
        exit;
    }


}

// Verify credentials (email , phone number) doesnt exist in db
if($email_changed)
{
    $email_existence_validation = validate_email_existence($conn , $account_payload['email']);
    if(!$email_existence_validation['success'])
    {
        echo json_encode([
            'success' => false,
            'status' => 400,
            'message' => $email_existence_validation['message']
        ]);
        exit;
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
        echo json_encode([
            'success' => false,
            'status' => 400,
            'message' => $phone_existence_validation['message']
        ]);
        exit;
    }

    $fields[] = "phone_number = ?";
    $types .= "s";
    $params[] = $account_payload['phone_number'];
}

if($account_payload['current_password'])
{
    // Verify current password is correct
    if(!password_verify($account_payload['current_password'] , $user_data['password'])) {
        echo json_encode([
            'success' => false,
            'status' => 400,
            'message' => 'Password is incorrect'
        ]);
        exit;
    }

    // Validate new password and confirmation
    if($account_payload['new_password'] !== $account_payload['confirm_password'])
    {
        echo json_encode([
            'success' => false,
            'status' => 400,
            'message' => 'Password confirmation is incorrect'
        ]);
        exit;
    }

    $password_validation = validate_customer_password($account_payload['new_password']);
    if(!$password_validation['success'])
    {
        echo json_encode([
            'success' => false,
            'status' => 400,
            'message' => $password_validation['message']
        ]);
        exit;
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

    echo json_encode([
        'success' => true,
        'status' => 200,
        'message' => 'Account update successfully'
    ]);
    exit;
} else {
    $update_stmt->close();
    $conn->close();

    echo json_encode([
        'success' => false,
        'status' => 500,
        'message' => 'Problem in updating acccount'
    ]);
    exit;
}
?>