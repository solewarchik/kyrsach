<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Услуги - DreamTravel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar-inner">
        <div class="logo">
            <i class="fas fa-plane-departure"></i>
            <span>DreamTravel</span>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Главная</a></li>
            <li><a href="tours.php">Туры</a></li>
            <li><a href="services.php" class="active">Услуги</a></li>
        </ul>
    </nav>

    <div class="page-header">
        <h1>Наши услуги</h1>
        <p>Полный спектр услуг для комфортного путешествия</p>
    </div>

    <main class="container">
        <div class="services-grid">
            <?php
            $stmt = $pdo->query("SELECT * FROM services ORDER BY price DESC");
            $icons = [
                'fa-car', 'fa-shield-heart', 'fa-passport', 'fa-camera', 
                'fa-car-side', 'fa-hotel', 'fa-plane', 'fa-umbrella', 
                'fa-crown', 'fa-chalkboard-user'
            ];
            $colors = ['#3498db', '#e74c3c', '#2ecc71', '#f39c12', '#9b59b6', '#1abc9c', '#e67e22', '#16a085', '#c0392b', '#2980b9'];
            $i = 0;
            
            if($stmt->rowCount() > 0):
                while($service = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="service-card" data-service="<?= htmlspecialchars($service['service_name']) ?>" data-price="<?= $service['price'] ?>">
                        <div class="service-icon" style="background: linear-gradient(135deg, <?= $colors[$i % count($colors)] ?> 0%, <?= $colors[$i % count($colors)] ?>cc 100%);">
                            <i class="fas <?= $icons[$i % count($icons)] ?>"></i>
                        </div>
                        <h3><?= htmlspecialchars($service['service_name']) ?></h3>
                        <p><?= htmlspecialchars($service['description']) ?></p>
                        <div class="service-price">
                            <?php if($service['price'] > 0): ?>
                                от <?= number_format($service['price'], 0, '', ' ') ?> ₽
                            <?php else: ?>
                                По запросу
                            <?php endif; ?>
                        </div>
                        <button class="btn-service" onclick="openServiceModal('<?= htmlspecialchars($service['service_name']) ?>', <?= $service['price'] ?>)">
                            <i class="fas fa-shopping-cart"></i> Заказать
                        </button>
                    </div>
                <?php $i++; endwhile;
            else: ?>
                <div style="text-align: center; grid-column: 1/-1; padding: 3rem;">
                    <i class="fas fa-info-circle" style="font-size: 3rem; color: #667eea;"></i>
                    <p style="margin-top: 1rem; font-size: 1.2rem;">Услуги временно отсутствуют. Добавьте их через админ-панель.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Модальное окно заказа услуги -->
    <div id="serviceModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fas fa-concierge-bell"></i>
                <h2>Заказ услуги</h2>
                <p>Оставьте заявку, и мы свяжемся с вами</p>
                <span class="close-service">&times;</span>
            </div>
            <div class="modal-body">
                <form id="serviceForm" class="booking-form" onsubmit="submitServiceRequest(event)">
                    <input type="hidden" id="serviceNameInput">
                    
                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> Выбранная услуга</label>
                        <input type="text" id="selectedService" readonly style="background: #f8f9fa; font-weight: 600; color: #667eea;">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-user"></i> Ваше имя *</label>
                            <input type="text" id="serviceName" required placeholder="Иван Иванов">
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-phone"></i> Телефон *</label>
                            <input type="tel" id="servicePhone" required placeholder="+7 (999) 123-45-67">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" id="serviceEmail" placeholder="ivan@example.com">
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt"></i> Желаемая дата</label>
                        <input type="date" id="serviceDate">
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-comment"></i> Дополнительная информация</label>
                        <textarea id="serviceComments" placeholder="Расскажите подробнее о ваших пожеланиях..."></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> Отправить заявку
                    </button>
                </form>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h4><i class="fas fa-plane-departure"></i> DreamTravel</h4>
                <p>Ваш идеальный отдых начинается здесь</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-telegram"></i></a>
                </div>
            </div>
            <div class="footer-section">
                <h4>Контакты</h4>
                <p><i class="fas fa-phone"></i> +7 (999) 123-45-67</p>
                <p><i class="fas fa-envelope"></i> info@dreamtravel.ru</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 DreamTravel. Все права защищены.</p>
        </div>
    </footer>

    <script>
        function openServiceModal(serviceName, price) {
            document.getElementById('selectedService').value = serviceName + (price > 0 ? ' (' + price.toLocaleString('ru-RU') + ' ₽)' : '');
            document.getElementById('serviceNameInput').value = serviceName;
            document.getElementById('serviceModal').style.display = 'block';
        }
        
        function submitServiceRequest(event) {
            event.preventDefault();
            const name = document.getElementById('serviceName').value;
            const phone = document.getElementById('servicePhone').value;
            const service = document.getElementById('serviceNameInput').value;
            
            if(!name || !phone) {
                alert('Пожалуйста, заполните обязательные поля (Имя и Телефон)');
                return;
            }
            
            const modal = document.getElementById('serviceModal');
            const modalBody = modal.querySelector('.modal-body');
            
            modalBody.innerHTML = `
                <div class="success-animation">
                    <i class="fas fa-check-circle"></i>
                    <h2>Заявка отправлена!</h2>
                    <p>Спасибо, ${name}!</p>
                    <p style="color: #27ae60;">Услуга: "${service}"</p>
                    <p>Наш менеджер свяжется с вами для уточнения деталей.</p>
                    <p style="margin-top: 1rem; color: #7f8c8d;">Номер заявки: S-${Math.floor(Math.random() * 10000)}</p>
                    <button class="submit-btn" onclick="location.reload()" style="margin-top: 1rem;">
                        <i class="fas fa-check"></i> Закрыть
                    </button>
                </div>
            `;
        }
        
        document.querySelector('.close-service').onclick = () => {
            document.getElementById('serviceModal').style.display = 'none';
            location.reload();
        };
        
        window.onclick = (e) => {
            if(e.target == document.getElementById('serviceModal')) {
                document.getElementById('serviceModal').style.display = 'none';
                location.reload();
            }
        };
    </script>
</body>
</html>