<?php


function get_store_reviews($conn)
{
    $result = $conn->query("
    SELECT
        r.id,
        u.name AS customer_name,
        r.text,
        r.rating
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    WHERE r.type = 'store';
    ");

    $store_reviews = [];

    if($result)
    {
        while($row = $result->fetch_assoc())
        {
            $store_reviews[] = $row;
        }
    }

    return $store_reviews;
}


?>