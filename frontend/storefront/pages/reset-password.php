<?php

require_once __DIR__ . '../../../../configuration/session.php';
require_once __DIR__ . '../../../../configuration/database.php';
require_once __DIR__ . '../../../../backend/auth/auth_customer.php';


// Get token
$token = $_GET['token'] ?? '';

if(!$token)
{
    die("Invalid token");
}

// hash it
$hashedToken = hash('sha256' , $token);

// Verify that its not expired
$query = "SELECT user_id FROM password_resets WHERE token_hash = ? AND expires_at > NOW()";

$stmt = $conn->prepare($query);
$stmt->bind_param("s" , $hashedToken);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0)
{
    die("This reset link is invalid or expired");
}

// if its not get user id
$user = $result->fetch_assoc();
$userId = $user['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - BookNest</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/layout.css">
    <link rel="stylesheet" href="../css/component.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <script defer type="module" src="../js/main.js"></script>
    <script defer type="module" src="../js/components/resetPasswordForm.js"></script>
</head>
<body>
        <div class="message-box-container">

    </div>
    <?php include __DIR__ . '../../includes/header.php'?>

    <?php include __DIR__ . '../../includes/sidebar.php'?>
    
    <main>
        <section id="reset-password">
            <h3 class="section-title">Reset your password</h3>
            <?php 
            
            if($_SERVER['REQUEST_METHOD'] === "GET")
            {
                include __DIR__ . '../../includes/reset-password-form.php';
            }
            else if($_SERVER['REQUEST_METHOD'] === "POST")
            {
                $new_password = $_POST['new-password'];
                $confirm_password = $_POST['confirm-password'];

                if($new_password !== $confirm_password)
                {
                    die("Password do not match");
                }

                $newHashedPassword = password_hash($new_password , PASSWORD_DEFAULT);

                $update_query = "UPDATE users SET password = ? WHERE id = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param("si" , $newHashedPassword , $userId);

                if($update_stmt->execute())
                {
                    $delete_query = "DELETE FROM password_resets WHERE user_id = ?";
                    $delete_stmt = $conn->prepare($query);
                    $delete_stmt->bind_param("i" , $userId);
                    $delete_stmt->execute();

                    include __DIR__ . '../../includes/successfull-password-reset.php';
                }

            }
            
            ?>
        </section>
    </main>

    <?php include __DIR__ . '../../includes/footer.php'?>

</body>
</html>