<?php
// Подключение к базе данных
$mysqli = new mysqli("localhost", "username", "F9&euEa9", "dbname");

// Проверка соединения
if ($mysqli->connect_error) {
    die("Ошибка подключения: " . $mysqli->connect_error);
}
?>
