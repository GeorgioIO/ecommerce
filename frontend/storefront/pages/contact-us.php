<?php

require_once __DIR__ . '../../../../configuration/session.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - BookNest</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/layout.css">
    <link rel="stylesheet" href="../css/component.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <script defer type="module" src="../js/main.js"></script>

</head>
<body>  
    <div class="message-box-container">

    </div>
    <?php include __DIR__ . '/../includes/header.php' ?>
    
    <?php include __DIR__ . '../../includes/sidebar.php'?>

    <main>
        <section id="contact-us">
            <h3 class="section-title">We would love to hear from you.</h3>
            <p style="text-align: center;">Any details you enter here will not be published</p>
            <div class="contact-us-inner-section">
                <figure>
                    <img src="../../../assets/images/contact-us-image.png" alt="Contact us">
                </figure>
                <form action="../../../backend/contact/send-contact.php" method="POST" id="contact-us-form">
                    <div class="form-row">
                        <label for="username">Name <span class="required-asteriks">*</span></label>
                        <input type="text" name="username" id="username" required>
                    </div>
                    <div class="form-row">
                        <label for="useremail">Email <span class="required-asteriks">*</span></label>
                        <input type="email" name="useremail" id="useremail" required autocomplete="off">
                    </div>
                    <div class="form-row">
                        <label for="usermessage">Message <span class="required-asteriks">*</span></label>
                        <textarea name="usermessage" id="usermessage" required></textarea>
                    </div>
                    <button id="submit-contact-message-button">Submit message</button>
                </form>
            </div>
        </section>
    </main>
    <?php include __DIR__ . '/../includes/footer.php' ?>        
</body>
</html>