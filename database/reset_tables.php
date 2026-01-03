<?php

require_once '../config/database.php';

$conn->query("DROP TABLE IF EXISTS book_languages;");
$conn->query("DROP TABLE IF EXISTS authored;");
$conn->query("DROP TABLE IF EXISTS authors;");
$conn->query("DROP TABLE IF EXISTS wishlist_items;");
$conn->query("DROP TABLE IF EXISTS order_items;");
$conn->query("DROP TABLE IF EXISTS billing_addresses;");
$conn->query("DROP TABLE IF EXISTS cart_items");
$conn->query("DROP TABLE IF EXISTS reviews;");
$conn->query("DROP TABLE IF EXISTS books;");
$conn->query("DROP TABLE IF EXISTS orders;");
$conn->query("DROP TABLE IF EXISTS languages;");
$conn->query("DROP TABLE IF EXISTS genres;");
$conn->query("DROP TABLE IF EXISTS users;");


$conn->query("DROP INDEX IF EXISTS username_index ON users;");
$conn->query("DROP INDEX IF EXISTS email_index ON users;");
$conn->query("DROP INDEX IF EXISTS booktitle_index ON books;");
$conn->query("DROP INDEX IF EXISTS bookprice_index ON books;");
$conn->query("DROP INDEX IF EXISTS booksaleprice_index ON books;");
$conn->query("DROP INDEX IF EXISTS booklanguage_index ON book_languages;");
$conn->query("DROP INDEX IF EXISTS bookauthor_index ON authored;");
$conn->query("DROP INDEX IF EXISTS customerorders_index ON orders;");
$conn->query("DROP INDEX IF EXISTS ordersstatus_index ON orders;");
$conn->query("DROP INDEX IF EXISTS ordersdate_index ON orders;");
$conn->query("DROP INDEX IF EXISTS orders_products_index ON order_items;");
$conn->query("DROP INDEX IF EXISTS order_items_index ON order_items;");



?>