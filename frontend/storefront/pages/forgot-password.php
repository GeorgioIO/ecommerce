<?php

require_once __DIR__ . '../../../../configuration/session.php';
require_once __DIR__ . '../../../../backend/auth/auth_customer.php';

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
</head>
<body>

    <?php include __DIR__ . '../../includes/header.php'?>

    <?php include __DIR__ . '../../includes/sidebar.php'?>
    
    <main>
        <section id="forgot-password">
            <h3 class="section-title">Lost your password ?</h3>
            
            <form action="" id="lost-password-form">
                <p>Please enter your email , you will receive a email to create a new password.</p>
                <div class="form-row">
                    <label for="lost-pass-email">Email <span class="required-asteriks">*</span> </label>
                    <input type="text" id="lost-pass-email" name="useremail" autocomplete="off">
                </div>
            </form>
        </section>
    </main>

    <?php include __DIR__ . '../../includes/footer.php'?>

</body>
</html>