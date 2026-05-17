<?php
require_once 'config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Добавление страны
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $stmt = $pdo->prepare("INSERT INTO countries (name, hotel, meal_type, stars, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['name'], 
        $_POST['hotel'], 
        $_POST['meal_type'], 
        $_POST['stars'], 
        $_POST['image'] ?? null
    ]);
    header('Location: admincountries.php');
    exit;
}

// Удаление страны
if(isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM countries WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: admincountries.php');
    exit;
}

// Редактирование страны
if(isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM countries WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_country = $stmt->fetch();
}

// Обновление страны
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $stmt = $pdo->prepare("UPDATE countries SET name=?, hotel=?, meal_type=?, stars=?, image=? WHERE id=?");
    $stmt->execute([
        $_POST['name'], 
        $_POST['hotel'], 
        $_POST['meal_type'], 
        $_POST['stars'], 
        $_POST['image'] ?? null,
        $_POST['id']
    ]);
    header('Location: admincountries.php');
    exit;
}

$countries = $pdo->query("SELECT * FROM countries ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление странами | Админ-панель</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Montserrat', sans-serif; background: #f0f2f5; }
        .container { max-width: 1400px; margin: 0 auto; padding: 20px; }
        h1 { margin-bottom: 20px; color: #2c3e50; }
        .form-card, .table-card { background: white; border-radius: 15px; padding: 25px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-family: 'Montserrat', sans-serif; }
        button { padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; font-family: 'Montserrat', sans-serif; }
        button:hover { background: #5a67d8; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; font-weight: 600; }
        .btn-delete { background: #e74c3c; color: white; padding: 5px 10px; text-decoration: none; border-radius: 5px; font-size: 12px; margin-right: 5px; }
        .btn-edit { background: #f39c12; color: white; padding: 5px 10px; text-decoration: none; border-radius: 5px; font-size: 12px; }
        .btn-back { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #2c3e50; color: white; text-decoration: none; border-radius: 5px; }
        img { width: 50px; height: 50px; object-fit: cover; border-radius: 5px; }
        .stars { color: #f39c12; }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-globe"></i> Управление странами</h1>
        
        <?php if(isset($edit_country)): ?>
        <div class="form-card">
            <h3><i class="fas fa-edit"></i> Редактировать страну</h3>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $edit_country['id'] ?>">
                <div class="form-group">
                    <label>Название страны</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($edit_country['name']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Отель</label>
                    <input type="text" name="hotel" value="<?= htmlspecialchars($edit_country['hotel'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Питание</label>
                    <input type="text" name="meal_type" value="<?= htmlspecialchars($edit_country['meal_type'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Звезды (1-5)</label>
                    <input type="number" name="stars" min="1" max="5" value="<?= $edit_country['stars'] ?? 5 ?>">
                </div>
                <div class="form-group">
                    <label>URL изображения</label>
                    <input type="text" name="image" value="<?= htmlspecialchars($edit_country['image'] ?? '') ?>" placeholder="https://images.unsplash.com/...">
                </div>
                <button type="submit" name="update"><i class="fas fa-save"></i> Сохранить</button>
                <a href="admincountries.php" style="margin-left: 10px; padding: 10px 20px; background: #95a5a6; color: white; text-decoration: none; border-radius: 5px;">Отмена</a>
            </form>
        </div>
        <?php else: ?>
        <div class="form-card">
            <h3><i class="fas fa-plus"></i> Добавить новую страну</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Название страны</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Отель</label>
                    <input type="text" name="hotel">
                </div>
                <div class="form-group">
                    <label>Питание</label>
                    <input type="text" name="meal_type">
                </div>
                <div class="form-group">
                    <label>Звезды (1-5)</label>
                    <input type="number" name="stars" min="1" max="5" value="5">
                </div>
                <div class="form-group">
                    <label>URL изображения</label>
                    <input type="text" name="image" placeholder="https://images.unsplash.com/...">
                </div>
                <button type="submit" name="add"><i class="fas fa-plus"></i> Добавить</button>
            </form>
        </div>
        <?php endif; ?>

        <div class="table-card">
            <h3><i class="fas fa-list"></i> Список стран</h3>
            <table>
                <thead>
                    <tr><th>ID</th><th>Изображение</th><th>Страна</th><th>Отель</th><th>Питание</th><th>Звезды</th><th>Действия</th></tr>
                </thead>
                <tbody>
                    <?php if(count($countries) > 0): ?>
                        <?php foreach($countries as $c): ?>
                        <tr>
                            <td><?= $c['id'] ?></td>
                            <td><?php if(!empty($c['image'])): ?><img src="<?= htmlspecialchars($c['image']) ?>" alt=""><?php else: ?>-<?php endif; ?></td>
                            <td><?= htmlspecialchars($c['name']) ?></td>
                            <td><?= htmlspecialchars($c['hotel'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($c['meal_type'] ?? '-') ?></td>
                            <td><span class="stars"><?= str_repeat('★', $c['stars'] ?? 0) ?></span></td>
                            <td>
                                <a href="?edit=<?= $c['id'] ?>" class="btn-edit"><i class="fas fa-edit"></i> Ред.</a>
                                <a href="?delete=<?= $c['id'] ?>" class="btn-delete" onclick="return confirm('Удалить страну?')"><i class="fas fa-trash"></i> Удалить</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" style="text-align: center;">Нет добавленных стран</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <a href="admin.php" class="btn-back"><i class="fas fa-arrow-left"></i> Назад в админ-панель</a>
    </div>
</body>
</html>