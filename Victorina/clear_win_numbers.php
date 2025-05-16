<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}
// Подключение к базе данных
require 'db_config.php'; // Подключение к базе данных

// Очистка таблицы win_numbers
$mysqli->query("TRUNCATE TABLE win_numbers");

// Перенаправление обратно на страницу с таблицей
header("Location: winners.php");
exit;
