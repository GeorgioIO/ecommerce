<?php

require_once __DIR__ . '../../../../configuration/session.php';
require_once __DIR__ . '/../../../backend/auth/auth_customer.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - BookNest</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/layout.css">
    <link rel="stylesheet" href="../css/component.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <script defer type="module" src="../js/main.js"></script>
    <script defer type="module" src="../js/pages/checkoutPage.js"></script>
</head>
<body>
    <div class="message-box-container">

    </div>
    <?php  require __DIR__ . '/../includes/header.php' ?>

    <?php require __DIR__ . '/../includes/sidebar.php' ?>

    <main>
        <section id="checkout">

        </section>
    </main>
    <?php require __DIR__ . '/../includes/footer.php' ?>

</body>
</html>