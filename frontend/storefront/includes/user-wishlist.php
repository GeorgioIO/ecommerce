<?php 
    
    require_once __DIR__ . '../../../../backend/wishlist/helpers/wishlist_db_helpers.php';
    $page = 1;
    $perPage = 5;
    $data = get_wishlist_items($conn , $_SESSION['user_id'] , $page , $perPage);
    $wishlist_items = $data['data'];
    $pagination = $data['pagination'];
    
?>


<main>
    <section id="wishlist">
        <h3 class="section-title">My wishlist</h3>
        <div class="products-grid">
            
        </div>
    </section>
</main>