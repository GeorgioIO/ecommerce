<?php

require_once __DIR__ . '/../config/database.php';


$conn->query("SET FOREIGN_KEY_CHECKS = 0");

$conn->query("DROP TABLE IF EXISTS authors;");
$conn->query("DROP TABLE IF EXISTS wishlist_items;");
$conn->query("DROP TABLE IF EXISTS order_items;");
$conn->query("DROP TABLE IF EXISTS shipping_addresses;");
$conn->query("DROP TABLE IF EXISTS cart_items");
$conn->query("DROP TABLE IF EXISTS reviews;");
$conn->query("DROP TABLE IF EXISTS books;");
$conn->query("DROP TABLE IF EXISTS orders;");
$conn->query("DROP TABLE IF EXISTS languages;");
$conn->query("DROP TABLE IF EXISTS genres;");
$conn->query("DROP TABLE IF EXISTS users;");
$conn->query("DROP TABLE IF EXISTS book_formats;");
$conn->query("DROP TABLE IF EXISTS user_addresses;");

$conn->query("DROP TRIGGER IF EXISTS after_book_stock_update;");

$conn->query("SET FOREIGN_KEY_CHECKS = 1");

?>