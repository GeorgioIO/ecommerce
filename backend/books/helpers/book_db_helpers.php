<?php

function get_books_under_price($conn , $price , $user_id=null)
{
    $query = <<<SQL

    SELECT
        b.id,
        b.title,
        b.price,
        b.cover_image,
        a.name AS author_name,
        bf.name AS format_name,
        b.is_onSale,
        b.is_inStock,
        b.discount_percentage,
        CASE WHEN ci.book_id IS NOT NULL THEN 1 ELSE 0 END AS is_inCart,
        CASE WHEN w.book_id IS NOT NULL THEN 1 ELSE 0 END AS is_inWishlist,
        CASE WHEN b.is_onSale = 1 THEN ROUND(b.price - (b.price * b.discount_percentage) / 100 , 2) ELSE b.price END AS final_price
        FROM books b
        LEFT JOIN wishlist_items w ON b.id = w.book_id AND w.user_id = ?
        JOIN authors a ON b.author_id = a.id
        JOIN book_formats bf ON b.format_id = bf.id
        LEFT JOIN carts c ON c.user_id = ? AND c.status = 'active' 
        LEFT JOIN cart_items ci ON b.id = ci.book_id AND ci.cart_id = c.id
        WHERE 
            (
                CASE
                    WHEN b.is_onSale = 1
                        THEN ROUND(b.price - (b.price * b.discount_percentage) / 100, 2)
                    ELSE b.price
                END
            )  <= ?
        LIMIT 10;
    SQL;

    $uid = $user_id ? $user_id : 0;

    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii" , $uid , $uid ,  $price);
    $stmt->execute();
    $result = $stmt->get_result();
    $books_under_price = [];

    if($result)
    {
        while($row = $result->fetch_assoc())
        {
            $books_under_price[] = $row;
        }
    }

    return $books_under_price;
}

function get_new_arrivals_books($conn , $user_id = null)
{
    $query = "
        SELECT 
            b.id,
            b.title,
            b.price,
            b.cover_image,
            a.name AS author_name,
            bf.name AS format_name,
            b.is_onSale,
            b.is_inStock,
            b.discount_percentage,
            CASE WHEN ci.book_id IS NOT NULL THEN 1 ELSE 0 END AS is_inCart, 
            CASE WHEN w.book_ID IS NOT NULL THEN 1 ELSE 0 END AS is_inWishlist,
            CASE WHEN b.is_onSale = 1 THEN ROUND(b.price - (b.price * b.discount_percentage) / 100 , 2) ELSE b.price END AS final_price
        FROM books b
        JOIN authors a ON b.author_id = a.id
        JOIN book_formats bf ON b.format_id = bf.id
        LEFT JOIN wishlist_items w ON b.id = w.book_id AND w.user_id = ?
        LEFT JOIN carts c ON c.user_id = ? AND c.status = 'active' 
        LEFT JOIN cart_items ci ON b.id = ci.book_id AND ci.cart_id = c.id
        ORDER BY b.date_added DESC
        LIMIT 10;
    ";

    $uid = $user_id ? $user_id : 0;

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii" , $uid , $uid);
    $stmt->execute();
    $result = $stmt->get_result();

    $new_arrivals = [];

    if($result)
    {
        while($row = $result->fetch_assoc())
        {
            $new_arrivals[] = $row;
        }
    }

    return $new_arrivals;
}

function get_best_seller_books($conn , $user_id= null)
{
    $query = "
        SELECT
            b.id,
            b.title,
            b.price,
            b.cover_image,
            a.name AS author_name,
            bf.name AS format_name,
            b.is_inStock,
            b.is_onSale,
            b.discount_percentage,
            CASE WHEN w.book_id IS NOT NULL THEN 1 ELSE 0 END AS is_inWishlist,
            CASE WHEN ci.book_id IS NOT NULL THEN 1 ELSE 0 END AS is_inCart,  
            CASE WHEN b.is_onSale = 1 THEN ROUND(b.price - (b.price * b.discount_percentage) / 100 , 2) ELSE b.price END AS final_price,
            COUNT(o.id) AS total_orders
        FROM books b
        JOIN order_items oi ON b.id = oi.book_id
        JOIN orders o ON oi.order_id = o.id 
        JOIN authors a ON b.author_id = a.id
        JOIN book_formats bf ON b.format_id = bf.id
        LEFT JOIN wishlist_items w ON b.id = w.book_id AND w.user_id = ?
        LEFT JOIN carts c ON c.user_id = ? AND c.status = 'active' 
        LEFT JOIN cart_items ci ON b.id = ci.book_id AND ci.cart_id = c.id
        WHERE o.status NOT IN ('Refunded' , 'Cancelled' , 'Pending' , 'Processing')
        GROUP BY b.id 
        LIMIT 10;
    ";

    $uid = $user_id ? $user_id : 0;

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii" , $uid , $uid);
    $stmt->execute();
    $result = $stmt->get_result();


    $best_sellers = [];

    if($result)
    {
        while($row = $result->fetch_assoc())
        {
            $best_sellers[] = $row;
        }
    }

    return $best_sellers;
}

function form_load_books_query($author_id , $genre_id)
{
    if($author_id === null && $genre_id === null)
    {
        return <<<EOT
        SELECT 
            b.id,
            b.isbn,
            b.sku,
            g.name AS genre_title,
            b.title,
            a.name AS author_name,
            bf.name AS format,
            b.description,
            b.language,
            b.stock_quantity,
            b.is_inStock,
            b.cover_image,
            b.price,
            b.is_onSale,
            b.discount_percentage,
            CASE 
                WHEN b.is_onSale = 1
                    THEN ROUND(b.price - (b.price * b.discount_percentage) / 100 , 2)
                ELSE
                    b.price
            END AS final_price
        FROM books b
        LEFT JOIN genres g ON b.genre_id = g.id
        LEFT JOIN authors a ON b.author_id = a.id
        LEFT JOIN book_formats bf ON b.format_id = bf.id
        ORDER BY b.title ASC
        
        EOT;
    }
    elseif ($author_id)
    {
        return <<<EOT
        SELECT 
            b.id,
            b.isbn,
            b.sku,
            g.name AS genre_title,
            b.title,
            a.name AS author_name,
            bf.name AS format,
            b.description,
            b.language,
            b.stock_quantity,
            b.is_inStock,
            b.cover_image,
            b.price,
            b.is_onSale,
            b.discount_percentage,
            CASE 
                WHEN b.is_onSale = 1
                    THEN ROUND(b.price - (b.price * b.discount_percentage) / 100 , 2)
                ELSE
                    b.price
            END AS final_price
        FROM books b
        LEFT JOIN genres g ON b.genre_id = g.id
        LEFT JOIN authors a ON b.author_id = a.id
        LEFT JOIN book_formats bf ON b.format_id = bf.id
        WHERE b.author_id = ?
        ORDER BY b.title ASC
       
        
        EOT;
    }
    elseif($genre_id)
    {
                return <<<EOT
        SELECT 
            b.id,
            b.isbn,
            b.sku,
            g.name AS genre_title,
            b.title,
            a.name AS author_name,
            bf.name AS format,
            b.description,
            b.language,
            b.stock_quantity,
            b.is_inStock,
            b.cover_image,
            b.price,
            b.is_onSale,
            b.discount_percentage,
            CASE 
                WHEN b.is_onSale = 1
                    THEN ROUND(b.price - (b.price * b.discount_percentage) / 100 , 2)
                ELSE
                    b.price
            END AS final_price
        FROM books b
        LEFT JOIN genres g ON b.genre_id = g.id
        LEFT JOIN authors a ON b.author_id = a.id
        LEFT JOIN book_formats bf ON b.format_id = bf.id
        WHERE b.genre_id = ?
        ORDER BY b.title ASC
       
        EOT;
    }
}


function get_book_data($conn , $id)
{
    $query = <<<EOT
        SELECT
            title,
            isbn,
            sku,
            stock_quantity AS old_stock
        FROM 
            books
        WHERE id = ?
    EOT;

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc();
}

function get_book_title($conn , $id)
{
    $query = <<<EOT
        SELECT
            title
        FROM 
            books
        WHERE id = ?
    EOT;
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc()['title'];
}


function get_book_stock($conn , $id)
{
    $query = <<<EOT
        SELECT
            stock_quantity
        FROM 
            books
        WHERE id = ?
    EOT;
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc()['stock_quantity'];
}

function get_book_price($conn , $id)
{
    $query = <<<EOT
        SELECT
            price
        FROM 
            books
        WHERE id = ?
    EOT;
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc();
}

?>