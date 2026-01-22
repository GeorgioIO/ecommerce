<?php

function extract_author_payload(array $post) : array
{
    return [
        'id' => $post['id'] ?? null,
        'name' => $post['name'] ?? null,
        
    ];
}

?>