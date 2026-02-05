<?php

require_once __DIR__ . '/../../configuration/session.php';
require_once __DIR__ . '/../../configuration/database.php';
require_once __DIR__ . '/../customers/helpers/customers_db_helpers.php';
require_once __DIR__ . '/../customers/helpers/customers_helpers.php';
require_once __DIR__ . '/../customers/validators/customer_validators.php';

if (
    empty($_POST['username']) ||
    empty($_POST['email']) ||
    empty($_POST['password'])
) {
    exit;
}

// Collect data
$username = $_POST['username'];
$email = $_POST['email'];
$phone_number = $_POST['phone'] ?? null;
$password = $_POST['password'];


// Sanitize data
$email = strtolower($email);
$phone_number = trim($phone_number);
$phone_number = $phone_number === null ? '' : $phone_number;

// Validate data
$name_validation = validate_customer_name($username);
if(!$name_validation['success'])
{
    $_SESSION['redirect-message'] = $name_validation['message'];
    $_SESSION['redirect-message-type'] = 'error';
    header("Location: /ecommerce/frontend/storefront/pages/my-account.php");
    exit;
}

$email_validation = validate_customer_email($email);
if(!$email_validation['success'])
{
    $_SESSION['redirect-message'] = $email_validation['message'];
    $_SESSION['redirect-message-type'] = 'error';
    header("Location: /ecommerce/frontend/storefront/pages/my-account.php");
    exit;
}

$phone_validation = validate_customer_phone($phone_number);
if(!$phone_validation['success'])
{
    $_SESSION['redirect-message'] = $phone_validation['message'];
    header("Location: /ecommerce/frontend/storefront/pages/my-account.php");
    exit;
}

$password_validation = validate_customer_password($password);
if(!$password_validation['success'])
{
    $_SESSION['redirect-message'] = $password_validation['message'];
    $_SESSION['redirect-message-type'] = 'error';
    header("Location: /ecommerce/frontend/storefront/pages/my-account.php");
    exit;
}

$creds_existence_validation = validate_creds_existence($conn , $email , $phone_number);
if(!$creds_existence_validation['success'])
{
    $_SESSION['redirect-message'] = $creds_existence_validation['message'];
    $_SESSION['redirect-message-type'] = 'error';
    header("Location: /ecommerce/frontend/storefront/pages/my-account.php");
    exit;
}

// Hash password
$hashedPassword = password_hash($password , PASSWORD_DEFAULT);

// Generate Customer Code
$customer_code = generate_customer_code();

$params = [$customer_code , $username , $email , $phone_number , $hashedPassword];
$types = "sssss";

// Add user
$query = "
INSERT INTO users (customer_code , name ,  email , phone_number , password)
VALUES (? , ? , ? , ? , ?);
";

$stmt = $conn->prepare($query);
$stmt->bind_param($types , ...$params);
$message = [];

if($stmt->execute())
{
    // $_SESSION['user_id'] = $conn->insert_id;
    // $_SESSION['user_email'] = $email;
    // $_SESSION['user_name'] = $username;

    $stmt->close();
    $conn->close();

    $_SESSION['redirect-message'] = 'Account created successfully';
    header("Location: /ecommerce/frontend/storefront/pages/my-account.php");
    exit;

}
else
{
    $stmt->close();
    $conn->close();

    $_SESSION['redirect-message'] = 'Problem in registering...';
    $_SESSION['redirect-message-type'] = 'error';
    header("Location: /ecommerce/frontend/storefront/pages/my-account.php");
    exit;
}

?>