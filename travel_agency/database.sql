CREATE DATABASE travel_agency;
USE travel_agency;

-- Таблица стран
CREATE TABLE countries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    hotel VARCHAR(100),
    meal_type VARCHAR(100),
    stars INT
);

-- Таблица типов отдыха
CREATE TABLE vacation_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_name VARCHAR(100) NOT NULL,
    sub_features TEXT
);

-- Таблица туров
CREATE TABLE tours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    type ENUM('hot', 'early', 'individual', 'group') NOT NULL,
    discount INT,
    departure_date DATE,
    country_id INT,
    vacation_type_id INT,
    price DECIMAL(10,2),
    description TEXT,
    FOREIGN KEY (country_id) REFERENCES countries(id),
    FOREIGN KEY (vacation_type_id) REFERENCES vacation_types(id)
);

-- Таблица услуг
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2),
    description TEXT
);

-- Таблица пользователей (админ)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user'
);

-- Вставка демо-админа (пароль admin123)
INSERT INTO users (username, password, role) VALUES ('admin', '$2y$10$YourHashHere', 'admin');

-- Вставка начальных данных по вашей структуре
INSERT INTO countries (name, hotel, meal_type, stars) VALUES
('Турция', 'Sunny Hotel', 'All Inclusive', 5),
('Египет', 'Red Sea Resort', 'Ultra All Inclusive', 4),
('Таиланд', 'Phuket Paradise', 'Breakfast', 5),
('Греция', 'Aegean Star', 'Half Board', 4),
('Италия', 'Roman Holiday', 'Bed & Breakfast', 4),
('ОАЭ', 'Desert Palace', 'Full Board', 5);

INSERT INTO vacation_types (type_name, sub_features) VALUES
('пляжный', 'море, песок'),
('экскурсионный', 'достопримечательности, трансфер'),
('Горнолыжный', 'курорт, уровень трасс'),
('лечебный', 'санаторий, процедуры');

INSERT INTO services (service_name, price, description) VALUES
('трансфер', 50.00, 'Встреча в аэропорту'),
('страховка', 25.00, 'Медицинская страховка'),
('виза', 80.00, 'Оформление визы'),
('экскурсии', 120.00, 'Групповые экскурсии'),
('аренда авто', 200.00, 'Аренда автомобиля'),
('отели', 0.00, 'Бронирование отелей'),
('перелеты', 0.00, 'Авиабилеты');