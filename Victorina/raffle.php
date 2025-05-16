<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'db_config.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prizeId = (int)$_POST['prize_id'];
    $quantity = (int)$_POST['quantity'];

    // Получаем данные о призе
    $prizeResult = $mysqli->query("SELECT prize_name, quantity FROM prizes WHERE id = $prizeId");
    if ($prizeResult->num_rows == 0) {
        die('Подарок не найден.');
    }
    $prize = $prizeResult->fetch_assoc();
    $prizeName = $prize['prize_name'];
    $currentQuantity = $prize['quantity'];

    // Выбор случайных номеров
    $numberResult = $mysqli->query("SELECT number FROM numbers ORDER BY RAND() LIMIT $quantity");
    if ($numberResult->num_rows < $quantity) {
        die('Недостаточно номеров для розыгрыша.');
    }

    $winningNumbers = [];
    while ($row = $numberResult->fetch_assoc()) {
        $winningNumbers[] = $row['number'];
    }

    // Выводим номера победителей
    echo "<h3>$prizeName</h3>";
    echo "<ul>";
    foreach ($winningNumbers as $number) {
        echo "<li>$number</li>";

        // Записываем в win_numbers
        $mysqli->query("INSERT INTO win_numbers (number, prize) VALUES ('$number', '$prizeName')");

        // Удаляем из numbers
        $mysqli->query("DELETE FROM numbers WHERE number = '$number'");
    }
    echo "</ul>";

    // Обновляем количество призов
    $newQuantity = $currentQuantity - $quantity;
    if ($newQuantity <= 0) {
        // Удаляем приз из таблицы, если количество стало 0 или меньше
        $mysqli->query("DELETE FROM prizes WHERE id = $prizeId");
    } else {
        // Обновляем количество оставшихся призов
        $mysqli->query("UPDATE prizes SET quantity = $newQuantity WHERE id = $prizeId");
    }
}