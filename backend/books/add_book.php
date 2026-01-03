<?php

header('Content-Type: application/json');
require_once __DIR__ .  '/../../config/database.php';
require_once __DIR__ . '/../../config/helpers.php';
require_once __DIR__ . '/../validators/book_validators.php';
require_once  __DIR__ . '/../validators/book_db_validators.php';
require_once  __DIR__ . '/book_helpers.php';

$book_payload = extract_book_payload($_POST , $_FILES);

// Validation of data
$book_title_result = validate_book_title($book_payload['title']);
if(!$book_title_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $book_title_result['message']
    ]);
    exit;
}

$book_isbn_result = validate_book_isbn($book_payload['isbn']);
if(!$book_isbn_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $book_isbn_result['message']
    ]);
    exit;
}

$book_sku_result = validate_book_sku($book_payload['sku']);
if(!$book_sku_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $book_sku_result['message']
    ]);
    exit;
}

$book_language_result = validate_book_language($book_payload['language']);
if(!$book_language_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $book_language_result['message']
    ]);
    exit;
}

$book_author_result = validate_book_author($book_payload['author']);
if(!$book_author_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $book_author_result['message']
    ]);
    exit;
}

$book_genre_result = validate_book_genre($book_payload['genre']);
if(!$book_genre_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $book_genre_result['message']
    ]);
    exit;
}

$book_format_result = validate_book_format($book_payload['format']);
if(!$book_format_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $book_format_result['message']
    ]);
    exit;
}

$book_quantity_result = validate_book_quantity($book_payload['quantity']);
if(!$book_quantity_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $book_quantity_result['message']
    ]);
    exit;
}

$book_price_result = validate_book_price($book_payload['price']);
if(!$book_price_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $book_price_result['message']
    ]);
    exit;
}

$book_cover_result = validate_book_cover_file($book_payload['cover']);
if(!$book_cover_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $book_cover_result['message']
    ]);
    exit;
}

$DB_cover_filename = null;

if($book_cover_result['value'])
{
    $DB_cover_filename = upload_image($book_cover_result['value']);

    if($DB_cover_filename === false)
    {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to upload cover image'
        ]);
        exit;
    }
}

$DB_book_title = $book_title_result['value'];

// Validate DB isbn uniqueness
$DB_book_isbn = $book_isbn_result['value'];
$DB_isbn_validation_result = DB_validate_book_isbn($conn , $DB_book_isbn);
if(!$DB_isbn_validation_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $DB_isbn_validation_result['message']
    ]);
    exit;
}

// Validate DB sku uniqueness
$DB_book_sku = $book_sku_result['value']; 
$DB_sku_validation_result = DB_validate_book_sku($conn , $DB_book_sku);
if(!$DB_sku_validation_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $DB_sku_validation_result['message']
    ]);
    exit;
}


$DB_book_language = $book_language_result['value'];
$DB_book_author = $book_author_result['value'];
$DB_book_genre = $book_genre_result['value'];
$DB_book_format = $book_format_result['value'];
$DB_book_quantity = $book_quantity_result['value'];
$DB_book_price = $book_price_result['value'];

$query = <<<EOT

INSERT INTO books
(isbn , sku , title , description , language , stock_quantity, cover_image , price , genre_id , author_id , format_id)
VALUES
(? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ?);

EOT;

$stmt = $conn->prepare($query);

$stmt->bind_param("sssssisdiii" , $DB_book_isbn , $DB_book_sku , $DB_book_title , $book_payload['description'] , $DB_book_language , $DB_book_quantity , $DB_cover_filename , $DB_book_price , $DB_book_genre , $DB_book_author , $DB_book_format);

if($stmt->execute()){
    $response = ['success' => true , 'message' => 'New book is added!'];
}
else
{
    $response = ['success' => false , 'message' => 'Problem in adding book'];
}

$stmt->close();
$conn->close();

echo json_encode($response);
exit;

?>