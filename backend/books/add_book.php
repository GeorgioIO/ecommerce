<?php

header('Content-Type: application/json');
require __DIR__ . '/../../configuration/session.php';
require_once __DIR__ . '/../helpers.php';

if (!isset($_SESSION['admin_id'])) {
    respond(false , 401 , null , null , 'Unauthorized to use api');
}

if($_SERVER['REQUEST_METHOD'] === "POST")
{
    require_once __DIR__ .  '/../../configuration/database.php';
    require_once __DIR__ . '/validators/book_validators.php';
    require_once  __DIR__ . '/validators/book_db_validators.php';
    require_once  __DIR__ . '/helpers/book_helpers.php';
    require_once  __DIR__ . '/helpers/book_db_helpers.php';

    $book_payload = extract_book_payload($_POST , $_FILES);

    $book_title_validation = validate_book_title($book_payload['title']);
    if(!$book_title_validation['success'])
    {
        respond(false , 400 , null , null , $book_title_validation['message']);
    }

    $book_isbn_validation = validate_book_isbn($book_payload['isbn']);
    if(!$book_isbn_validation['success'])
    {
        respond(false , 400 , null , null , $book_isbn_validation['message']);
    }

    $book_sku_validation = validate_book_sku($book_payload['sku']);
    if(!$book_sku_validation['success'])
    {
        respond(false , 400 , null , null , $book_sku_validation['message']);
    }

    $book_language_validation = validate_book_language($book_payload['language']);
    if(!$book_language_validation['success'])
    {
        respond(false , 400 , null , null , $book_language_validation['message']);
    }

    $book_author_validation = validate_book_author($book_payload['author']);
    if(!$book_author_validation['success'])
    {
        respond(false , 400 , null , null , $book_author_validation['message']);
    }

    $book_genre_validation = validate_book_genre($book_payload['genre']);
    if(!$book_genre_validation['success'])
    {
        respond(false , 400 , null , null , $book_genre_validation['message']);
    }

    $book_format_validation = validate_book_format($book_payload['format']);
    if(!$book_format_validation['success'])
    {
        respond(false , 400 , null , null , $book_format_validation['message']);
    }

    $book_quantity_validation = validate_book_quantity($book_payload['quantity']);
    if(!$book_quantity_validation['success'])
    {
        respond(false , 400 , null , null , $book_quantity_validation['message']);
    }

    $book_price_validation = validate_book_price($book_payload['price']);
    if(!$book_price_validation['success'])
    {
        respond(false , 400 , null , null , $book_price_validation['message']);
    }

    if($book_payload['is_on_sale'] === "1")
    {
        $book_discount_validation = validate_book_discount_percentage($book_payload['discount_percentage']);
        if(!$book_discount_validation['success'])
        {
            respond(false , 400 , null , null , $book_discount_validation['message']);
        }
    }

    $book_cover_validation = validate_book_cover_file($book_payload['cover']);
    if(!$book_cover_validation['success'])
    {
        respond(false , 400 , null , null , $book_cover_validation['message']);
    }

    $cover_filename = null;

    if($book_cover_validation['value'])
    {
        $cover_filename = upload_image($book_cover_validation['value']);

        if($cover_filename === false)
        {
            respond(false , 400 , null , null , $book_cover_validation['message']);
        }
    }

    $book_title = $book_title_validation['value'];

    // ! Validate DB isbn uniqueness
    $book_isbn = $book_isbn_validation['value'];
    $isbn_is_unique = DB_validate_book_isbn($conn , $book_isbn);
    if(!$isbn_is_unique['success'])
    {
        respond(false , 400 , null , null , $isbn_is_unique['message']);
    }

    // ! Validate DB sku uniqueness
    $book_sku = $book_sku_validation['value']; 
    $sku_is_unique = DB_validate_book_sku($conn , $book_sku);
    if(!$sku_is_unique['success'])
    {
        respond(false , 400 , null , null , $sku_is_unique['message']);
    }


    $book_language = $book_language_validation['value'];
    $book_author = $book_author_validation['value'];
    $book_genre = $book_genre_validation['value'];
    $book_format = $book_format_validation['value'];
    $book_quantity = $book_quantity_validation['value'];
    $book_price = $book_price_validation['value'];
    $book_in_stock = $book_quantity === 0 ? 0 : 1;
    $book_on_sale = !$book_payload['is_on_sale'] ? 0 : 1; 

    if($book_payload['is_on_sale'] === "1")
    {
        $book_discount = $book_discount_validation['value'];
    }

    $conn->begin_transaction();
    try
    {
        $query = form_add_book_query($book_payload['is_on_sale']);
        $insert_stmt = $conn->prepare($query);

        if($book_payload['is_on_sale'] === "1")
        {
            $insert_stmt->bind_param("sssssiiiisdiii" , $book_isbn , $book_sku , $book_title , $book_payload['description'] , $book_language , $book_quantity , $book_in_stock , $book_on_sale , $book_discount , $cover_filename , $book_price , $book_genre , $book_author , $book_format);
        }
        else
        {
            $insert_stmt->bind_param("sssssiisdiii" , $book_isbn , $book_sku , $book_title , $book_payload['description'] , $book_language , $book_quantity , $book_in_stock , $cover_filename , $book_price , $book_genre , $book_author , $book_format);
        }

        $insert_stmt->execute();

        // ! Add slug to book
        $insert_id = $insert_stmt->insert_id;
        $book_slug = create_book_slug($book_title , $insert_id);
        $update_query = "UPDATE books SET slug = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("si" , $book_slug , $insert_id);
        $update_stmt->execute();

        if($update_stmt->error || $update_stmt->affected_rows === 0)
        {
            throw new Exception("Error adding slug");
        }

        $conn->commit();
        $insert_stmt->close();
        $update_stmt->close();

        respond(true , 201 , null , null , 'Book is added successfully');

    }
    catch(Exception $e)
    {
         respond(false , 500 , null , null , 'Something went wrong in adding book : ' , $e->getMessage());
    }
}
else
{
    respond(false , 400 , null , null , 'Wrong method used');
}

?>