<?php
session_start();
require_once 'includes/auth.php';
require_login();

if ($_SESSION['user']['username'] == 'admin') {
	header("Location: admin/index.php");
	exit;
}

require_once('includes/feedback.php');
$title = 'Форма обратной связи';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Получаем данные из формы
	$name = trim(isset($_POST['name']) ? $_POST['name'] : '');
	$email = trim(isset($_POST['email']) ? $_POST['email'] : '');
	$message = trim(isset($_POST['message']) ? $_POST['message'] : '');

	// Валидация данных
	$errors = [];

	if (empty($name)) {
		$errors[] = 'Пожалуйста, введите ваше имя.';
	}

	if (empty($email)) {
		$errors[] = 'Пожалуйста, введите ваш email.';
	} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$errors[] = 'Пожалуйста, введите корректный email.';
	}

	if (empty($message)) {
		$errors[] = 'Пожалуйста, введите ваше сообщение.';
	}

	// Если ошибок нет, обрабатываем данные
	if (empty($errors)) {
        $query = 'INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)';
        $params = [$name, $email, $message];
		Feedback::executeQuery($query, $params);
		header('Location: index.php');
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
                    <li><a href="/" class="<?php if ($title === 'Главная') {
							echo 'active';
						} else {
							echo '';
						} ?>">Главная</a></li>
                    <li><a href="/books.php" class="<?php if ($title === 'Книги') {
							echo 'active';
						} else {
							echo '';
						} ?>">Книги</a></li>
                    <li><a href="/feedback.php" class="<?php if ($title === 'Форма обратной связи') {
							echo 'active';
						} else {
							echo '';
						} ?>">Форма обратной связи</a></li>
                    <li><a href="/logout.php">Выйти</a></li>
					<?php if (isset($_SESSION['user'])): ?>
                        <li>
                            Вы вошли как: <?= htmlspecialchars($_SESSION['user']['username']) ?>
                        </li>
					<?php endif; ?>
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
	                    <?php if (!empty($errors)): ?>
                            <ul class="error">
			                    <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
			                    <?php endforeach; ?>
                            </ul>
	                    <?php endif; ?>
                        <form action="feedback.php" method="POST" style="max-width: 768px; margin: auto; padding: 10px 0;">
                            <h4 style="text-align: center; margin-bottom: 20px;">Форма обратной связи</h4>
                            <div style="margin-bottom: 20px;">
                                <label for="name" style="display: block; color: #ccc; margin-bottom: 8px;">Ваше имя:</label>
                                <input type="text" id="name" name="name" placeholder="Введите имя"
                                       style="width: 100%; padding: 12px 20px; border: none; border-radius: 23px; background-color: #1e1e1e; color: #fff; font-size: 14px; transition: all 0.3s;"
                                       required>
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label for="email" style="display: block; color: #ccc; margin-bottom: 8px;">Ваш e-mail:</label>
                                <input type="email" id="email" name="email" placeholder="Введите e-mail"
                                       style="width: 100%; padding: 12px 20px; border: none; border-radius: 23px; background-color: #1e1e1e; color: #fff; font-size: 14px; transition: all 0.3s;"
                                       required>
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label for="message" style="display: block; color: #ccc; margin-bottom: 8px;">Сообщение:</label>
                                <textarea id="message" name="message" rows="7" placeholder="Опишите сообщение"
                                          style="width: 100%; padding: 12px 20px; border: none; border-radius: 23px; background-color: #1e1e1e; color: #fff; font-size: 14px; transition: all 0.3s; resize: vertical; overflow: hidden;"></textarea>
                            </div>
                            <div style="text-align: center;">
                                <button type="submit" class="btn btn-light p-2"
                                        style="width: 25%; text-align: center;">Отправить
                                </button>
                            </div>
                        </form>
                        <div class="col-lg-12 pt-4">
                            <div class="main-button" style="text-align: center;">
                                <a href="/">Вернуться на главную</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>