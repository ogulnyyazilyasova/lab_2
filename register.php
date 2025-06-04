<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/config.php';

$title = 'Регистрация';

// Если пользователь уже авторизован, перенаправляем на главную
if (is_logged_in()) {
    header("Location: index.php");
    exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Валидация данных
    if (empty($username)) {
        $errors['username'] = 'Имя пользователя обязательно';
    } elseif (strlen($username) < 3) {
        $errors['username'] = 'Имя пользователя должно быть не менее 3 символов';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors['username'] = 'Имя пользователя может содержать только буквы, цифры и подчеркивание';
    }

    if (empty($email)) {
        $errors['email'] = 'Email обязателен';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Некорректный email';
    }

    if (empty($password)) {
        $errors['password'] = 'Пароль обязателен';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Пароль должен быть не менее 6 символов';
    }

    if ($password !== $password_confirm) {
        $errors['password_confirm'] = 'Пароли не совпадают';
    }

    // Проверка уникальности username и email
    if (empty($errors)) {
        $db = db_connect();

        // Проверка username
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors['username'] = 'Это имя пользователя уже занято';
        }

        // Проверка email
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors['email'] = 'Этот email уже используется';
        }

        // Если ошибок нет - регистрируем пользователя
        if (empty($errors)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user'; // По умолчанию обычный пользователь

            $stmt = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

            if ($stmt->execute()) {
                $success = 'Регистрация прошла успешно! Теперь вы можете войти.';
                // Можно автоматически авторизовать пользователя
                // $_SESSION['user'] = ['id' => $stmt->insert_id, 'username' => $username, 'role' => $role];
                // header("Location: index.php");
            } else {
                $errors['database'] = 'Ошибка при регистрации. Попробуйте позже.';
            }
        }

        $db->close();
    }
}

include 'includes/header.php';
?>

<header class="header header-area header-sticky">
    <div class="container-fluid">
        <div class="row">
            <nav class="main-nav">
                <div class="logo">
                    <a href="/"><img src="/img/logo.png" alt="Логотип электронной библиотеки"></a>
                </div>
                <!-- <form id="search" action="#">
                    <input type="text" placeholder="Поиск..." id='searchText' name="searchKeyword" />
                    <i class="fa fa-search"></i>
                </form> -->
                <ul class="nav">
                    <li><a href="/login.php">Войти</a></li>
                </ul>
                <a class='menu-trigger'>
                    <span>Меню</span>
                </a>
            </nav>
        </div>
    </div>
</header>

<main class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-content">
                    <div class="default-container">
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                            <p><a href="/login.php" class="btn btn-primary">Войти</a></p>
                        <?php else: ?>
                            <?php if (isset($errors['database'])): ?>
                                <div class="alert alert-danger"><?= htmlspecialchars($errors['database']) ?></div>
                            <?php endif; ?>
                            <form action="register.php" method="POST" style="max-width: 768px; margin: auto; padding: 10px 0;">
                                <h4 style="text-align: center; margin-bottom: 20px;">Регистрация</h4>
                                <div style="margin-bottom: 20px;">
                                    <label for="username" style="display: block; color: #ccc; margin-bottom: 8px;">Имя
                                        пользователя:</label>
                                    <input type="text" id="username" name="username"
                                        placeholder="Введите имя пользователя"
                                        style="width: 100%; padding: 12px 20px; border: none; border-radius: 23px; background-color: #1e1e1e; color: #fff; font-size: 14px; transition: all 0.3s;"
                                        value="<?= isset($username) ? htmlspecialchars($username) : '' ?>"
                                        class="<?= isset($errors['username']) ? 'is-invalid' : '' ?>"
                                        required>
                                    <?php if (isset($errors['username'])): ?>
                                        <div class="invalid-feedback"><?= htmlspecialchars($errors['username']) ?></div>
                                    <?php endif; ?>
                                </div>
                                <div style="margin-bottom: 20px;">
                                    <label for="email"
                                        style="display: block; color: #ccc; margin-bottom: 8px;">E-mail:</label>
                                    <input type="email" id="email" name="email" placeholder="Введите e-mail"
                                        style="width: 100%; padding: 12px 20px; border: none; border-radius: 23px; background-color: #1e1e1e; color: #fff; font-size: 14px; transition: all 0.3s;"
                                        value="<?= isset($email) ? htmlspecialchars($email) : '' ?>"
                                        class="<?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                        required>
                                    <?php if (isset($errors['email'])): ?>
                                        <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
                                    <?php endif; ?>
                                </div>
                                <div style="margin-bottom: 20px;">
                                    <label for="password"
                                        style="display: block; color: #ccc; margin-bottom: 8px;">Пароль:</label>
                                    <input type="password" id="password" name="password" placeholder="Введите пароль"
                                        style="width: 100%; padding: 12px 20px; border: none; border-radius: 23px; background-color: #1e1e1e; color: #fff; font-size: 14px; transition: all 0.3s;"
                                        value="<?= isset($password) ? htmlspecialchars($password) : '' ?>"
                                        class="<?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                                        required>
                                    <?php if (isset($errors['password'])): ?>
                                        <div class="invalid-feedback"><?= htmlspecialchars($errors['password']) ?></div>
                                    <?php endif; ?>
                                </div>
                                <div style="margin-bottom: 20px;">
                                    <label for="password_confirm"
                                        style="display: block; color: #ccc; margin-bottom: 8px;">Подтвердите пароль:</label>
                                    <input type="password" id="password_confirm" name="password_confirm" placeholder="Подтвердите пароль"
                                        style="width: 100%; padding: 12px 20px; border: none; border-radius: 23px; background-color: #1e1e1e; color: #fff; font-size: 14px; transition: all 0.3s;"
                                        value="<?= isset($password_confirm) ? htmlspecialchars($password_confirm) : '' ?>"
                                        class="<?= isset($errors['password_confirm']) ? 'is-invalid' : '' ?>"
                                        required>
                                    <?php if (isset($errors['password_confirm'])): ?>
                                        <div class="invalid-feedback"><?= htmlspecialchars($errors['password_confirm']) ?></div>
                                    <?php endif; ?>
                                </div>
                                <div style="text-align: center;">
                                    <button type="submit" class="btn btn-light p-2"
                                        style="width: 25%; text-align: center;">Зарегистрироваться</button>
                                </div>
                            </form>
                            <div class="col-lg-12 pt-4">
                                <div class="button" style="text-align: center; align-items: center;">
                                    Уже есть аккаунт? <a href="login.php">Войдите</a>
                                </div>
                            </div>
                    </div>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>