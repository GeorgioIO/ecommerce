<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard Login</title>
    <link rel="stylesheet" href="../admin/css/admin_login_styles.css" />
    <script defer type="module" src="../admin/js/login/loginUI.js"></script>
</head>
<body>
    <?php
    
    require_once __DIR__ . '/../../config/session.php';

    if(isset($_SESSION['admin_id']))
    {
        header('Location : /ecommerce/frontend/admin/admin_dashboard.php');
        exit;
    }
    
    ?>

    <form class="admin-login-form">
        <h1>Admin Dashboard Login</h1>
        
        <div class="input-container">
            <label for="email">Email :</label>
            <input type="email" id="email" name="admin-email" placeholder="Your email" autocomplete="off">
        </div>
        <div class="input-container">
            <label for="password">Password :</label>
            <input type="password" id="password" name="admin-password" placeholder="Password">
        </div>
        <div class="buttons-container">
            <button type="submit" id="submit-admin-login-button">LOGIN</button>
            <button type="reset">RESET</button>
        </div>
    </form>
    <span class="login-message-log"></span>
</body>
</html>