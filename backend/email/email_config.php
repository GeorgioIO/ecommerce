<?php 

header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP; 


require_once __DIR__ . '/../../PHPMailer/PHPMailer-master/src/Exception.php'; 
require_once __DIR__ . '/../../PHPMailer/PHPMailer-master/src/PHPMailer.php'; 
require_once __DIR__ . '/../../PHPMailer/PHPMailer-master/src/SMTP.php'; 
require_once __DIR__ . '/email_bodies.php';



function sendEmail($type , $subject , $data)
{
    $config = require_once __DIR__ . '/../../configuration/env.php'; 
    $mail = new PHPMailer(true); 

    try { 
        $mail->isSMTP(); 
        $mail->isHTML();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true; 
        $mail->Username = 'georgiojabbour.g.gj@gmail.com'; 
        $mail->Password = "{$config['EMAIL_PASSWORD']}"; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port = 587; 
        $mail->setFrom('georgiojabbour.g.gj@gmail.com', "Sender");

        // sender's email and name 
        $mail->addAddress('georgiojabbour.g.gj@gmail.com', "Receiver"); 
        
        // receiver's email and name 
        $mail->Subject = $subject;
        
        if($type === "new_order")
        {
            $body = get_new_order_email_bd($data);
        }

        $mail->Body = $body; 
        $mail->send(); 
    } 
    catch (Exception $e) 
    { 
        // handle error. 
       throw new Exception("Error sending email"); 
    } 
}

?>