<?php

function extract_genre_payload(array $post , array $files) : array
{
    return [
        'id' => $post['id'] ?? null,
        'name' => $post['name'] ?? null,
        'image' => $files['image'] ?? null,
    ];
}

?>