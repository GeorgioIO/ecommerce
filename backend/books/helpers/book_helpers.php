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
        'price' => $post['price'] ?? null
    ];
}

?>