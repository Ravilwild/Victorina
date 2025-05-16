<?php
require 'auth_check.php';

require 'db.php';

// Убираем пробелы и фильтруем данные
$number      = trim($_POST['number']);
$phone       = trim($_POST['phone']);
$last_name   = trim($_POST['last_name']);
$first_name  = trim($_POST['first_name']);
$middle_name = trim($_POST['middle_name']);
$birthdate   = trim($_POST['birthdate']);

// Проверка форматов и базовая защита от мусора
function sanitize($value) {
  return htmlspecialchars(strip_tags($value));
}

$number      = sanitize($number);
$phone       = sanitize($phone);
$last_name   = sanitize($last_name);
$first_name  = sanitize($first_name);
$middle_name = sanitize($middle_name);
$birthdate   = sanitize($birthdate);

// Проверка: дата в нужном формате
if (!preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $birthdate)) {
  header("Location: dashboard.php?error=invalid_date");
  exit;
}
// Парсим дату из строки
list($day, $month, $year) = explode('.', $birthdate);

// Создаём объект даты рождения
$birthDate = DateTime::createFromFormat('d.m.Y', $birthdate);
$today = new DateTime();

// Если дата некорректна (например, 31.02.2020), $birthDate будет false
if (!$birthDate || $birthDate->format('d.m.Y') !== $birthdate) {
  header("Location: dashboard.php?error_date=invalid_date");
  exit;
}

// Вычисляем разницу в годах
$age = $birthDate->diff($today)->y;

// Проверка возраста
if ($age < 14) {
  header("Location: dashboard.php?error_date=underage");
  exit;
}

// Проверка: всё ли заполнено
if (!$number || !$phone || !$last_name || !$first_name || !$middle_name || !$birthdate) {
  header("Location: dashboard.php?error=missing_fields");
  exit;
}

// Проверяем, есть ли уже такая запись
$check = $pdo->prepare("SELECT COUNT(*) FROM numbers WHERE 
    last_name = ? AND first_name = ? AND middle_name = ? AND birthdate = ?");
$check->execute([$last_name, $first_name, $middle_name, $birthdate]);
$exists = $check->fetchColumn();

if ($exists > 0) {
    header('Location: dashboard.php?error=duplicate');
    exit;
}

// Если не существует — вставляем
$stmt = $pdo->prepare("INSERT INTO numbers 
  (number, phone, last_name, first_name, middle_name, birthdate)
  VALUES (?, ?, ?, ?, ?, ?)");

$stmt->execute([$number, $phone, $last_name, $first_name, $middle_name, $birthdate]);
$manager = $_SESSION['manager'] ?? 'неизвестно';
$logStmt = $pdo->prepare("INSERT INTO log_entries (manager, action) VALUES (?, ?)");
$logStmt->execute([$manager, "Добавил запись: $last_name $first_name $middle_name, $birthdate"]);

header('Location: dashboard.php?success=1');
