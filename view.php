<?php
require_once 'includes/book.php';

// Проверяем наличие UUID
if (!isset($_GET['uuid'])) {
	die('Не указан идентификатор книги');
}

// Получаем данные книги
$book = Book::getByUUID($_GET['uuid']);

// Проверяем, что книга существует
if (!$book) {
	die('Книга не найдена');
}

$title = $book['title'];

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

	<main class="main pt-2">
		<div class="container-fluid">
			<div class="row" style="justify-content: center">
				<div class="default-container">
					<iframe STYLE="display: flex; width: 95%; border-radius: 10px;" src="file.php?uuid=<?= urlencode($_GET['uuid']) ?>"></iframe>
				</div>
			</div>
		</div>
	</main>

<?php require 'includes/footer.php'; ?>