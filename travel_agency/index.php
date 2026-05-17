<?php 
require_once 'config.php'; 
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DreamTravel | Туристическое агентство</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Дополнительные стили для главной страницы */
        .hero {
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1469474968028-56623f02e42e') center/cover;
            opacity: 0.4;
        }

        nav {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 2rem 5%;
            background: rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .logo i {
            margin-right: 10px;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }

        .nav-links a:hover {
            background: rgba(255,255,255,0.2);
        }

        .btn-login {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4a 100%);
            color: #333 !important;
        }

        .btn-admin {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white !important;
        }

        .btn-logout {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
            top: 50%;
            transform: translateY(-50%);
        }

        .hero-content h1 {
            font-size: 4rem;
            margin-bottom: 1rem;
            animation: fadeInUp 1s ease;
        }

        .hero-content p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease 0.2s both;
        }

        .btn-primary {
            display: inline-block;
            padding: 1rem 2.5rem;
            background: linear-gradient(135deg, #ffd700 0%, #ffed4a 100%);
            color: #333;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
            animation: fadeInUp 1s ease 0.4s both;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Секции */
        .section {
            padding: 5rem 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-header h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-header p {
            color: #7f8c8d;
            font-size: 1.1rem;
        }

        .bg-light {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        }

        /* Карточки стран */
        .countries-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .country-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
        }

        .country-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .country-image {
            height: 250px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .rating {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            backdrop-filter: blur(5px);
        }

        .rating i {
            color: #ddd;
            font-size: 0.8rem;
        }

        .rating i.active {
            color: #ffd700;
        }

        .country-info {
            padding: 1.5rem;
        }

        .country-info h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }

        .hotel-details {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
            color: #7f8c8d;
        }

        .hotel-details i {
            margin-right: 5px;
            color: #667eea;
        }

        /* Типы отдыха */
        .vacation-types-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .vacation-card {
            background: white;
            padding: 2rem;
            text-align: center;
            border-radius: 15px;
            transition: all 0.4s;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .vacation-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102,126,234,0.1), transparent);
            transition: left 0.5s;
        }

        .vacation-card:hover::before {
            left: 100%;
        }

        .vacation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .vacation-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            transition: transform 0.3s;
        }

        .vacation-card:hover .vacation-icon {
            transform: rotateY(180deg);
        }

        .vacation-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .vacation-card h3 {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }

        .vacation-card p {
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        /* Преимущества */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            transition: all 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .feature-card i {
            font-size: 3rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
        }

        .feature-card h4 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }

        .feature-card p {
            color: #7f8c8d;
        }

        /* Футер */
        footer {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            margin-top: 3rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .footer-section h4 {
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .footer-section p {
            margin: 0.5rem 0;
            opacity: 0.8;
        }

        .social-links a {
            color: white;
            margin-right: 1rem;
            font-size: 1.5rem;
            transition: all 0.3s;
            display: inline-block;
        }

        .social-links a:hover {
            color: #ffd700;
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            padding: 1rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        /* Адаптивность */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2rem;
            }
            
            .hero-content p {
                font-size: 1rem;
            }
            
            .nav-links {
                display: none;
            }
            
            .countries-grid, .vacation-types-grid, .features-grid {
                grid-template-columns: 1fr;
            }
            
            .section-header h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Hero секция -->
    <div class="hero">
        <div class="hero-overlay"></div>
        <nav>
            <div class="logo">
                <i class="fas fa-plane-departure"></i>
                <span>DreamTravel</span>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Главная</a></li>
                <li><a href="tours.php">Туры</a></li>
                <li><a href="services.php">Услуги</a></li>
                <?php if(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <li><a href="admin.php" class="btn-admin"><i class="fas fa-shield-alt"></i> Админ панель</a></li>
                    <li><a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Выйти</a></li>
                <?php elseif(isset($_SESSION['user_id'])): ?>
                    <li><a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Выйти</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="btn-login"><i class="fas fa-user-shield"></i> Вход</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        
        <div class="hero-content">
            <h1>Путешествуй с комфортом</h1>
            <p>Лучшие туры по всему миру. Индивидуальный подход. Доступные цены.</p>
            <a href="tours.php" class="btn-primary">Подобрать тур <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>

    <main>
        <!-- Страны -->
        <section class="section">
            <div class="container">
                <div class="section-header">
                    <h2>Популярные направления</h2>
                    <p>Выберите страну для незабываемого отдыха</p>
                </div>
                <div class="countries-grid">
                    <?php
                    try {
                        $stmt = $pdo->query("SELECT * FROM countries ORDER BY stars DESC");
                        if($stmt->rowCount() > 0) {
                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                <div class="country-card">
                                    <div class="country-image" style="background-image: url('turkey.jpg')">
                                        <div class="rating">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star <?= $i <= ($row['stars'] ?? 0) ? 'active' : '' ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <div class="country-info">
                                        <h3><?= htmlspecialchars($row['name']) ?></h3>
                                        <div class="hotel-details">
                                            <p><i class="fas fa-hotel"></i> <?= htmlspecialchars($row['hotel'] ?? 'Не указан') ?></p>
                                            <p><i class="fas fa-utensils"></i> <?= htmlspecialchars($row['meal_type'] ?? 'Не указано') ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile;
                        } else {
                            echo '<div style="text-align: center; grid-column: 1/-1; padding: 3rem;"><i class="fas fa-info-circle" style="font-size: 3rem; color: #667eea;"></i><p style="margin-top: 1rem;">Нет добавленных стран. Добавьте их через админ-панель.</p></div>';
                        }
                    } catch(PDOException $e) {
                        echo '<div style="text-align: center; grid-column: 1/-1; padding: 3rem;"><i class="fas fa-database" style="font-size: 3rem; color: #e74c3c;"></i><p style="margin-top: 1rem;">Ошибка загрузки данных</p></div>';
                    }
                    ?>
                </div>
            </div>
        </section>

        <!-- Типы отдыха -->
        <section class="section bg-light">
            <div class="container">
                <div class="section-header">
                    <h2>Виды отдыха</h2>
                    <p>Выберите идеальный вариант для вашего отпуска</p>
                </div>
                <div class="vacation-types-grid">
                    <?php
                    try {
                        $stmt = $pdo->query("SELECT * FROM vacation_types");
                        $icons = ['fa-umbrella-beach', 'fa-landmark', 'fa-mountain', 'fa-spa', 'fa-heart', 'fa-child'];
                        $i = 0;
                        if($stmt->rowCount() > 0) {
                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                <div class="vacation-card">
                                    <div class="vacation-icon">
                                        <i class="fas <?= $icons[$i % count($icons)] ?>"></i>
                                    </div>
                                    <h3><?= htmlspecialchars($row['type_name']) ?></h3>
                                    <p><?= htmlspecialchars($row['sub_features']) ?></p>
                                </div>
                            <?php $i++; endwhile;
                        } else {
                            echo '<div style="text-align: center; grid-column: 1/-1; padding: 3rem;"><i class="fas fa-info-circle" style="font-size: 3rem; color: #667eea;"></i><p style="margin-top: 1rem;">Нет добавленных типов отдыха.</p></div>';
                        }
                    } catch(PDOException $e) {
                        echo '<div style="text-align: center; grid-column: 1/-1; padding: 3rem;"><i class="fas fa-database" style="font-size: 3rem; color: #e74c3c;"></i><p style="margin-top: 1rem;">Ошибка загрузки данных</p></div>';
                    }
                    ?>
                </div>
            </div>
        </section>

        <!-- Преимущества -->
        <section class="section">
            <div class="container">
                <div class="section-header">
                    <h2>Почему выбирают нас</h2>
                    <p>Мы заботимся о каждом клиенте</p>
                </div>
                <div class="features-grid">
                    <div class="feature-card">
                        <i class="fas fa-shield-alt"></i>
                        <h4>Надёжность</h4>
                        <p>Работаем с проверенными отелями и авиакомпаниями</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-clock"></i>
                        <h4>24/7 Поддержка</h4>
                        <p>Поможем в любое время суток</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-tags"></i>
                        <h4>Лучшие цены</h4>
                        <p>Гарантия низкой стоимости туров</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-passport"></i>
                        <h4>Помощь с визой</h4>
                        <p>Быстрое оформление документов</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h4><i class="fas fa-plane-departure"></i> DreamTravel</h4>
                <p>Ваш идеальный отдых начинается здесь</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-telegram"></i></a>
                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            <div class="footer-section">
                <h4>Контакты</h4>
                <p><i class="fas fa-phone"></i> +7 (999) 123-45-67</p>
                <p><i class="fas fa-envelope"></i> info@dreamtravel.ru</p>
                <p><i class="fas fa-map-marker-alt"></i> г. Москва, ул. Тверская, 15</p>
            </div>
            <div class="footer-section">
                <h4>Режим работы</h4>
                <p><i class="fas fa-clock"></i> Пн-Пт: 10:00 - 20:00</p>
                <p><i class="fas fa-clock"></i> Сб-Вс: 11:00 - 18:00</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 DreamTravel. Все права защищены.</p>
        </div>
    </footer>
</body>
</html>