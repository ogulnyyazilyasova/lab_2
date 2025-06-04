<?php
session_start();

// Уничтожаем все данные сессии
$_SESSION = array();
session_destroy();

// Перенаправляем на страницу входа
header("Location: login.php");
exit;
