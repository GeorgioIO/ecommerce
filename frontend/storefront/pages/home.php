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
            <a href="" id="open-genres-page-button">
                View all genres
            </a>
        </section>
        <section id="best-sellers">
            <h3 class="section-title">What people buy the most</h3>
            <div class="best-sellers-container">
                <div class="product-card">
                    <figure>
                        <a href="">
                            <img src="../../../assets/images/cover_695247077f7621.95155152.png" alt="">
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
                        <p class="product-card-price">$25.50 USD</p>
                        <a class="product-card-title">Sun tzu art of war</a>
                        <p class="product-card-author-format"> 
                            <a href="" class="product-card-author">By Richard Hawkins</a>     
                            <span class="separator">,</span>
                            <span class="product-card-format">Paperback</span>
                        </p>
                    </div>
                    <a href="" class="product-card-show-button">View Product</a>             
                </div>
            </div>
            <div class="best-sellers-navigation-container">
                <button id="previous-bestseller-card-button" class="disabled">
                    <svg class="left-caret" xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" fill="none" viewBox="0 0 24 24">
                        <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m7 10 5 5 5-5"/>
                    </svg>
                </button>
                <div class="inner-navigation-container">
                    <div class="line-bar active"></div>
                    <div class="line-bar"></div>
                    <div class="line-bar"></div>
                    <div class="line-bar"></div>
                    <div class="line-bar"></div>
                    <div class="line-bar"></div>
                </div>
                <button id="next-seller-card-button" class="enabled">
                    <svg class="right-caret" xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" fill="none" viewBox="0 0 24 24">
                        <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m7 10 5 5 5-5"/> 
                    </svg>
                </button>
            </div>
        </section>
    </main>
</body>
</html>