<?php


if($_SERVER['REQUEST_METHOD'] === "GET")
{
    header('Content-Type: application/json');
    require_once  __DIR__ . '/../../configuration/database.php';
    require_once  __DIR__ . '/validators/book_validators.php';
    require_once __DIR__ . '/../helpers.php';

    $book_id = $_GET["id"] ?? null;

    $book_id_validation = validate_entity_ID($book_id);
    if(!$book_id_validation['valid'])
    {
        respond(false , 400 , null , null , $book_id_validation['message']);
    }

    $query = <<<EOT
        SELECT 
            id,
            isbn,
            sku,
            title,
            description,
            language,
            stock_quantity,
            cover_image,
            price,
            is_onSale,
            discount_percentage,
            genre_id,
            author_id,
            format_id,
            slug
        FROM
            books 
        WHERE id = ? 
    EOT;

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $book_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows === 0){
        respond(false , 404 , null , null , 'Book not found');
    }

    $book = $result->fetch_assoc();

    respond(true , 200 , $book , null , null);
}
else
{
    respond(false , 400 , null , null , 'Wrong method used in getting book');
}


?>
