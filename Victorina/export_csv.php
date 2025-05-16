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

/// Устанавливаем заголовки для скачивания файла
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="win_numbers.csv"');

// Открываем "файл" для записи в стандартный вывод
$output = fopen('php://output', 'w');

// Пишем BOM для корректного отображения кириллицы в Excel
fputs($output, "\xEF\xBB\xBF");

// Пишем заголовки столбцов
fputcsv($output, array('Номер билета', 'Выигрыш'));

// Получаем данные из таблицы win_numbers
$result = $mysqli->query("SELECT number, prize FROM win_numbers");

// Пишем данные в CSV-файл
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

// Закрываем "файл"
fclose($output);
exit();
