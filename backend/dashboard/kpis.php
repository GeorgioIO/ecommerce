<?php

/*

This file will return :
total orders
total customers
total revenue
pending orders
out of stock books

*/

header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';

try
{
    // Total Orders
    $result = $conn->query("SELECT COUNT(*) AS total_orders FROM orders");
    $total_orders = (int) $result->fetch_assoc()['total_orders'];

    

    // Total Customers
    $result = $conn->query("SELECT COUNT(*) AS total_customers FROM users WHERE role = 'Customer'");
    $total_customers = (int) $result->fetch_assoc()['total_customers'];

    // Total Revenue
    $result = $conn->query("SELECT SUM(total_price) AS total_revenue FROM orders WHERE status != 'Cancelled'");
    $total_revenue = (float) $result->fetch_assoc()['total_revenue'];

    // Pending orders
    $result = $conn->query("SELECT COUNT(*) AS total_pending_orders FROM orders WHERE status = 'Pending'");
    $total_pending_orders = (int) $result->fetch_assoc()['total_pending_orders'];

    // Out of stock books
    $result = $conn->query("SELECT COUNT(*) AS total_out_of_stock_books FROM books WHERE is_InStock = 0");
    $total_out_of_stock_books = (int) $result->fetch_assoc()['total_out_of_stock_books'];

    echo json_encode([
        'success' => true,
        'value' => [
            'total_orders' => $total_orders,
            'total_customers' => $total_customers,
            'total_revenue' => $total_revenue,
            'total_pending_orders' => $total_pending_orders,
            'total_ofs_books' => $total_out_of_stock_books
        ]
    ]);
}
catch (Exception $e)
{
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);

}

$conn->close();
exit;

?>