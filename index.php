<?php
session_start();
require_once 'includes/auth.php';
require_login();

if ($_SESSION['user']['username'] == 'admin') {
	header("Location: admin/index.php");
	exit;
}

require_once('includes/book.php');
$title = 'Главная';

// Инициализация базы данных (выполнить один раз)
Book::initializeDatabase();
$books = Book::getAll();

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
                        <div class="main-banner">
                            <div class="row">
                                <div class="col-lg-7">
                                    <div class="header-text">
                                        <h6>Добро пожаловать в мою электронную библиотеку!</h6>
                                        <h4><em>Смотрите</em> наши популярные книги здесь</h4>
                                        <div class="main-button">
                                            <a href="/books.php">Смотреть сейчас <i class="fa fa-arrow-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="main-container">
                            <div class="row">
								<?php foreach ($books as $book): ?>
                                    <div class="col-lg-4 col-sm-4 mb-3">
                                        <div class="card-container align-items-center">
                                            <div class="card"
                                                 style="background-color: #27292a; border-radius: 23px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.5); transition: all .3s ease;">
                                                <div class="card-image" style="position: relative;">
                                                    <img src="<?= $book['image']; ?>" alt="<?= $book['title']; ?>"
                                                         style="width: 100%; display: block;">
                                                </div>
                                                <div class="card-body">
                                                    <h4 class="short-title" style="margin: 10px 0; font-size: 15px;">
                                                        <a href="/view.php?uuid=<?= $book['uuid']; ?>">
															<?= $book['title']; ?> <i class="fa fa-up-right-from-square"></i>
                                                        </a>
                                                    </h4>
                                                    <span style="display: block; margin-bottom: 10px; color: #666;">Автор: <?= $book['author']; ?></span>
                                                    <span style="display: block; margin-bottom: 10px; color: #666;">Категория: <?= $book['catalog']; ?></span>
                                                    <p class="text-truncate"
                                                       style="font-size: 14px; color: #999; line-height: 20px;">
                                                        Описание: <?= $book['about']; ?></p>
                                                    <!--                                                    <div class="main-button mb-3 mt-4" style="text-align: center;">-->
                                                    <!--                                                        <a href="#/book/-->
													<?php //=$book['uuid']; ?><!--/edit"><i-->
                                                    <!--                                                                    class="fa fa-edit"></i> Редактировать</a>-->
                                                    <!--                                                    </div>-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
								<?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include 'includes/footer.php'; ?>