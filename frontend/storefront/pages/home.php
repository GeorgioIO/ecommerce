<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookNest</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script defer type="module" src="../js/main.js"></script>
</head>
<body>
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
                
                $best_sellers = get_best_seller_books($conn);
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

                            $title = htmlspecialchars($best_seller['title'] , ENT_QUOTES , 'UTF-8');
                            $total_price = htmlspecialchars($best_seller['price'] , ENT_QUOTES , 'UTF-8');
                            $image = htmlspecialchars($best_seller['cover_image'] , ENT_QUOTES , 'UTF-8');
                            $author_name = htmlspecialchars($best_seller['author_name'] , ENT_QUOTES , 'UTF-8');
                            $format_name = htmlspecialchars($best_seller['format_name'] , ENT_QUOTES , 'UTF-8');
                            $url = "../../../assets/images/$image";
                        ?> 
                            <div class="product-card">
                                <figure>
                                    <a href="">
                                        <img src="<?= $url ?>" alt="<?= $title ?> cover image">
                                    </a>
                                    <div class="product-card-actions">
                                        <button class="product-card-add-wishlist-button">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="none" viewBox="0 0 24 24">
                                                <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7.694C10 3 3 3.5 3 9.5s9 11 9 11 9-5 9-11-7-6.5-9-1.806Z"/>
                                            </svg>
                                        </button>
                                        <button class="product-card-add-cart-button">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="none" viewBox="0 0 24 24">
                                                <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.3 5H21l-2 7H7.377M20 16H8L6 3H3m6 17a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm11 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </figure>
                                <div class="product-card-text">
                                    <p class="product-card-price">$<?= $total_price ?> USD</p>
                                    <a class="product-card-title"><?= $title ?></a>
                                    <p class="product-card-author-format"> 
                                        <a href="" class="product-card-author">By <span class="product-card-author-name"> <?= $author_name ?></span></a>     
                                        <span class="separator">,</span>
                                        <span class="product-card-format"><?= $format_name ?></span>
                                    </p>
                                </div>
                                <a href="" class="product-card-redirection-button">View Product</a>             
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
        <div class="moving-advertisement">
            <div class="ad-track">
                <span>🆘 All Revenue this month for @CharityForHope</span>
                <span>30% OFF on Game of Thrones Books</span>
                <span>🆘 All Revenue this month for @CharityForHope</span>
                <span>30% OFF on Game of Thrones Books</span>
                <span>🆘 All Revenue this month for @CharityForHope</span>
                <span>30% OFF on Game of Thrones Books</span>
            </div>
        </div>
        <section id="new-arrivals">
            <?php 
                $new_arrivals = get_new_arrivals_books($conn);
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

                            $title = htmlspecialchars($new_arrival['title'] , ENT_QUOTES , 'UTF-8');
                            $total_price = htmlspecialchars($new_arrival['price'] , ENT_QUOTES , 'UTF-8');
                            $image = htmlspecialchars($new_arrival['cover_image'] , ENT_QUOTES , 'UTF-8');
                            $author_name = htmlspecialchars($new_arrival['author_name'] , ENT_QUOTES , 'UTF-8');
                            $format_name = htmlspecialchars($new_arrival['format_name'] , ENT_QUOTES , 'UTF-8');
                            $url = "../../../assets/images/$image";
                            
                        ?>
                        <div class="product-card">
                            <figure>
                                <a href="">
                                    <img src="<?= $url ?>" alt="<?= $title ?> cover image">
                                </a>
                                <div class="product-card-actions">
                                    <button class="product-card-add-wishlist-button">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="none" viewBox="0 0 24 24">
                                            <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7.694C10 3 3 3.5 3 9.5s9 11 9 11 9-5 9-11-7-6.5-9-1.806Z"/>
                                        </svg>
                                    </button>
                                    <button class="product-card-add-cart-button">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="none" viewBox="0 0 24 24">
                                            <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.3 5H21l-2 7H7.377M20 16H8L6 3H3m6 17a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm11 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>
                                        </svg>
                                    </button>
                                </div>
                            </figure>
                            <div class="product-card-text">
                                <p class="product-card-price">$<?= $total_price ?> USD</p>
                                <a class="product-card-title"><?= $title ?></a>
                                <p class="product-card-author-format"> 
                                    <a href="" class="product-card-author">By <span class="product-card-author-name"> <?= $author_name ?> </span></a>     
                                    <span class="separator">,</span>
                                    <span class="product-card-format"><?= $format_name ?></span>
                                </p>
                            </div>
                            <a href="" class="product-card-redirection-button">View Product</a>             
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
    </main>

    <?php require __DIR__ . '/../includes/footer.php' ?>
</body>
</html>