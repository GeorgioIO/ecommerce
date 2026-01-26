<?php 



use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP; 


require __DIR__ . '/../../PHPMailer/PHPMailer-master/src/Exception.php'; 
require __DIR__ . '/../../PHPMailer/PHPMailer-master/src/PHPMailer.php'; 
require __DIR__ . '/../../PHPMailer/PHPMailer-master/src/SMTP.php'; 
require __DIR__ . '/email_bodies.php';



function sendEmail($type , $subject , $data , $receiver='georgiojabbour.g.gj@gmail.com')
{
    $config = require __DIR__ . '/../../configuration/env.php'; 
    $mail = new PHPMailer(true); 

    try { 
        $mail->isSMTP(); 
        $mail->isHTML();
        $mail->CharSet  = 'UTF-8';
        $mail->Encoding = 'base64'; 
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true; 
        $mail->Username = 'georgiojabbour.g.gj@gmail.com'; 
        $mail->Password = "{$config['EMAIL_PASSWORD']}"; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port = 587; 
        $mail->setFrom('georgiojabbour.g.gj@gmail.com', "Sender");

        // sender's email and name 
        $mail->addAddress($receiver, "Receiver"); 
        
        $mail->Subject = $subject;
        
        if($type === "new_order")
        {
            $body = get_new_order_email_bd($data);
        }
        elseif ($type === "out_of_stock")
        {
            $body = get_out_of_stock_email_bd($data);
        }
        elseif ($type === "low_stock")
        {
            $body = get_low_stock_email_bd($data);
        }
        elseif ($type === "back_in_stock")
        {
            $body = get_back_in_stock_email_bd($data);
        }
        elseif($type === "order_update")
        {
            $body = get_update_order_email_bd($data);
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