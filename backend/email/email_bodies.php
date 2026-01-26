<?php

function get_new_order_email_bd($data) {
    $order = $data['order_data'];

    $body = <<<EOT
        <div style="font-family: Arial, Helvetica, sans-serif; color:#333; line-height:1.6; max-width:600px; margin:0 auto;">

        <h2 style="background:#2563eb; color:#fff; padding:12px 16px; border-radius:6px;">
            📦New Order {$order['order_code']} has been placed 
        </h2>

        <p>
            This is to inform you that your order has been placed.
            If you did not place this order, please contact our support team immediately at
            <a href="mailto:support@booknest.com">support@booknest.com</a>.
        </p>

        <h3 style="border-bottom:1px solid #ddd; padding-bottom:6px;">Order Details</h3>
        <table width="100%" cellpadding="6" cellspacing="0">
            <tr><td><strong>ID</strong></td><td>#{$order['id']}</td></tr>
            <tr><td><strong>Code</strong></td><td>{$order['order_code']}</td></tr>
            <tr><td><strong>Customer</strong></td><td>{$order['customer_name']}</td></tr>
            <tr><td><strong>Status</strong></td><td>{$order['status']}</td></tr>
            <tr><td><strong>Total Price</strong></td><td>\${$order['total_price']}</td></tr>
            <tr><td><strong>Created At</strong></td><td>{$order['date_added']}</td></tr>
        </table>

        <h3 style="border-bottom:1px solid #ddd; padding-bottom:6px; margin-top:24px;">Shipping Address</h3>
        <table width="100%" cellpadding="6" cellspacing="0">
            <tr><td><strong>Name</strong></td><td>{$order['first_name']} {$order['last_name']}</td></tr>
            <tr><td><strong>Email</strong></td><td>{$order['email']}</td></tr>
            <tr><td><strong>Phone</strong></td><td>{$order['phone_number']}</td></tr>
            <tr><td><strong>State</strong></td><td>{$order['state']}</td></tr>
            <tr><td><strong>City</strong></td><td>{$order['city']}</td></tr>
            <tr><td><strong>Address</strong></td><td>{$order['address_line1']} {$order['address_line2']}</td></tr>
            <tr><td><strong>Notes</strong></td><td>{$order['additional_notes']}</td></tr>
        </table>

        <h3 style="border-bottom:1px solid #ddd; padding-bottom:6px; margin-top:24px;">Order Items</h3>
        <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse:collapse;">
            <thead>
                <tr style="background:#f3f4f6;">
                    <th align="left" style="border:1px solid #ddd;">Book</th>
                    <th align="center" style="border:1px solid #ddd;">Qty</th>
                    <th align="right" style="border:1px solid #ddd;">Unit Price</th>
                    <th align="right" style="border:1px solid #ddd;">Total</th>
                </tr>
            </thead>
            <tbody>
        EOT;

    foreach ($data['order_lines'] as $line) {
        $body .= "
            <tr>
                <td style='border:1px solid #ddd;'>{$line['title']}</td>
                <td align='center' style='border:1px solid #ddd;'>{$line['quantity']}</td>
                <td align='right' style='border:1px solid #ddd;'>\${$line['selling_price']}</td>
                <td align='right' style='border:1px solid #ddd;'>\${$line['total_line_price']}</td>
            </tr>
        ";
    }

    $body .= <<<EOT
        </tbody>
    </table>

    <p style="margin-top:24px; font-size:13px; color:#666;">
        — BookNest Team
    </p>

</div>
EOT;

    return $body;
}

function get_low_stock_email_bd($data) {
    return '
        <p>
            <strong>⚠️ Low stock alert.</strong>
        </p>

        <p>
            The following product is running low on inventory and may require restocking.
        </p>

        <ul>
            <li><strong>ID:</strong> ' . $data['book_id'] . '</li>
            <li><strong>Product:</strong> ' . $data['title'] . '</li>
            <li><strong>Old Stock:</strong> ' . (int) $data['old_stock'] . '</li>
            <li><strong>New Stock:</strong> ' . (int) $data['new_stock'] . '</li>
            <li><strong>Low Stock Threshold:</strong> ' . (int) $data['threshold'] . '</li>
        </ul>

        <p>
            Please review this item in the admin dashboard and restock if necessary.
        </p>
    ';
}

function get_out_of_stock_email_bd($data) {
    return '
        <p>
            <strong>🚨 Out of Stock Alert.</strong>
        </p>

        <p>
            The following product is out of stock and may require restocking.
        </p>

        <ul>
            <li><strong>ID:</strong> ' . $data['book_id'] . '</li>
            <li><strong>Product:</strong> ' . $data['title'] . '</li>
        </ul>

        <p>
            Please review this item in the admin dashboard and restock if necessary.
        </p>
    ';
}

function get_back_in_stock_email_bd($data) {
    return '
        <p>
            <strong>🟨 Back in Stock Alert.</strong>
        </p>

        <p>
            The following product is back in stock and may require restocking.
        </p>

        <ul>
            <li><strong>ID:</strong> ' . $data['book_id'] . '</li>
            <li><strong>Product:</strong> ' . $data['title'] . '</li>
            <li><strong>Old Stock:</strong> ' . $data['old_stock'] . '</li>
            <li><strong>New Stock:</strong> ' . $data['new_stock'] . '</li>
        </ul>

        <p>
            Please review this item in the admin dashboard and restock if necessary.
        </p>
    ';
}

function get_update_order_email_bd($data) {

    $order = $data['order_data'];

    $body = <<<EOT
        <div style="font-family: Arial, Helvetica, sans-serif; color:#333; line-height:1.6; max-width:600px; margin:0 auto;">

        <h2 style="background:#2563eb; color:#fff; padding:12px 16px; border-radius:6px;">
            🟦 Order {$order['order_code']} Updated
        </h2>

        <p>
            This is to inform you that your order has been updated.
            If you did not request this change, please contact our support team immediately at
            <a href="mailto:support@booknest.com">support@booknest.com</a>.
        </p>

        <h3 style="border-bottom:1px solid #ddd; padding-bottom:6px;">Order Details</h3>
        <table width="100%" cellpadding="6" cellspacing="0">
            <tr><td><strong>ID</strong></td><td>#{$order['id']}</td></tr>
            <tr><td><strong>Code</strong></td><td>{$order['order_code']}</td></tr>
            <tr><td><strong>Customer</strong></td><td>{$order['customer_name']}</td></tr>
            <tr><td><strong>Status</strong></td><td>{$order['status']}</td></tr>
            <tr><td><strong>Total Price</strong></td><td>\${$order['total_price']}</td></tr>
            <tr><td><strong>Created At</strong></td><td>{$order['date_added']}</td></tr>
        </table>

        <h3 style="border-bottom:1px solid #ddd; padding-bottom:6px; margin-top:24px;">Shipping Address</h3>
        <table width="100%" cellpadding="6" cellspacing="0">
            <tr><td><strong>Name</strong></td><td>{$order['first_name']} {$order['last_name']}</td></tr>
            <tr><td><strong>Email</strong></td><td>{$order['email']}</td></tr>
            <tr><td><strong>Phone</strong></td><td>{$order['phone_number']}</td></tr>
            <tr><td><strong>State</strong></td><td>{$order['state']}</td></tr>
            <tr><td><strong>City</strong></td><td>{$order['city']}</td></tr>
            <tr><td><strong>Address</strong></td><td>{$order['address_line1']} {$order['address_line2']}</td></tr>
            <tr><td><strong>Notes</strong></td><td>{$order['additional_notes']}</td></tr>
        </table>

        <h3 style="border-bottom:1px solid #ddd; padding-bottom:6px; margin-top:24px;">Order Items</h3>
        <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse:collapse;">
            <thead>
                <tr style="background:#f3f4f6;">
                    <th align="left" style="border:1px solid #ddd;">Book</th>
                    <th align="center" style="border:1px solid #ddd;">Qty</th>
                    <th align="right" style="border:1px solid #ddd;">Unit Price</th>
                    <th align="right" style="border:1px solid #ddd;">Total</th>
                </tr>
            </thead>
            <tbody>
        EOT;

    foreach ($data['order_lines'] as $line) {
        $body .= "
            <tr>
                <td style='border:1px solid #ddd;'>{$line['title']}</td>
                <td align='center' style='border:1px solid #ddd;'>{$line['quantity']}</td>
                <td align='right' style='border:1px solid #ddd;'>\${$line['selling_price']}</td>
                <td align='right' style='border:1px solid #ddd;'>\${$line['total_line_price']}</td>
            </tr>
        ";
    }

    $body .= <<<EOT
        </tbody>
    </table>

    <p style="margin-top:24px; font-size:13px; color:#666;">
        — BookNest Team
    </p>

</div>
EOT;

    return $body;
}



?>