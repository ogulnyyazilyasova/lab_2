<?php
session_start();
require_once 'includes/auth.php';

$title = 'Войти';

if (is_logged_in()) {
    header("Location: index.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $result = login_user($username, $password);

    if (isset($result['error'])) {
        $error = $result['error'];
    } else {
        $_SESSION['user'] = $result;
        header("Location: index.php");
        exit;
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
                    <li><a href="/register.php">Регистрация</a></li>
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
                        <?php if (isset($error)): ?>
                            <div class="error"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        <form action="login.php" method="POST" style="max-width: 768px; margin: auto; padding: 10px 0;">
                            <h4 style="text-align: center; margin-bottom: 20px;">Вход в систему</h4>
                            <div style="margin-bottom: 20px;">
                                <label for="username" style="display: block; color: #ccc; margin-bottom: 8px;">Имя
                                    пользователя:</label>
                                <input type="text" id="username" name="username"
                                    placeholder="Введите имя пользователя"
                                    style="width: 100%; padding: 12px 20px; border: none; border-radius: 23px; background-color: #1e1e1e; color: #fff; font-size: 14px; transition: all 0.3s;"
                                    required>
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label for="password"
                                    style="display: block; color: #ccc; margin-bottom: 8px;">Пароль:</label>
                                <input type="password" id="password" name="password" placeholder="Введите пароль"
                                    style="width: 100%; padding: 12px 20px; border: none; border-radius: 23px; background-color: #1e1e1e; color: #fff; font-size: 14px; transition: all 0.3s;"
                                    required>
                            </div>
                            <div style="text-align: center;">
                                <button type="submit" class="btn btn-light p-2"
                                    style="width: 25%; text-align: center;">Войти</button>
                            </div>
                        </form>
                        <div class="col-lg-12 pt-4">
                            <div class="button" style="text-align: center; align-items: center;">
                                Нет аккаунта? <a href="register.php">Зарегистрируйтесь</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>