<?php
require_once 'config.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Исправление пароля администратора</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f0f2f5; }
        .success { background: #d4edda; color: #155724; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .info { background: #e8f0fe; padding: 15px; margin: 10px 0; border-radius: 5px; }
        code { background: #f4f4f4; padding: 2px 5px; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>🔧 Исправление пароля администратора</h1>";

// Проверяем существование таблицы
$table_check = $pdo->query("SHOW TABLES LIKE 'users'");
if($table_check->rowCount() == 0) {
    echo "<div class='error'>✗ Таблица users не существует! Создаём...</div>";
    
    // Создаём таблицу users
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'user') DEFAULT 'user'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    echo "<div class='success'>✓ Таблица users создана</div>";
}

// Очищаем таблицу и создаём новых пользователей
echo "<h2>Пересоздание пользователей</h2>";

// Удаляем всех старых пользователей
$pdo->exec("TRUNCATE TABLE users");
echo "<div class='info'>✓ Старые пользователи удалены</div>";

// Создаём новых пользователей с правильными паролями
$users = [
    ['username' => 'admin', 'password' => 'admin123', 'role' => 'admin'],
    ['username' => 'superadmin', 'password' => 'super2024', 'role' => 'admin'],
    ['username' => 'manager', 'password' => 'manager123', 'role' => 'user']
];

foreach($users as $user) {
    $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    if($stmt->execute([$user['username'], $hashed_password, $user['role']])) {
        echo "<div class='success'>✓ Создан: <strong>{$user['username']}</strong> (пароль: {$user['password']}, роль: {$user['role']})</div>";
    } else {
        echo "<div class='error'>✗ Ошибка при создании {$user['username']}</div>";
    }
}

// Проверяем, что пользователи создались правильно
echo "<h2>Проверка паролей</h2>";

$test_users = [
    ['username' => 'admin', 'password' => 'admin123'],
    ['username' => 'superadmin', 'password' => 'super2024'],
    ['username' => 'manager', 'password' => 'manager123']
];

foreach($test_users as $test) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$test['username']]);
    $user = $stmt->fetch();
    
    if($user) {
        if(password_verify($test['password'], $user['password'])) {
            echo "<div class='success'>✓ {$test['username']} - пароль верный</div>";
        } else {
            echo "<div class='error'>✗ {$test['username']} - пароль НЕ верный</div>";
            // Показываем хеш для отладки
            echo "<div class='info'>Хеш в БД: " . substr($user['password'], 0, 30) . "...</div>";
        }
    } else {
        echo "<div class='error'>✗ Пользователь {$test['username']} не найден</div>";
    }
}

// Показываем всех пользователей в БД
echo "<h2>Список пользователей в базе данных:</h2>";
$users_list = $pdo->query("SELECT id, username, role FROM users")->fetchAll();
echo "<table border='1' cellpadding='8' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background: #667eea; color: white;'><th>ID</th><th>Логин</th><th>Роль</th></tr>";
foreach($users_list as $u) {
    echo "<tr><td>{$u['id']}</td><td><strong>{$u['username']}</strong></td><td>{$u['role']}</td></tr>";
}
echo "</table>";

echo "<div class='info' style='margin-top: 20px;'>
    <h3>📋 Данные для входа:</h3>
    <p><strong>Администратор:</strong> логин = <code>admin</code>, пароль = <code>admin123</code></p>
    <p><strong>Супер-администратор:</strong> логин = <code>superadmin</code>, пароль = <code>super2024</code></p>
    <p><strong>Пользователь:</strong> логин = <code>manager</code>, пароль = <code>manager123</code></p>
</div>";

echo "<div style='margin-top: 20px;'>
    <a href='login.php' style='display: inline-block; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;'>Перейти к входу →</a>
</div>";

echo "</body></html>";
?>