<?php
require_once 'includes/book.php';

// Проверяем наличие UUID
if (!isset($_GET['uuid'])) {
	http_response_code(400);
	die('Не указан идентификатор книги');
}

// Получаем данные книги
$book = Book::getByUUID($_GET['uuid']);

// Проверяем, что книга существует
if (!$book || !file_exists($book['file'])) {
	http_response_code(404);
	die('Файл книги не найден');
}

// Устанавливаем заголовки для PDF
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . basename($book['file']) . '"');
header('Content-Length: ' . filesize($book['file']));

// Отключаем кэширование
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Отправляем файл
readfile($book['file']);
exit;