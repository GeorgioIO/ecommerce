<?php

require_once __DIR__ . '/../../../configuration/session.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookNest</title>
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
        <?php

        if(!isset($_GET['slug']))
        {
            http_response_code(404);
            exit("Product not found");
        }

        $slug = $_GET['slug'];
        $user_id = 0;

        if(isset($_SESSION['user_id']))
        {
            $user_id = $_SESSION['user_id'];
        }

        $query = get_single_book($conn , $slug);
        $select_stmt = $conn->prepare($query);
        $select_stmt->bind_param("iis" , $user_id , $user_id , $slug);
        $select_stmt->execute();
        $result =  $select_stmt->get_result();
        $product = $result->fetch_assoc();

        if(!$product)
        {
            http_response_code(404);
            exit("Product not found");
        }
        else
        {
            include __DIR__ . '/../includes/product-detail.php';
        }

        ?>
    </main>

    <?php include __DIR__ . '/../includes/footer.php' ?>    
</body>
</html>