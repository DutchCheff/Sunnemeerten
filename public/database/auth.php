<?php
// /includes/auth.php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=login');
    exit;
}