<?php
session_start();
if (!isset($_SESSION['manager'])) {
  header('Location: manage.php');
  exit;
}
