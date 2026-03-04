<?php

require_once __DIR__ . '../../../../configuration/session.php';
require_once __DIR__ . '../../../../backend/auth/auth_customer.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collections - BookNest</title>
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
        <section id="collections">
            <?php
            
            $genres = get_all_genres($conn)
            
            ?>
            <h3 class="section-title">All our collections</h3>
            <div class="collections-grid">
                <?php foreach ($genres as $genre) : 
                
                $id = htmlspecialchars($genre['id'] , ENT_QUOTES , 'UTF-8');
                $name = htmlspecialchars($genre['name'] , ENT_QUOTES , 'UTF-8');
                $image = htmlspecialchars($genre['image'] , ENT_QUOTES , 'UTF-8');
                $url = empty($image) ?  "../../../assets/images/no-image-photo.png" : "../../../assets/images/$image" ;
                ?>

                <div class="collection-card">
                    <figure>
                        <img src="<?= $url ?>" alt="<?= $name ?>">
                    </figure>
                    <a href="../pages/products.php?genre=<?= $id ?>" class="view-collection-button">Shop now</a>
                </div>


                <?php endforeach; ?>
            </div>
        </section>
    </main>
    

    <?php include __DIR__ . '../../includes/footer.php'?>

</body>
</html>