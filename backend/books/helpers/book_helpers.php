<?php

function create_book_slug ($title , $id)
{
    // Convert to lowercase
    $slug = strtolower($title);

    // Remove any character that is not a letter, number, or space
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

    // Replace spaces and multiple dashes with a single dash
    $slug = preg_replace('/[\s-]+/', '-', $slug);

    // Trim any leading or trailing dash
    $slug = trim($slug, '-');

    // Append the ID at the end
    $slug .= '-' . $id;

    return $slug;
}

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



?>