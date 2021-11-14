<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System with PHP</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

    <header>
        <a href="/" class="header-logo">Login System With PHP</a>
        <ul>
            <?php if(isset($_SESSION['auth']) && $_SESSION['auth']['state'] == true): ?>
                <li><a href="/logout.php">Log out</a></li>
            <?php else:?>
            <li><a href="/login.php">Login</a></li>
            <li><a href="/register.php">Register</a></li>
            <?php endif?>
        </ul>
    </header>
    