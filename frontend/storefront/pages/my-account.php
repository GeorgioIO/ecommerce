<?php

require_once __DIR__ . '../../../../configuration/session.php';

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
    <script defer type="module" src="../js/pages/registration.js"></script>
</head>
<body>
    <?php  require __DIR__ . '/../includes/header.php' ?>

    <?php require __DIR__ . '/../includes/sidebar.php' ?>
    <?php
    
        if(!isset($_SESSION['user_id']))
        {
            // User not logged in
            require __DIR__ . '/../includes/account-guest.php';
        }
        else
        {
            // User logged in
            require __DIR__ . '/../includes/account-dashboard.php';
        }
    
    ?>

    <?php require __DIR__ . '/../includes/footer.php' ?>


</body>
</html>