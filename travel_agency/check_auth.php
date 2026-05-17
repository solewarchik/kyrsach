<?php
require_once 'config.php';

echo "<h2>Проверка авторизации</h2>";

echo "<h3>Данные сессии:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h3>Пользователи в БД:</h3>";
$users = $pdo->query("SELECT id, username, role FROM users")->fetchAll();
foreach($users as $user) {
    echo "- {$user['username']} (роль: {$user['role']})<br>";
}

echo "<h3>Проверка статуса:</h3>";
if(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    echo "<p style='color:green'>✓ Вы авторизованы как администратор</p>";
    echo "<a href='admin/index.php'>Перейти в админ-панель</a>";
} else {
    echo "<p style='color:red'>✗ Вы НЕ авторизованы как администратор</p>";
    echo "<a href='login.php'>Войти как администратор</a>";
}
?>