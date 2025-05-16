<?php
session_start();
require 'db.php';

if ($_POST['username'] && $_POST['password']) {
  $stmt = $pdo->prepare("SELECT * FROM managers WHERE username = ?");
  $stmt->execute([$_POST['username']]);
  $user = $stmt->fetch();

  if ($user && $_POST['password'] === $user['password']) {
    $_SESSION['manager'] = $user['username'];
    header('Location: dashboard.php');
    exit;
  }
}

echo "Неверный логин или пароль";
