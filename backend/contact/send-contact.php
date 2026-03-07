<?php

if($_SERVER['REQUEST_METHOD'] === "POST")
{
    require_once __DIR__ . '../../customers/validators/customer_validators.php';
    require_once __DIR__ . '../../email/email_config.php';
    require_once __DIR__ . '../../../configuration/session.php';
    $config = require __DIR__ . '../../../configuration/env.php';

    // Collect data 
    $name = trim($_POST['username']);
    $email = trim($_POST['useremail']);
    $message = trim($_POST['usermessage']);
 
    // Validate data
    $name_validation = validate_customer_name($name);
    if(!$name_validation['success'])
    {
        $_SESSION['redirect_message'] = $name_validation['message'];
        $_SESSION['redirect_message_type'] = 'error';
    }

    $email_validation = validate_customer_email($email);
    if(!$email_validation['success'])
    {
        $_SESSION['redirect_message'] = $email_validation['message'];
        $_SESSION['redirect_message_type'] = 'error';
    }

    if(empty($message))
    {
        $_SESSION['redirect_message'] = 'Message cannot be empty';
        $_SESSION['redirect_message_type'] = 'error';
    }
    
    $data = [
        'name' => $name,
        'email' => $email,
        'message' => $message
    ];
    
    // All data is correct
    try
    {
        sendEmail("contact_us" , "Inquiry coming from $name" , $data , $config['TEAM_EMAIL']);
        $_SESSION['redirect_message'] = "Thanks for contacting us! We'll get back to you as soon as possible.";
        $_SESSION['redirect_message_type'] = 'success';
    }
    catch (Exception $e)
    {
        $_SESSION['redirect_message'] = 'Sending email failed , please try again later';
        $_SESSION['redirect_message_type'] = 'error';
    }



    $conn->close();
    header("Location: /ecommerce/frontend/storefront/pages/contact-us.php");
    exit;
}

?>