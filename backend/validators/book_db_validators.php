<?php

require_once  __DIR__ . '/../../config/database.php';
require_once  __DIR__ . '/../../config/helpers.php';

function DB_validate_book_isbn($conn , $isbn , $id=null)
{
    
    if($id !== null)
    {
        $query = "SELECT id FROM books WHERE isbn = ? AND id <> ? LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si" , $isbn , $id);
    }
    else
    {
        $query = "SELECT id FROM books WHERE isbn = ?  LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s" , $isbn);
    }

    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0)
    {
        return [
            'success' => false,
            'message' => 'A book with this ISBN already exists'
        ];
    }

    return [
        'success' => true
    ];
}

function DB_validate_book_sku($conn , $sku , $id=null)
{
    if($id !== null)
    {
        $query = "SELECT id FROM books WHERE sku = ? AND id <> ? LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si" , $sku , $id);
    }
    else
    {
        $query = "SELECT id FROM books WHERE sku = ?  LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s" , $sku);
    }

    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0)
    {
        return [
            'success' => false,
            'message' => 'A book with this SKU already exixts'
        ];
    }

    return [
        'success' => true
    ];
}

?>