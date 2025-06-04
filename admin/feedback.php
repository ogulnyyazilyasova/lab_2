<?php
session_start();
require_once '../includes/auth.php';
require_admin();

require_once('../includes/feedback.php');
$title = 'Отзывы - Админ';

// Инициализация базы данных (выполнить один раз)
$feedbacks = Feedback::fetchAll('SELECT * FROM feedback;');

include '../includes/header.php';
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
                        <li><a href="/admin/" class="<?php if ($title === 'Главная - Админ') {
								echo 'active';
							} else {
								echo '';
							} ?>">Главная</a></li>
                        <li><a href="/admin/books.php" class="<?php if ($title === 'Книги - Админ') {
								echo 'active';
							} else {
								echo '';
							} ?>">Книги</a></li>
                        <li><a href="/admin/book-add.php" class="<?php if ($title === 'Добавить книгу - Админ') {
								echo 'active';
							} else {
								echo '';
							} ?>">Добавить книгу</a></li>
                        <li><a href="/admin/feedback.php" class="<?php if ($title === 'Отзывы - Админ') {
								echo 'active';
							} else {
								echo '';
							} ?>">Отзывы</a></li>
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
                            <div class="row">
	                            <?php foreach ($feedbacks as $feedback): ?>
                                    <div class="col-lg-12 col-sm-12 mb-3">
                                        <div class="card-container align-items-center">
                                            <div class="card"
                                                 style="background-color: #27292a; border-radius: 23px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.5); transition: all .3s ease;">
                                                <div class="card-body">
                                                    <h4 class="short-title" style="margin: 10px 0; font-size: 15px;">Имя: <?= $feedback['name']; ?></h4>
                                                    <span style="display: block; margin-bottom: 10px; color: #666;">E-mail: <?= $feedback['email']; ?></span>
                                                    <p style="display: block; margin-bottom: 10px; color: #666;">Сообщение: <?= $feedback['message']; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
								<?php endforeach; ?>
                            </div>
                            <div class="col-lg-12 pt-4">
                                <div class="main-button" style="text-align: center;">
                                    <a href="/admin/">Вернуться на главную</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include '../includes/footer.php'; ?>