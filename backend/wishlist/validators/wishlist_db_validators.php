<?php

function validate_book_in_wishlist($conn , $user_id , $book_id)
{
    $query = <<<SQL
        SELECT
            user_id,
            book_id
        FROM
            wishlist_items
        WHERE user_id = ? AND book_id = ?;
    SQL;

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii" , $user_id , $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Book found in wishlist
    if($result->num_rows === 1)
    {
        return [
            'success' => false,
            'message' => 'Book already in wishlist'
        ];
    }
    else
    {
        return [
            'success' => true
        ];
    }
}

?>