<?php

function get_new_arrivals_books($conn)
{
    $result = $conn->query("
        SELECT 
            b.id,
            b.title,
            b.price,
            b.cover_image,
            a.name AS author_name,
            bf.name AS format_name
        FROM books b
        JOIN authors a ON b.author_id = a.id
        JOIN book_formats bf ON b.format_id = bf.id
        ORDER BY b.date_added DESC
        LIMIT 10;
    ");

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

function get_best_seller_books($conn)
{
    $result = $conn->query("
        SELECT
            b.id,
            b.title,
            b.price,
            b.cover_image,
            a.name AS author_name,
            bf.name AS format_name,
            COUNT(o.id) AS total_orders
        FROM books b
        JOIN order_items oi ON b.id = oi.book_id
        JOIN orders o ON oi.order_id = o.id 
        JOIN authors a ON b.author_id = a.id
        JOIN book_formats bf ON b.format_id = bf.id
        WHERE o.status NOT IN ('Refunded' , 'Cancelled' , 'Pending' , 'Processing')
        GROUP BY b.id , b.title , b.cover_image , a.name , bf.name 
        LIMIT 10;
    ");
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
            b.price
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
            b.price
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
            b.price
        FROM books b
        LEFT JOIN genres g ON b.genre_id = g.id
        LEFT JOIN authors a ON b.author_id = a.id
        LEFT JOIN book_formats bf ON b.format_id = bf.id
        WHERE b.genre_id = ?
        ORDER BY b.title ASC
       
        EOT;
    }
}

?>