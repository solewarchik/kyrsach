<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Туры - DreamTravel</title>
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
            <li><a href="tours.php" class="active">Туры</a></li>
            <li><a href="services.php">Услуги</a></li>
        </ul>
    </nav>

    <div class="page-header">
        <h1>Наши туры</h1>
        <p>Выберите идеальное путешествие из 8 уникальных направлений</p>
    </div>

    <main>
        <div class="tours-filter">
            <button class="filter-btn active" data-filter="all">Все туры</button>
            <button class="filter-btn" data-filter="hot">🔥 Горящие туры</button>
            <button class="filter-btn" data-filter="early">📅 Раннее бронирование</button>
            <button class="filter-btn" data-filter="individual">👤 Индивидуальные</button>
            <button class="filter-btn" data-filter="group">👥 Групповые</button>
        </div>

        <div class="tours-grid">
            <?php
            $stmt = $pdo->query("
                SELECT t.*, c.name as country, c.stars as country_stars 
                FROM tours t 
                JOIN countries c ON t.country_id = c.id 
                ORDER BY t.id DESC
            ");
            
            if($stmt->rowCount() > 0):
                while($tour = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="tour-card" data-type="<?= $tour['type'] ?>" data-tour-id="<?= $tour['id'] ?>" data-tour-title="<?= htmlspecialchars($tour['title']) ?>" data-tour-price="<?= $tour['price'] ?>">
                        <div class="tour-image" style="background-image: url('1.jpg')">
                            <?php if($tour['discount'] > 0): ?>
                                <div class="discount-badge">Скидка -<?= $tour['discount'] ?>%</div>
                            <?php endif; ?>
                            <div class="tour-type-badge">
                                <?php
                                $typeLabels = [
                                    'hot' => '🔥 Горящий тур',
                                    'early' => '📅 Раннее бронирование',
                                    'individual' => '👤 Индивидуальный',
                                    'group' => '👥 Групповой'
                                ];
                                echo $typeLabels[$tour['type']] ?? $tour['type'];
                                ?>
                            </div>
                        </div>
                        <div class="tour-info">
                            <h3><?= htmlspecialchars($tour['title']) ?></h3>
                            <p class="country"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($tour['country']) ?> 
                                <?php for($i = 1; $i <= $tour['country_stars']; $i++): ?>★<?php endfor; ?>
                            </p>
                            <p class="description"><?= htmlspecialchars($tour['description']) ?></p>
                            <div class="tour-details">
                                <span><i class="fas fa-calendar"></i> <?= date('d.m.Y', strtotime($tour['departure_date'])) ?></span>
                                <span class="price"><?= number_format($tour['price'], 0, '', ' ') ?> ₽</span>
                            </div>
                            <button class="btn-book" onclick="openBookingModal(<?= $tour['id'] ?>, '<?= htmlspecialchars($tour['title']) ?>', <?= $tour['price'] ?>)">
                                <i class="fas fa-gem"></i> Забронировать
                            </button>
                        </div>
                    </div>
                <?php endwhile; 
            else: ?>
                <div style="text-align: center; grid-column: 1/-1; padding: 3rem;">
                    <i class="fas fa-info-circle" style="font-size: 3rem; color: #667eea;"></i>
                    <p style="margin-top: 1rem; font-size: 1.2rem;">Туры временно отсутствуют. Добавьте их через админ-панель.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Модальное окно заявки -->
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fas fa-gift"></i>
                <h2>Оформление заявки</h2>
                <p>Заполните форму, и мы свяжемся с вами</p>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <form id="bookingForm" class="booking-form" onsubmit="submitBooking(event)">
                    <input type="hidden" id="tourId" name="tour_id">
                    
                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> Выбранный тур</label>
                        <input type="text" id="tourName" readonly style="background: #f8f9fa; font-weight: 600; color: #667eea;">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-user"></i> Ваше имя *</label>
                            <input type="text" id="customerName" required placeholder="Иван Иванов">
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-phone"></i> Телефон *</label>
                            <input type="tel" id="customerPhone" required placeholder="+7 (999) 123-45-67">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" id="customerEmail" placeholder="ivan@example.com">
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-users"></i> Количество человек *</label>
                            <input type="number" id="persons" min="1" max="20" value="2" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt"></i> Желаемая дата вылета</label>
                        <input type="date" id="preferredDate">
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-comment"></i> Дополнительные пожелания</label>
                        <textarea id="comments" placeholder="Напишите особые пожелания, если есть..."></textarea>
                    </div>
                    
                    <div class="booking-summary">
                        <span><i class="fas fa-ruble-sign"></i> Стоимость тура:</span>
                        <span id="totalPrice">0 ₽</span>
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
        let currentTourPrice = 0;
        
        function openBookingModal(tourId, tourTitle, price) {
            currentTourPrice = price;
            document.getElementById('tourId').value = tourId;
            document.getElementById('tourName').value = tourTitle;
            document.getElementById('totalPrice').innerHTML = price.toLocaleString('ru-RU') + ' ₽';
            document.getElementById('bookingModal').style.display = 'block';
            
            document.getElementById('persons').onchange = function() {
                const persons = parseInt(this.value) || 1;
                const total = currentTourPrice * persons;
                document.getElementById('totalPrice').innerHTML = total.toLocaleString('ru-RU') + ' ₽';
            };
        }
        
        function submitBooking(event) {
            event.preventDefault();
            
            const name = document.getElementById('customerName').value;
            const phone = document.getElementById('customerPhone').value;
            const persons = document.getElementById('persons').value;
            const tourName = document.getElementById('tourName').value;
            
            if(!name || !phone) {
                alert('Пожалуйста, заполните обязательные поля (Имя и Телефон)');
                return;
            }
            
            const modal = document.getElementById('bookingModal');
            const modalBody = modal.querySelector('.modal-body');
            
            modalBody.innerHTML = `
                <div class="success-animation">
                    <i class="fas fa-check-circle"></i>
                    <h2>Заявка отправлена!</h2>
                    <p>Спасибо, ${name}!</p>
                    <p style="color: #27ae60;">Тур "${tourName}" на ${persons} чел.</p>
                    <p>Наш менеджер свяжется с вами в ближайшее время.</p>
                    <p style="margin-top: 1rem; color: #7f8c8d;">Номер заявки: T-${Math.floor(Math.random() * 10000)}</p>
                    <button class="submit-btn" onclick="location.reload()" style="margin-top: 1rem;">
                        <i class="fas fa-check"></i> Закрыть
                    </button>
                </div>
            `;
        }
        
        // Фильтрация туров
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const filter = this.dataset.filter;
                document.querySelectorAll('.tour-card').forEach(card => {
                    if(filter === 'all' || card.dataset.type === filter) {
                        card.style.display = 'block';
                        card.style.animation = 'fadeInUp 0.5s ease';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
        
        // Закрытие модального окна
        document.querySelector('.close').onclick = () => {
            document.getElementById('bookingModal').style.display = 'none';
            location.reload();
        };
        
        window.onclick = (e) => {
            if(e.target == document.getElementById('bookingModal')) {
                document.getElementById('bookingModal').style.display = 'none';
                location.reload();
            }
        };
    </script>
</body>
</html>