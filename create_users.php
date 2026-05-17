<?php
require_once 'config.php';

// Очищаем таблицу пользователей
$pdo->exec("TRUNCATE TABLE users");
echo "<h2>Создание пользователей административной панели</h2>";

// Данные для пользователей
$users = [
    [
        'username' => 'admin',
        'password' => 'admin123',
        'role' => 'admin'
    ],
    [
        'username' => 'superadmin',
        'password' => 'super2024',
        'role' => 'admin'
    ],
    [
        'username' => 'manager',
        'password' => 'manager123',
        'role' => 'user'
    ]
];

foreach ($users as $user) {
    $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    
    if ($stmt->execute([$user['username'], $hashed_password, $user['role']])) {
        echo "✓ Пользователь <strong>{$user['username']}</strong> создан (роль: {$user['role']})<br>";
    } else {
        echo "✗ Ошибка при создании {$user['username']}<br>";
    }
}

echo "<hr>";
echo "<h3>Данные для входа:</h3>";
echo "<ul>";
echo "<li><strong>Администратор 1:</strong> логин: admin, пароль: admin123</li>";
echo "<li><strong>Администратор 2:</strong> логин: superadmin, пароль: super2024</li>";
echo "<li><strong>Пользователь:</strong> логин: manager, пароль: manager123</li>";
echo "</ul>";
echo "<br><a href='login.php' style='display: inline-block; padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px;'>Перейти к входу →</a>";
?>