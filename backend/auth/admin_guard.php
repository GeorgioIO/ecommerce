<?php

require_once __DIR__ . '/../../configuration/session.php';

if(!isset($_SESSION['admin_id'])){
    header('Location: /ecommerce/frontend/admin/admin_login.php');
    exit;
}

?>