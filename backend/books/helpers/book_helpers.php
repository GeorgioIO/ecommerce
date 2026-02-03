<?php

function extract_book_payload(array $post , array $files) : array
{
    return [
        'id' => $post['id'] ?? null,
        'title' => $post['title'] ?? null,
        'isbn' => $post['isbn'] ?? null,
        'sku' => $post['sku'] ?? null,
        'language' => $post['language'] ?? null,
        'author' => $post['author'] ?? null,
        'cover' => $files['cover'] ?? null,
        'description' => $post['description'] ?? null,
        'genre' => $post['genre'] ?? null,
        'format' => $post['format'] ?? null,
        'quantity' => $post['quantity'] ?? null,
        'price' => $post['price'] ?? null,
        'is_on_sale' => $post['is_on_sale'] ?? null,
        'discount_percentage' => $post['discount_percentage'] ?? null
    ];
}


function form_add_book_query($is_onsale)
{
    if($is_onsale)
    {
        return 
        <<<EOT
            INSERT INTO books
            (isbn , sku , title , description , language , stock_quantity , is_inStock , is_onSale , discount_percentage , cover_image , price , genre_id , author_id , format_id)
            VALUES
            (? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ?);
        EOT;
    }
    else
    {
        return 
        <<<EOT
            INSERT INTO books
            (isbn , sku , title , description , language , stock_quantity , is_inStock , cover_image , price , genre_id , author_id , format_id)
            VALUES
            (? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ?);
        EOT;
    }
}
?>