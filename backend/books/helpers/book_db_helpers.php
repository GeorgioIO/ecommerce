<?php

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