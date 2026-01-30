<?php

function get_genres_by_order_counts($conn)
{
    $result = $conn->query("        
        SELECT
            g.id,
            g.name,
            g.image,
            COUNT(o.id) AS total_orders
        FROM
            genres g
        JOIN books b ON g.id = b.genre_id
        LEFT JOIN order_items oi ON b.id = oi.book_id
        LEFT JOIN orders o ON o.id = oi.order_id
        GROUP BY g.id , g.name , g.image
        ORDER BY total_orders DESC
        LIMIT 4
        ");
    
        $genres = [];

        if($result)
        {
            while($row = $result->fetch_assoc())
            {
                $genres[] = $row;
            }
        }

    return $genres;
}

function get_genres_by_alphabet($conn)
{
    $result = $conn->query("        
        SELECT
            g.id,
            g.name
        FROM
            genres g
        ORDER BY RAND()
        LIMIT 6;
        ");
    
        $genres = [];

        if($result)
        {
            while($row = $result->fetch_assoc())
            {
                $genres[] = $row;
            }
        }

    return $genres;
}

?>