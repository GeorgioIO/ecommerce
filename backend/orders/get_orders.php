<?php

require_once __DIR__ . '/../../configuration/session.php';
require_once __DIR__ . '/../helpers.php';

header("Content-Type: application/json");

$isAdmin = isset($_SESSION['admin_id']);
$isUser = isset($_SESSION['user_id']);

// Not admin or user
if (!$isAdmin && !$isUser ) {
    respond(false , 401 , null , null , 'Not authorized to use api');
}

if($_SERVER['REQUEST_METHOD'] === 'GET')
{
    require_once __DIR__ . '/../../configuration/database.php';
    require_once __DIR__ . '/helpers/order_db_helpers.php';

    $hasPagination =  isset($_GET['page']) && isset($_GET['perPage']);
    $role = $isAdmin ? "admin" : ($isUser ? "user" : "");
    $params = [];
    $types = "";

    $query = get_select_orders_query($role);

    if($isUser)
    {
        $types .= "i";
        $params[] = $_SESSION['user_id'];
    }

    if($hasPagination)
    {
        $page = $_GET['page'] ?? 1;
        $perPage = $_GET['perPage'] ?? 10;

        $page = max(1 , (int) $page);
        $perPage = min(50 , max(5 , (int) $perPage));
        $offset = ($page - 1) * $perPage;

        $params[] = $perPage;
        $params[] = $offset;
        $types .= "ii";
        $query .= " LIMIT ? OFFSET ?;";
    }

    $stmt = $conn->prepare($query);
    if (strlen($types) > 0) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];

    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            $orders[] = $row;
        }
    }

    if ($isAdmin) {
        $count_query = "SELECT COUNT(*) AS total_orders FROM orders";
        $count_stmt = $conn->prepare($count_query);
    } else {
        $count_query = "SELECT COUNT(*) AS total_orders FROM orders WHERE user_id = ?";
        $count_stmt = $conn->prepare($count_query);
        $count_stmt->bind_param("i", $_SESSION['user_id']);
    }

    $count_stmt->execute();
    $countResult = $count_stmt->get_result();
    $total_orders = $countResult->fetch_assoc()['total_orders'];

    $stmt->close();
    $count_stmt->close();
    $conn->close();

    $pagination = $hasPagination ? [
        'page' => $page,
        'perPage' => $perPage,
        'total' => $total_orders,
        'totalPages' => ceil($total_orders / $perPage)
    ] : null;


    respond(true , 200 , $orders , $pagination , null);
}
else
{
    respond(false , 400 , null , null , 'Wrong method used in getting orders');
}

?>