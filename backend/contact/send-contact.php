<?php

if($_SERVER['REQUEST_METHOD'] === "POST")
{
    require_once __DIR__ . '../../customers/validators/customer_validators.php';
    require_once __DIR__ . '../../email/email_config.php';
    require_once __DIR__ . '../../../configuration/session.php';
    $config = require __DIR__ . '/../../configuration/env.php';

    // Collect data 
    $name = trim($_POST['username']);
    $email = trim($_POST['useremail']);
    $message = trim($_POST['usermessage']);
 
    // Validate data
    $name_validation = validate_customer_name($name);
    if(!$name_validation['success'])
    {
        $conn->close();
        $_SESSION['redirect-message'] = $name_validation['message'];
        $_SESSION['redirect-message-type'] = 'error';
        header("Location: /ecommerce/frontend/storefront/pages/contact-us.php");
        exit;
    }

    $email_validation = validate_customer_email($email);
    if(!$email_validation['success'])
    {
        $conn->close();
        $_SESSION['redirect-message'] = $email_validation['message'];
        $_SESSION['redirect-message-type'] = 'error';
        header("Location: /ecommerce/frontend/storefront/pages/contact-us.php");
        exit;
    }

    if(empty($message))
    {
        $conn->close();
        $_SESSION['redirect-message'] = 'Message cannot be empty';
        $_SESSION['redirect-message-type'] = 'error';
        header("Location: /ecommerce/frontend/storefront/pages/contact-us.php");
        exit;
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

        $conn->close();
        $_SESSION['redirect-message'] = "Thanks for contacting us! We'll get back to you as soon as possible.";
        $_SESSION['redirect-message-type'] = 'success';
        header("Location: /ecommerce/frontend/storefront/pages/contact-us.php");
        exit;
    }
    catch (Exception $e)
    {
        $conn->close();
        $_SESSION['redirect-message'] = 'Sending email failed , please try again later';
        $_SESSION['redirect-message-type'] = 'error';
        header("Location: /ecommerce/frontend/storefront/pages/contact-us.php");
        exit;
    }



}

?>