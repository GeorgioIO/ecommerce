<?php

require_once __DIR__ . '../../../../configuration/session.php';
require_once __DIR__ . '/../../../backend/auth/auth_customer.php';

if(!isset($_SESSION['user_id']))
{
    // User not logged in
    header("Location: /ecommerce/frontend/storefront/pages/my-account.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - BookNest</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/layout.css">
    <link rel="stylesheet" href="../css/component.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <script defer type="module" src="../js/main.js"></script>
    <script defer type="module" src="../js/pages/cartPage.js"></script>
</head>
<body>
    <div class="message-box-container">

    </div>
    <?php  require __DIR__ . '/../includes/header.php' ?>

    <?php require __DIR__ . '/../includes/sidebar.php' ?>

    <main>
        <section id="cart">
            <h2  class="section-title">Shopping Cart</h2>
            <div class="cart-outer-container">

            </div>
        </section>
    </main>
    <?php require __DIR__ . '/../includes/footer.php' ?>

</body>
</html>