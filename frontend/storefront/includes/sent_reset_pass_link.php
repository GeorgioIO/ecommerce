<?php

require_once __DIR__ . '../../../../configuration/database.php';
require_once __DIR__ . '/../../../backend/helpers.php';
require_once __DIR__ . '../../../../backend/email/email_config.php';

// Get email
$email = trim($_POST['useremail']);

// Validate email
// $email_validation = validate_email($email);
// if(!$email_validation['valid'])
// {
//     echo json_encode([
//         'success' => false,
//         'status' => 400,
//         'message' => $email_validation['message']
//     ]);
//     exit;
// }

// Check email exist in database
$query = "SELECT id , name FROM users WHERE email = ?";
$email_stmt = $conn->prepare($query);
$email_stmt->bind_param("s" , $email);
$email_stmt->execute();
$result = $email_stmt->get_result();

$user = $result->fetch_assoc();

$query = "DELETE FROM password_resets WHERE user_id = ?";
$delete_stmt = $conn->prepare($query);
$delete_stmt->bind_param("i" , $user['id']);
$delete_stmt->execute();

// Generate token
$token = bin2hex(random_bytes(32));
$hashedToken = hash('sha256' , $token);

// Save it into password resets table
if($result->num_rows === 1)
{
    $query = "
    INSERT INTO password_resets (user_id , token_hash , expires_at)
    VALUES (? , ? , DATE_ADD(NOW() , INTERVAL 30 MINUTE));
    ";
    $token_stmt = $conn->prepare($query);
    $token_stmt->bind_param("is" , $user['id'] , $hashedToken);
    $token_stmt->execute();

    // Build reset link
    $resetLink = "http://localhost/ecommerce/frontend/storefront/pages/reset-password.php?token=" . $token;

    $data = [
        'customer_name' => $user['name'],
        'reset_link' => $resetLink
    ];

    // Send email
    sendEmail("reset_password" , "Reset password request" , $data , $email);

    $token_stmt->close();
}

$conn->close();
$email_stmt->close();

?>

<p class="email-sent-message">If this email exist , you will receive a link to reset your password</p>