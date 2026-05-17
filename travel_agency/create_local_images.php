<?php
// Создаём папку для изображений
$upload_dir = __DIR__ . '/uploads/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
    echo "✓ Папка uploads создана<br>";
}

// Функция создания цветного изображения с текстом
function createColoredImage($filename, $color, $text, $width = 800, $height = 600) {
    global $upload_dir;
    
    // Создаём изображение
    $img = imagecreatetruecolor($width, $height);
    
    // Цвета
    $bg_color = imagecolorallocate($img, $color[0], $color[1], $color[2]);
    $text_color = imagecolorallocate($img, 255, 255, 255);
    $border_color = imagecolorallocate($img, 255, 215, 0);
    
    // Заливаем фон
    imagefill($img, 0, 0, $bg_color);
    
    // Добавляем рамку
    imagerectangle($img, 5, 5, $width-5, $height-5, $border_color);
    
    // Добавляем текст
    $font_size = 5;
    $text_width = imagefontwidth($font_size) * strlen($text);
    $text_height = imagefontheight($font_size);
    $x = ($width - $text_width) / 2;
    $y = ($height - $text_height) / 2;
    imagestring($img, $font_size, $x, $y, $text, $text_color);
    
    // Добавляем иконку отеля (простой квадрат)
    imagerectangle($img, $width-100, 20, $width-20, 100, $border_color);
    imagestring($img, 3, $width-90, 50, "HOTEL", $text_color);
    
    // Сохраняем
    imagejpeg($img, $upload_dir . $filename, 90);
    imagedestroy($img);
}

// Создаём изображения для стран
$countries = [
    ['turkey.jpg', [52, 152, 219], 'ТУРЦИЯ'],
    ['egypt.jpg', [241, 196, 15], 'ЕГИПЕТ'],
    ['thailand.jpg', [46, 204, 113], 'ТАИЛАНД'],
    ['greece.jpg', [52, 73, 94], 'ГРЕЦИЯ'],
    ['italy.jpg', [155, 89, 182], 'ИТАЛИЯ'],
    ['uae.jpg', [230, 126, 34], 'ОАЭ'],
    ['maldives.jpg', [26, 188, 156], 'МАЛЬДИВЫ'],
    ['spain.jpg', [231, 76, 60], 'ИСПАНИЯ']
];

echo "<h2>Создание изображений...</h2>";

foreach ($countries as $country) {
    createColoredImage($country[0], $country[1], $country[2]);
    echo "✓ Создано: " . $country[0] . "<br>";
}

// Создаём дополнительные изображения для туров
$tours = [
    'tour1.jpg' => [41, 128, 185],
    'tour2.jpg' => [142, 68, 173],
    'tour3.jpg' => [39, 174, 96],
    'tour4.jpg' => [243, 156, 18],
    'tour5.jpg' => [192, 57, 43],
    'tour6.jpg' => [22, 160, 133]
];

foreach ($tours as $filename => $color) {
    createColoredImage($filename, [$color[0], $color[1], $color[2]], 'ТУР');
    echo "✓ Создано: " . $filename . "<br>";
}

echo "<h2>✅ Все изображения созданы!</h2>";
echo "<h3>Путь к изображениям:</h3>";
echo "<code>C:\\xampp\\htdocs\\travel_agency\\uploads\\</code><br><br>";
echo "<a href='admincountries.php' style='display: inline-block; padding: 10px 20px; background: #27ae60; color: white; text-decoration: none; border-radius: 5px;'>Перейти в админ-панель</a>";
?>