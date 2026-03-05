<?php

$id = htmlspecialchars($product['id'] , ENT_QUOTES , 'UTF-8');
$author_id = htmlspecialchars($product['author_id'] , ENT_QUOTES , 'UTF-8');
$product_title = htmlspecialchars($product['title'] , ENT_QUOTES ,'UTF-8');
$image = htmlspecialchars($product['cover_image'] , ENT_QUOTES , 'UTF-8');
$url = "../../../assets/images/$image";
$price = htmlspecialchars($product['price'] , ENT_QUOTES , 'UTF-8');
$language = htmlspecialchars($product['language'] , ENT_QUOTES , 'UTF-8');
$genre = htmlspecialchars($product['genre_name'] , ENT_QUOTES , 'UTF-8');
$author = htmlspecialchars($product['author_name'] , ENT_QUOTES , 'UTF-8');
$book_format = htmlspecialchars($product['format_name'] , ENT_QUOTES , 'UTF-8');
$cart_quantity = htmlspecialchars($product['cart_quantity'] , ENT_QUOTES , 'UTF-8');
$in_cart = htmlspecialchars($product['is_inCart'] , ENT_QUOTES , 'UTF-8');
$is_inStock = htmlspecialchars($product['is_inStock'] , ENT_QUOTES , 'uTF-8');
?>


<section id="product" data-productid="<?= $id ?>">
    <figure>
        <img src="<?= $url ?>" alt="<?= $product_title ?>">
    </figure>
    <div class="product-information">
        <h2 class="product-title"><?= $product_title ?></h2>
        <a href="../pages/products.php?author=<?= $author_id ?>" class="product-author"> By <?= $author ?></a>
        <p class="product-genre"> Genre : <?= $genre ?></p>
        <div class="product-price-container">
            <?php
            
            echo $product['is_onSale'] === 1 ?
            "<span class='pre-sale-price'> \${$product['price']} </span> <span class='post-sale-price'> \${$product['final_price']} </span>" 
            : 
            "<span class='base-price'> \${$product['price']} </span>"
            ;

            ?>
        </div>
        <div class="product-buttons-container">
            <input  type="number" id="single-product-quantity" value="<?= $cart_quantity ?>" min="1"/>
            <?php

                if ($product['is_inStock'] === 1) {
                    echo '<button id="single-product-adc-button" >ADD TO CART</button>';
                    echo '<button id="buy-now-button">BUY IT NOW</button>';
                    }
                else
                {
                    echo '<button id="single-product-ofs-button" disabled> OUT OF STOCK </button>';
                }
            
            ?>
            
        </div>
        <div class="product-description">
            <p><?= $product['description'] ?></p>
        </div>
        <table class="additional-product-information">
            <tbody>
                <tr>
                    <th>ISBN</th>
                    <td><?= $product['isbn'] ?></td>
                </tr>
                <tr>
                    <th>Author</th>
                    <td><?= $product['author_name'] ?></td>
                </tr>
                <tr>
                    <th>Genre</th>
                    <td><?= $product['genre_name'] ?></td>
                </tr>
                <tr>
                    <th>Language</th>
                    <td><?= $product['language'] ?></td>
                </tr>
                <tr>
                    <th>Format</th>
                    <td><?= $product['format_name'] ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<?php

include __DIR__ . '../../includes/advertisement.php';

include __DIR__ . '../../includes/services.php';

?>