<?php
require_once 'config.php';

function db_connect()
{
    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($db->connect_error) {
        die("Ошибка подключения: " . $db->connect_error);
    }
    return $db;
}

function login_user($username, $password)
{
    $db = db_connect();

    // Проверяем количество попыток входа
    $ip = $_SERVER['REMOTE_ADDR'];
    $stmt = $db->prepare("SELECT attempts, last_attempt FROM login_attempts WHERE ip = ?");
    $stmt->bind_param("s", $ip);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $attempt = $result->fetch_assoc();
        if (
            $attempt['attempts'] >= MAX_LOGIN_ATTEMPTS &&
            time() - strtotime($attempt['last_attempt']) < LOGIN_TIMEOUT
        ) {
            return ['error' => 'Превышено количество попыток входа. Попробуйте позже.'];
        }
    }

    // Ищем пользователя
    $stmt = $db->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Успешный вход - сбрасываем счетчик попыток
            $stmt = $db->prepare("DELETE FROM login_attempts WHERE ip = ?");
            $stmt->bind_param("s", $ip);
            $stmt->execute();

            return $user;
        }
    }

    // Неудачная попытка входа - обновляем счетчик
    if ($result->num_rows > 0) {
        $stmt = $db->prepare("INSERT INTO login_attempts (ip, attempts, last_attempt) 
                             VALUES (?, 1, NOW()) ON DUPLICATE KEY UPDATE 
                             attempts = attempts + 1, last_attempt = NOW()");
        $stmt->bind_param("s", $ip);
        $stmt->execute();
    }

    return ['error' => 'Неверное имя пользователя или пароль'];
}

// Добавляем эту функцию в auth.php
function register_user($username, $email, $password) {
    $db = db_connect();
    
    // Проверяем существование пользователя
    $stmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        return ['error' => 'Пользователь с таким именем или email уже существует'];
    }
    
    // Регистрируем нового пользователя
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = 'user';
    
    $stmt = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);
    
    if ($stmt->execute()) {
        return ['success' => true, 'user_id' => $stmt->insert_id];
    } else {
        return ['error' => 'Ошибка при регистрации'];
    }
}

function is_logged_in()
{
    return isset($_SESSION['user']);
}

function is_admin()
{
    return is_logged_in() && $_SESSION['user']['role'] === 'admin';
}

function require_login()
{
    if (!is_logged_in()) {
        header("Location: login.php");
        exit;
    }
}

function require_admin()
{
    require_login();
    if (!is_admin()) {
        header("Location: ../index.php");
        exit;
    }
}
