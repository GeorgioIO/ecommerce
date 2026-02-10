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
    <script defer type="module" src="../js/components/productCard.js"></script>
</head>
<body>
    <div class="message-box-container">

    </div>
    <?php  require __DIR__ . '/../includes/header.php' ?>

    <?php require __DIR__ . '/../includes/sidebar.php' ?>

    <main>

        <section id="hero">
            <div class="hero-sliders">
                <div class="hero-card active" id="hero-card-one">
                    <div class="hero-text-container">
                        <h3>Discover a huge variety of genres</h3>
                    </div>
                    <a href="" class="call-to-action-button">
                        Explore Our Collections
                    </a>
                </div>
                <div class="hero-card" id="hero-card-two">
                    <div class="hero-text-container">
                        <h3>Our book of the month</h3>
                    </div>
                    <a href="" class="call-to-action-button">
                        Shop Now
                    </a>
                </div>
                <div class="hero-card" id="hero-card-three">
                    <div class="hero-text-container">
                        <h3>For each three books get 30% OFF</h3>
                    </div>
                    <a href="" class="call-to-action-button">
                        Buy Now
                    </a>
                </div>
            </div>
            <div class="hero-navigation-container">
                <button id="previous-hero-card-button" class="disabled">
                    <svg class="left-caret" xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" fill="none" viewBox="0 0 24 24">
                        <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m7 10 5 5 5-5"/>
                    </svg>
                </button>
                <div class="navigation-bars-container">
                    <div class="circle-one active" data-number="one"></div>
                    <div class="circle-two" data-number="two"></div>
                    <div class="circle-three" data-number="three"></div>
                </div>
                <button id="next-hero-card-button" class="enabled">
                    <svg class="right-caret" xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" fill="none" viewBox="0 0 24 24">
                        <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m7 10 5 5 5-5"/> 
                    </svg>
                </button>
            </div>
        </section>
        <section id="genres">
            <h3 class="section-title">Discover our genres</h3>
            <?php
            
            $genres = get_genres_by_order_counts($conn);
            
            ?>
            <div class="genres-grid">
                <?php foreach($genres as $genre):
                    $image = htmlspecialchars($genre['image'] , ENT_QUOTES , 'UTF-8');
                    $name = htmlspecialchars($genre['name'] , ENT_QUOTES , 'UTF-8');
                    $url = "../../../assets/images/$image";
                ?> 
                    <a href="">
                        <figure class="genre-grid-card">
                            <img src="<?=  $url ?>" alt='<?= $name ?> books genre'>
                            <figcaption><?= $name ?></figcaption>
                        </figure>
                    </a>
                <?php endforeach; ?>
            </div>
            <a href="" class="section-redirection-button">
                View all genres
            </a>
        </section>
        <section id="best-sellers">
            <h3 class="section-title">Best Sellers</h3>
            <?php 
                
                if(isset($_SESSION['user_id']))
                {
                    $best_sellers = get_best_seller_books($conn , $_SESSION['user_id']);
                }
                else
                {
                    $best_sellers = get_best_seller_books($conn);
                }
            ?>
            <div class="carousel">
                <button class="carousel-button prev">
                    <svg class="left-caret" xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" fill="none" viewBox="0 0 24 24">
                        <path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m7 10 5 5 5-5"/>
                    </svg>
                </button>
                <div class="carousel-viewport">
                    <div class="carousel-track">
                        <?php foreach($best_sellers as $best_seller):

                            $id = $best_seller['id'];
                            $title = htmlspecialchars($best_seller['title'] , ENT_QUOTES , 'UTF-8');
                            $total_price = htmlspecialchars($best_seller['price'] , ENT_QUOTES , 'UTF-8');
                            $image = htmlspecialchars($best_seller['cover_image'] , ENT_QUOTES , 'UTF-8');
                            $author_name = htmlspecialchars($best_seller['author_name'] , ENT_QUOTES , 'UTF-8');
                            $format_name = htmlspecialchars($best_seller['format_name'] , ENT_QUOTES , 'UTF-8');
                            $in_wishlist = htmlspecialchars($best_seller['is_inWishlist'] , ENT_QUOTES , 'UTF-8');
                            $url = "../../../assets/images/$image";
                        ?> 

                            <div class="product-card" data-productid="<?= $id ?>">
                                <figure>
                                    <a href="">
                                        <img src="<?= $url ?>" alt="<?= $title ?> cover image">
                                    </a>
                                    <div class="product-card-actions">
                                        <button class="product-card-add-wishlist-button" data-state="<?= $in_wishlist === '1' ? 'active' : 'inactive' ?>" data-enabled="<?= isset($_SESSION['user_id']) ? "true" : "false" ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="<?= $in_wishlist === '1' ? 'black' : 'none' ?>" viewBox="0 0 24 24">
                                                <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7.694C10 3 3 3.5 3 9.5s9 11 9 11 9-5 9-11-7-6.5-9-1.806Z"/>
                                            </svg>
                                        </button>
                                        <button class="product-card-add-cart-button <?= $best_seller['is_inStock'] === "0" ? "unclickable" : "" ?>" <?= $best_seller['is_inStock'] === "0" ? "disabled" : "" ?>>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="none" viewBox="0 0 24 24">
                                                <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.3 5H21l-2 7H7.377M20 16H8L6 3H3m6 17a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm11 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <?php 
                                        echo $best_seller['is_onSale'] === 1 ? 
                                        "<div class='product-card-sale-badge'>- %{$best_seller['discount_percentage']}</div>" : 
                                        "" ; 
                                    ?>
                                </figure>
                                <div class="product-card-text">
                                    <p class="product-card-price">
                                        <?php
                                        
                                        echo $best_seller['is_onSale'] === 1 ?
                                        "<span class='pre-sale-price'> \${$best_seller['price']} </span> <span class='post-sale-price'> \${$best_seller['final_price']} </span>":
                                        "<span class='base-price'> \${$best_seller['final_price']} </span>";
                                        
                                        ?>
                                    </p>    
                                    <a class="product-card-title"><?= $title ?></a>
                                    <p class="product-card-author-format"> 
                                        <a href="" class="product-card-author">By <span class="product-card-author-name"> <?= $author_name ?></span></a>     
                                        <span class="separator">,</span>
                                        <span class="product-card-format"><?= $format_name ?></span>
                                    </p>
                                </div>
                                <?php
                                    if($best_seller['is_inStock'] === 0)
                                    {
                                     echo "<a class='sold-out-button' disable>Sold Out</a>";
                                    }
                                    else
                                    {
                                        echo "<a href='' class='product-card-redirection-button'>View Product</a>";
                                    }
                                ?>             
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button class="carousel-button next">
                    <svg class="right-caret" xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" fill="none" viewBox="0 0 24 24">
                        <path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m7 10 5 5 5-5"/>
                    </svg>
                </button>
            </div>
        </section>
        <?php require __DIR__ . '/../includes/advertisement.php' ?>
        <section id="new-arrivals">
            <?php 
                if(isset($_SESSION['user_id']))
                {
                    $new_arrivals = get_new_arrivals_books($conn , $_SESSION['user_id']);
                }
                else
                {
                    $new_arrivals = get_new_arrivals_books($conn);
                }
            ?>
            <h3 class="section-title">New Arrivals</h3>
            <div class="carousel">
                <button class="carousel-button prev">
                    <svg class="left-caret" xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" fill="none" viewBox="0 0 24 24">
                        <path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m7 10 5 5 5-5"/>
                    </svg>
                </button>
                <div class="carousel-viewport">
                    <div class="carousel-track">
                        <?php foreach($new_arrivals as $new_arrival):

                            $id = $new_arrival['id'];
                            $title = htmlspecialchars($new_arrival['title'] , ENT_QUOTES , 'UTF-8');
                            $total_price = htmlspecialchars($new_arrival['price'] , ENT_QUOTES , 'UTF-8');
                            $image = htmlspecialchars($new_arrival['cover_image'] , ENT_QUOTES , 'UTF-8');
                            $in_wishlist = htmlspecialchars($new_arrival['is_inWishlist'] , ENT_QUOTES , 'UTF-8');
                            $author_name = htmlspecialchars($new_arrival['author_name'] , ENT_QUOTES , 'UTF-8');
                            $format_name = htmlspecialchars($new_arrival['format_name'] , ENT_QUOTES , 'UTF-8');
                            $url = "../../../assets/images/$image";
                            
                        ?>
                        <div class="product-card" data-productid="<?= $id ?>">
                            <figure>
                                <a href="">
                                    <img src="<?= $url ?>" alt="<?= $title ?> cover image">
                                </a>
                                <div class="product-card-actions">
                                    <button class="product-card-add-wishlist-button" data-state="<?= $in_wishlist === '1' ? 'active' : 'inactive' ?>" data-enabled="<?= isset($_SESSION['user_id']) ? "true" : "false" ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="<?= $in_wishlist === '1' ? 'black' : 'none' ?>" viewBox="0 0 24 24">
                                            <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7.694C10 3 3 3.5 3 9.5s9 11 9 11 9-5 9-11-7-6.5-9-1.806Z"/>
                                        </svg>
                                    </button>
                                    <button class="product-card-add-cart-button <?= $best_seller['is_inStock'] === "0" ? "unclickable" : "" ?> " <?= $best_seller['is_inStock'] === "0" ? "disabled" : "" ?>>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="none" viewBox="0 0 24 24">
                                            <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.3 5H21l-2 7H7.377M20 16H8L6 3H3m6 17a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm11 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>
                                        </svg>
                                    </button>
                                </div>
                                <?php 
                                
                                echo $new_arrival['is_onSale'] === 1 ? 
                                 "<div class='product-card-sale-badge'>- %{$new_arrival['discount_percentage']}</div>" : 
                                 "" ; 
                                ?>
                            </figure>
                            <div class="product-card-text">
                                <p class="product-card-price">
                                    <?php
                                        echo $new_arrival['is_onSale'] === 1 ?
                                        "<span class='pre-sale-price'> \${$new_arrival['price']} </span> <span class='post-sale-price'> \${$new_arrival['final_price']} </span>":
                                        "<span class='base-price'> \${$new_arrival['final_price']} </span>";  
                                    ?>
                                </p>
                                <a class="product-card-title"><?= $title ?></a>
                                <p class="product-card-author-format"> 
                                    <a href="" class="product-card-author">By <span class="product-card-author-name"> <?= $author_name ?> </span></a>     
                                    <span class="separator">,</span>
                                    <span class="product-card-format"><?= $format_name ?></span>
                                </p>
                            </div>
                            <?php
                            if($new_arrival['is_inStock'] === 0)
                            {
                            echo "<a class='sold-out-button' disable>Sold Out</a>";
                            }
                            else
                            {
                                echo "<a href='' class='product-card-redirection-button'>View Product</a>";
                            }
                            ?>            
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>
                <button class="carousel-button next">
                    <svg class="right-caret" xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" fill="none" viewBox="0 0 24 24">
                        <path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m7 10 5 5 5-5"/>
                    </svg>
                </button>
            </div>
        </section>
        <section id="books-under-price">
            <?php
            $price = 10;
            
            if(isset($_SESSION['user_id']))
            {
                $books_under = get_books_under_price($conn , 10 , $_SESSION['user_id']);
            }
            else
            {
                $books_under = get_books_under_price($conn , 10);
            }
            
            ?>
            <h3 class="section-title">Books under $<?= $price ?></h3>
            <div class="carousel">
                <button class="carousel-button prev">
                    <svg class="left-caret" xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" fill="none" viewBox="0 0 24 24">
                        <path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m7 10 5 5 5-5"/>
                    </svg>
                </button>
                <div class="carousel-viewport">
                    <div class="carousel-track">
                        <?php foreach($books_under as $book):

                            $id = $book['id'];
                            $title = htmlspecialchars($book['title'] , ENT_QUOTES , 'UTF-8');
                            $total_price = htmlspecialchars($book['price'] , ENT_QUOTES , 'UTF-8');
                            $image = htmlspecialchars($book['cover_image'] , ENT_QUOTES , 'UTF-8');
                            $author_name = htmlspecialchars($book['author_name'] , ENT_QUOTES , 'UTF-8');
                            $format_name = htmlspecialchars($book['format_name'] , ENT_QUOTES , 'UTF-8');
                            $url = "../../../assets/images/$image";
                            
                        ?>
                        <div class="product-card" data-productid="<?= $id ?>">
                            <figure>
                                <a href="">
                                    <img src="<?= $url ?>" alt="<?= $title ?> cover image">
                                </a>
                                <div class="product-card-actions">
                                    <button class="product-card-add-wishlist-button" data-state="<?= $in_wishlist === '1' ? 'active' : 'inactive' ?>" data-enabled="<?= isset($_SESSION['user_id']) ? "true" : "false" ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="<?= $in_wishlist === '1' ? 'black' : 'none' ?>" viewBox="0 0 24 24">
                                            <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7.694C10 3 3 3.5 3 9.5s9 11 9 11 9-5 9-11-7-6.5-9-1.806Z"/>
                                        </svg>
                                    </button>
                                    <button class="product-card-add-cart-button <?= $best_seller['is_inStock'] === "0" ? "unclickable" : "" ?> " <?= $best_seller['is_inStock'] === "0" ? "disabled" : "" ?>>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="none" viewBox="0 0 24 24">
                                            <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.3 5H21l-2 7H7.377M20 16H8L6 3H3m6 17a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm11 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>
                                        </svg>
                                    </button>
                                </div>
                                <?php 
                                
                                echo $book['is_onSale'] === 1 ? 
                                 "<div class='product-card-sale-badge'>- %{$book['discount_percentage']}</div>" : 
                                 "" ; 
                                ?>
                            </figure>
                            <div class="product-card-text">
                                <p class="product-card-price">
                                    <?php
                                        echo $book['is_onSale'] === 1 ?
                                        "<span class='pre-sale-price'> \${$book['price']} </span> <span class='post-sale-price'> \${$book['final_price']} </span>":
                                        "<span class='base-price'> \${$book['final_price']} </span>";  
                                    ?>
                                </p>
                                <a class="product-card-title"><?= $title ?></a>
                                <p class="product-card-author-format"> 
                                    <a href="" class="product-card-author">By <span class="product-card-author-name"> <?= $author_name ?> </span></a>     
                                    <span class="separator">,</span>
                                    <span class="product-card-format"><?= $format_name ?></span>
                                </p>
                            </div>
                            <?php
                            if($book['is_inStock'] === 0)
                            {
                            echo "<a class='sold-out-button' disable>Sold Out</a>";
                            }
                            else
                            {
                                echo "<a href='' class='product-card-redirection-button'>View Product</a>";
                            }
                            ?>            
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>
                <button class="carousel-button next">
                    <svg class="right-caret" xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" fill="none" viewBox="0 0 24 24">
                        <path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m7 10 5 5 5-5"/>
                    </svg>
                </button>
            </div>
        </section>
        <?php require __DIR__ . '/../includes/advertisement.php' ?>
        <section id="reviews">
            <?php
            
            $store_reviews = get_store_reviews($conn);
            
            ?>
            <h3 class="section-title">Check what our clients say about us</h3>
            <div class="review-slider-control">
                <button class="control-review-slider-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" fill="none" viewBox="0 0 16 16">
                        <path fill="#000" d="M7 1H2v14h5V1ZM14 1H9v14h5V1Z"/>
                    </svg>
                </button>
            </div>
            <div class="reviews-slider">
                <div class="review-viewport">
                    <div class="review-track">
                        <?php foreach($store_reviews as $store_review) :
                            
                            $name = htmlspecialchars($store_review['customer_name'] , ENT_QUOTES , 'UTF-8');
                            $text = htmlspecialchars($store_review['text'] , ENT_QUOTES , 'UTF-8');
                            $rating = htmlspecialchars($store_review['rating'] , ENT_QUOTES , 'UTF-8');
                        ?>
                            <div class="store-review-card">
                                <div class="card-header">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="35px" height="35px" fill="none" viewBox="0 0 16 16">
                                        <path fill="#00cdbc" d="m5.293 1.293 1.414 1.414L3 6.414V7h4v7H1V5.586l4.293-4.293ZM15 7h-4v-.586l3.707-3.707-1.414-1.414L9 5.586V14h6V7Z"/>
                                    </svg>
                                </div>
                                <div class="card-body">
                                    <p class="review-text"><?= $text ?></p>
                                    <p class="review-owner">
                                        <strong><?= $name ?></strong>
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="35px" height="35px" fill="none" viewBox="0 0 16 16">
                                        <path fill="#00cdbc" d="m7 10.414-4.293 4.293-1.414-1.414L5 9.586V9H1V2h6v8.414ZM9 9h4v.586l-3.707 3.707 1.414 1.414L15 10.414V2H9v7Z"/>
                                    </svg>
                                </div>
                            </div>
                        <?php endforeach; ?>    
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php require __DIR__ . '/../includes/services.php' ?>
        <section id="community">
            <div class="community-container">
                <div class="community-info">
                    <div class="community-text">
                        <h4 class="community-title">Join The Community</h4>
                        <p>Enter your email address to receive regular updates, as well as news on upcoming events and specific offers.</p>
                    </div>
                    <div class="community-action">
                        <input type="email" name="user-email-newsletter" id="newsletter-community-email" autocomplete="off">
                        <button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" fill="none" viewBox="0 0 24 24">
                                <path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12 4 4l2 8m14 0L4 20l2-8m14 0H6"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="community-image">
                    <img src="../../../assets/images/community-image.png" alt="Booknest community image">
                </div>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/../includes/footer.php' ?>
</body>
</html>