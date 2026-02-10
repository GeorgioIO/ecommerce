<?php

require_once __DIR__ . '/../../configuration/session.php';
require_once __DIR__ . '/../../configuration/database.php';
require_once __DIR__ . '/../customers/helpers/customers_db_helpers.php';
require_once __DIR__ . '/../customers/helpers/customers_helpers.php';
require_once __DIR__ . '/../customers/validators/customer_validators.php';

if (
    empty($_POST['useremail'])  ||
    empty($_POST['userpassword'])
) {
    exit;
}

// Collect data
$email = $_POST['useremail'];
$password = $_POST['userpassword'];

// Validate data
$email = strtolower($email);
$email_validation = validate_customer_email($email);
if(!$email_validation['success'])
{    
    $conn->close();
    $_SESSION['redirect-message'] = $email_validation['message'];
    $_SESSION['redirect-message-type'] = 'error';
    header("Location: /ecommerce/frontend/storefront/pages/my-account.php");
    exit;
}


// Check user exist
$query = "
    SELECT
        id,
        name,
        email,
        phone_number,
        password
    FROM 
        users
    WHERE 
        email = ?
    LIMIT 1;
";

$stmt = $conn->prepare($query);
$stmt->bind_param("s"  , $email);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0)
{    
    $stmt->close();
    $conn->close();
    $_SESSION['redirect-message'] = "Invalid username or password $password";
    $_SESSION['redirect-message-type'] = 'error';
    header("Location: /ecommerce/frontend/storefront/pages/my-account.php");
    exit;
}

$user_data = $result->fetch_assoc();

if(!password_verify($password , $user_data['password']))
{    
    $stmt->close();
    $conn->close();
    $_SESSION['redirect-message'] = " 1 Invalid username or password $password";
    $_SESSION['redirect-message-type'] = 'error';
    header("Location: /ecommerce/frontend/storefront/pages/my-account.php");
    exit;
}

$_SESSION['user_id'] = $user_data['id'];
$_SESSION['user_email'] = $user_data['email'];
$_SESSION['user_name'] = $user_data['name'];

if(!empty($_POST['remember-me']))
{
    $raw_token = bin2hex(random_bytes(32));
    $token_hash = password_hash($raw_token , PASSWORD_DEFAULT);

    $expires = date("Y-m-d H:i:s" , time() + (86400 * 30));

    $query = "INSERT INTO remember_tokens(user_id , token_hash , expires_date) VALUES (? , ? , ?)";
    $token_stmt = $conn->prepare($query);
    $token_stmt->bind_param("iss" , $user_data['id'] , $token_hash , $expires);
    $token_stmt->execute();
    $token_stmt->close();

    setcookie("remember_me" , $raw_token , time() + (86400 * 30) , "/" , "" , true , true);
}


$stmt->close();
$conn->close();

$_SESSION['redirect-message'] = 'Logged in successfully';
$_SESSION['redirect-message-type'] = 'success';
header("Location: /ecommerce/frontend/storefront/pages/my-account.php");
exit;

?>