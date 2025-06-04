<?php
// Настройки базы данных
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'auth_db');

// Настройки безопасности
define('SITE_KEY', 'Electron Library');
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 600); // 10 минут в секундах