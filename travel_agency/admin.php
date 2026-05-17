<?php
require_once 'config.php';

// Проверка авторизации
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Получаем статистику
$stats = [];
$stats['countries'] = $pdo->query("SELECT COUNT(*) FROM countries")->fetchColumn();
$stats['tours'] = $pdo->query("SELECT COUNT(*) FROM tours")->fetchColumn();
$stats['services'] = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
$stats['users'] = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// Получаем последние туры
$recent_tours = $pdo->query("SELECT t.*, c.name as country FROM tours t JOIN countries c ON t.country_id = c.id ORDER BY t.id DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель | DreamTravel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Montserrat', sans-serif;
            background: #f0f2f5;
        }
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100%;
            background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);
            color: white;
            padding-top: 30px;
        }
        .sidebar-header {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        .sidebar-header i {
            font-size: 3rem;
            color: #ffd700;
        }
        .sidebar-header h3 {
            margin-top: 10px;
        }
        .menu-item {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
        }
        .menu-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        .menu-item i {
            width: 30px;
            margin-right: 10px;
        }
        .main-content {
            margin-left: 280px;
            padding: 20px;
        }
        .top-bar {
            background: white;
            border-radius: 15px;
            padding: 20px 30px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-info h3 {
            font-size: 0.85rem;
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        .stat-info .number {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
        }
        .stat-icon i {
            font-size: 2.5rem;
            color: #667eea;
        }
        .section-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .section-title {
            font-size: 1.2rem;
            margin-bottom: 20px;
            border-left: 3px solid #667eea;
            padding-left: 15px;
        }
        .logout-btn {
            padding: 8px 20px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }
        .logout-btn:hover {
            background: #c0392b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        th {
            background: #f8f9fa;
            font-weight: 600;
        }
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-hot {
            background: #e74c3c;
            color: white;
        }
        @media (max-width: 768px) {
            .sidebar {
                left: -280px;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-plane-departure"></i>
            <h3>DreamTravel</h3>
            <p style="font-size: 0.8rem; opacity: 0.7;">Админ-панель</p>
        </div>
        <a href="admin.php" class="menu-item">
            <i class="fas fa-tachometer-alt"></i> Главная
        </a>
        <a href="admincountries.php" class="menu-item">
            <i class="fas fa-globe"></i> Страны
        </a>
        <a href="admintours.php" class="menu-item">
            <i class="fas fa-suitcase"></i> Туры
        </a>
        <a href="adminservices.php" class="menu-item">
            <i class="fas fa-concierge-bell"></i> Услуги
        </a>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <div>
                <h1>Добро пожаловать, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
                <p>Панель управления туристическим агентством</p>
            </div>
            <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Выход</a>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Страны</h3>
                    <div class="number"><?= $stats['countries'] ?></div>
                </div>
                <div class="stat-icon"><i class="fas fa-globe"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Туры</h3>
                    <div class="number"><?= $stats['tours'] ?></div>
                </div>
                <div class="stat-icon"><i class="fas fa-suitcase"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Услуги</h3>
                    <div class="number"><?= $stats['services'] ?></div>
                </div>
                <div class="stat-icon"><i class="fas fa-concierge-bell"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Пользователи</h3>
                    <div class="number"><?= $stats['users'] ?></div>
                </div>
                <div class="stat-icon"><i class="fas fa-users"></i></div>
            </div>
        </div>

        <div class="section-card">
            <div class="section-title">Последние туры</div>
            <table>
                <thead>
                    <tr><th>Название</th><th>Страна</th><th>Тип</th><th>Цена</th></tr>
                </thead>
                <tbody>
                    <?php foreach($recent_tours as $tour): ?>
                    <tr>
                        <td><?= htmlspecialchars($tour['title']) ?></td>
                        <td><?= htmlspecialchars($tour['country']) ?></td>
                        <td><span class="badge badge-hot"><?= $tour['type'] ?></span></td>
                        <td><?= number_format($tour['price'], 0, '', ' ') ?> ₽</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>