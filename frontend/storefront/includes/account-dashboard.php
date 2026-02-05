<?php

require_once __DIR__ . '../../../../configuration/session.php';

?>

<main>
    <h1>Im a <?= $_SESSION['user_name'] ?></h1>
</main>