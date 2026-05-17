<?php
require_once 'config.php';

// Очищаем сессию
$_SESSION = array();

// Уничтожаем сессию
session_destroy();

// Перенаправляем на главную
header('Location: index.php');
exit;
?>