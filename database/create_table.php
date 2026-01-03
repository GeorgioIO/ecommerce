<?php

require_once '../config/database.php';

// Genres Table
$conn->query("
    CREATE TABLE IF NOT EXISTS Genres (
        id INT AUTO_INCREMENT,
        name VARCHAR(45) NOT NULL UNIQUE,
        image VARCHAR(255) NOT NULL,
        PRIMARY KEY(id)
    );
");

// Authors Table
$conn->query("
    CREATE TABLE IF NOT EXISTS Authors (
        id INT AUTO_INCREMENT,
        name VARCHAR(45) NOT NULL UNIQUE,
        PRIMARY KEY(id)
    );
");

// Formats Table
$conn->query("
    CREATE TABLE IF NOT EXISTS book_formats(
        id INT AUTO_INCREMENT,
        name VARCHAR(45) NOT NULL UNIQUE,
        PRIMARY KEY(id)
    )");

// Books Table
$conn->query("
    CREATE TABLE IF NOT EXISTS Books (
        id INT AUTO_INCREMENT,
        isbn VARCHAR(17) NOT NULL UNIQUE,
        sku VARCHAR(255) NOT NULL UNIQUE,
        title VARCHAR(100) NOT NULL,
        description VARCHAR(255),
        language VARCHAR(100) NOT NULL CHECK(language IN('French' , 'English' , 'Not Defined')),
        stock_quantity INT CHECK(stock_quantity >= 0),
        cover_image VARCHAR(255),
        price DECIMAL(10,2) NOT NULL,
        date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
        genre_id INT,
        author_id INT,
        format_id INT,
        PRIMARY KEY (id),
        FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE,
        FOREIGN KEY (genre_id) REFERENCES genres(id), 
        FOREIGN KEY (format_id) REFERENCES book_formats(id) 
    );
");

// Billing Addresses Table
$conn->query("
    CREATE TABLE IF NOT EXISTS billing_addresses (
        id INT AUTO_INCREMENT,
        first_name VARCHAR(255) NOT NULL,
        last_name VARCHAR(255) NOT NULL,
        email VARCHAR(55) NOT NULL,
        phone_number VARCHAR(25) NOT NULL,
        state VARCHAR(45) NOT NULL,
        city VARCHAR(45) NOT NULL,
        additional_notes VARCHAR(255),
        PRIMARY KEY (id)
    )
");

// Users Table
$conn->query("
    CREATE TABLE IF NOT EXISTS Users(
        id INT AUTO_INCREMENT,
        address_id INT,
        customer_code VARCHAR(35) NOT NULL UNIQUE,
        name VARCHAR(255) NOT NULL UNIQUE,
        email VARCHAR(255) NOT NULL UNIQUE,
        phone_number VARCHAR(25) UNIQUE,
        password VARCHAR(255) NOT NULL,
        profile_image VARCHAR(255),
        date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        FOREIGN KEY (address_id) REFERENCES billing_addresses (id)
    );
");

// Orders Table
$conn->query("
    CREATE TABLE IF NOT EXISTS Orders (
        id INT AUTO_INCREMENT,
        user_id INT,
        order_code VARCHAR(35) NOT NULL UNIQUE,
        status enum('Pending','Processing','Shipped','Delivered','Cancelled','Refunded') NOT NULL,
        total_price DECIMAL(10,2),
        billing_id INT,
        date_added DATETIME DEFAULT CURRENT_TIMESTAMP,  
        PRIMARY KEY (id),
        FOREIGN KEY (billing_id) REFERENCES billing_addresses(id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    );
");


// Wishlist_items Table
$conn->query("
    CREATE TABLE IF NOT EXISTS wishlist_items (
        user_id INT,
        book_id INT,
        PRIMARY KEY (user_id , book_id),
        FOREIGN KEY (user_id) REFERENCES users (id),
        FOREIGN KEY (book_id) REFERENCES books (id)
    );
");

// order_items Table
$conn->query("
    CREATE TABLE IF NOT EXISTS order_items (
        id INT AUTO_INCREMENT,
        order_id INT,
        book_id INT,
        quantity INT,
        price DECIMAL(10,2),
        PRIMARY KEY (id),
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
        FOREIGN KEY (book_id) REFERENCES books(id)
    );
");

// Reviews Table
$conn->query("
    CREATE TABLE IF NOT EXISTS reviews (
        id INT AUTO_INCREMENT,
        user_id INT,
        book_id INT,
        text VARCHAR(255) NOT NULL,
        rating INT CHECK(rating BETWEEN 1 AND 5),
        PRIMARY KEY (id),
        FOREIGN KEY (user_id) REFERENCES users (id),
        FOREIGN KEY (book_id) REFERENCES books (id)
    );
");

// Carts Table
$conn->query("
    CREATE TABLE IF NOT EXISTS cart_items (
        user_id INT,
        book_id INT,
        quantity INT NOT NULL DEFAULT 1,
        added_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (user_id , book_id),
        FOREIGN KEY (user_id) REFERENCES users (id),
        FOREIGN KEY (book_id) REFERENCES books (id)
    );
");

// Indexes
$conn->query("CREATE INDEX IF NOT EXISTS username_index ON users (name);");
$conn->query("CREATE INDEX IF NOT EXISTS email_index ON users (email);");
$conn->query("CREATE INDEX IF NOT EXISTS booktitle_index ON books (title);");
$conn->query("CREATE INDEX IF NOT EXISTS bookprice_index ON books (price);");
$conn->query("CREATE INDEX IF NOT EXISTS customerorders_index ON orders (user_id)");
$conn->query("CREATE INDEX IF NOT EXISTS ordersstatus_index ON orders (status)");
$conn->query("CREATE INDEX IF NOT EXISTS ordersdate_index ON orders (date_added)");
$conn->query("CREATE INDEX IF NOT EXISTS orders_products_index ON order_items (book_id);");
$conn->query("CREATE INDEX IF NOT EXISTS order_items_index ON order_items (order_id);");

?>
