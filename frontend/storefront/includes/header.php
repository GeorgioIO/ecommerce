<header id="site-header">
    <a href="../pages/home.php">
        <img src="../../../assets/images/BookNest.svg" alt="booknest logo">
    </a>
    <nav class="site-header-navbar">
        <ul>
            <li>
                <a href="../pages/about-us.php">About Us</a>
            </li>
            <li>
                <a href="">Shop</a>
            </li>
            <li>
                <button id="show-genres-header-submenu-button">
                    Collections
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="none" viewBox="0 0 24 24">
                        <path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m7 10 5 5 5-5"/>
                    </svg>
                </button>
                <?php
                
                include __DIR__ . '/../../../configuration/database.php';
                include __DIR__ . '/../../../backend/genres/helpers/genre_db_helpers.php';
                include __DIR__ . '/../../../backend/books/helpers/book_db_helpers.php';
                include __DIR__ . '/../../../backend/reviews/helpers/reviews_db_helpers.php';
                
                $genres = get_genres_by_alphabet($conn);
                
                ?>
                <ul class="header-submenu-collections inactive-submenu">
                    <?php foreach($genres as $genre):

                        $name = htmlspecialchars($genre['name'] , ENT_QUOTES , 'UTF-8');
                    ?>
                        <li>
                            <a href=""><?= $name ?></a>
                        </li>
                    <?php endforeach; ?>
                    <li>
                        <a href="../pages/collections.php">View All</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="../pages/contact-us.php">Contact Us</a>
            </li>
        </ul>
    </nav>
    <div class="header-actions-container">
        <ul>
            <li>
                <button class="show-search-sidebar-button">
                    <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </li>
            <li>
                <button id="show-mini-wishlist-menu-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" fill="none" viewBox="0 0 24 24">
                        <path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7.694C10 3 3 3.5 3 9.5s9 11 9 11 9-5 9-11-7-6.5-9-1.806Z"/>
                    </svg>
                </button>
            </li>
            <li>
                <button id="show-mini-cart-menu-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" fill="none" viewBox="0 0 24 24">
                        <path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.3 5H21l-2 7H7.377M20 16H8L6 3H3m6 17a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm11 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>
                    </svg>
                </button>
            </li>
            <li>
                <a href="../pages/my-account.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" fill="none" viewBox="0 0 24 24">
                        <path fill="#ffffff" d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10ZM12 14.5c-5.01 0-9.09 3.36-9.09 7.5 0 .28.22.5.5.5h17.18c.28 0 .5-.22.5-.5 0-4.14-4.08-7.5-9.09-7.5Z"/>
                    </svg>
                </a>
            </li>
        </ul>
    </div>
    <div class="phone-header-leftside-container">
        <button class="show-search-sidebar-button">
            <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <div class="hamburger-menu">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
    </div>
</header>