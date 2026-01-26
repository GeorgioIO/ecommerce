<?php

function get_new_order_email_bd($data) {
    return '
        <p>
            A new order has been placed in the system.
        </p>

        <ul>
            <li><strong>Order ID:</strong> #' . $data['order_id'] . '</li>
            <li><strong>Order Code:</strong> #' . $data['order_code'] . '</li>
            <li><strong>Customer:</strong> ' . $data['customer_name'] . '</li>
            <li><strong>Total Amount:</strong> $' . $data['price'] . '</li>
            <li><strong>Order Date:</strong> ' . date("Y-m-d H:i") . '</li>
        </ul>

        <p>
            Please review and process this order from the admin dashboard.
        </p> ';
}


?>