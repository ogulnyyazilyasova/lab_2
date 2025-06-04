<?php
session_start();
require_once '../includes/auth.php';
require_admin();

require_once('../includes/book.php');
$title = 'Добавить книгу - Админ';

// Инициализация базы данных (выполнить один раз)
Book::initializeDatabase();

// Загрузка файла и создание книги
try {
	$filePath = Book::handleFileUpload();

	if ($filePath) {
		$book = new Book(
			$_POST['title'],
			$_POST['author'],
			$filePath,
			isset($_POST['outDate']) ? $_POST['outDate'] : null,
			isset($_POST['catalog']) ? $_POST['catalog'] : null,
			isset($_POST['about']) ? $_POST['about'] : null
		);

		if ($book->save()) {
			echo "Книга успешно добавлена!";
		}
	}
} catch (Exception $e) {
	echo "Ошибка: " . $e->getMessage();
}

$catalogs = Book::getCatalogs();

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
                            <form action="book-add.php" method="POST" enctype="multipart/form-data"
                                  style="max-width: 768px; margin: auto; padding: 10px 0;">
                                <h4 style="text-align: center; margin-bottom: 20px;">Добавить книгу</h4>
                                <div style="margin-bottom: 20px;">
                                    <label for="title" style="display: block; color: #ccc; margin-bottom: 8px;">Название
                                        книги</label>
                                    <input type="text" id="title" name="title" placeholder="Введите название книги"
                                           style="width: 100%; padding: 12px 20px; border: none; border-radius: 23px; background-color: #1e1e1e; color: #fff; font-size: 14px; transition: all 0.3s;"
                                           required>
                                </div>
                                <div style="margin-bottom: 20px;">
                                    <label for="author" style="display: block; color: #ccc; margin-bottom: 8px;">Автор
                                        книги</label>
                                    <input type="text" id="author" name="author" placeholder="Введите автора"
                                           style="width: 100%; padding: 12px 20px; border: none; border-radius: 23px; background-color: #1e1e1e; color: #fff; font-size: 14px; transition: all 0.3s;"
                                           required>
                                </div>
                                <div style="margin-bottom: 20px;">
                                    <p style="display: block; color: #ccc; margin-bottom: 8px;">Файл книги</p>
                                    <label for="file"
                                           style="display: block; margin-bottom: 8px; width: 100%; padding: 12px 20px; border: none; border-radius: 23px; background-color: #1e1e1e; color: #fff; font-size: 14px; transition: all 0.3s;">
                                        <input type="file" id="file" name="fileToUpload" accept="application/pdf"
                                               required>
                                        <span>Выберите файл</span>
                                    </label>
                                </div>
                                <div style="margin-bottom: 20px;">
                                    <label for="outDate" style="display: block; color: #ccc; margin-bottom: 8px;">Дата
                                        выхода</label>
                                    <input type="date" id="outDate" name="outDate"
                                           style="width: 100%; padding: 12px 20px; border: none; border-radius: 23px; background-color: #1e1e1e; color: #fff; font-size: 14px; transition: all 0.3s;"
                                           required>
                                </div>
                                <div style="margin-bottom: 20px;">
                                    <label for="catalog" style="display: block; color: #ccc; margin-bottom: 8px;">Каталог
                                        книги</label>
                                    <select id="catalog" name="catalog"
                                            style="width: 100%; padding: 12px 20px; border: none; border-radius: 23px; background-color: #1e1e1e; color: #fff; font-size: 14px; transition: all 0.3s;"
                                            required>
                                        <?php foreach ($catalogs as $catalog): ?>
                                            <option value="<?= $catalog['name']; ?>"><?= $catalog['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div style="margin-bottom: 20px;">
                                    <label for="about" style="display: block; color: #ccc; margin-bottom: 8px;">Описание
                                        книги</label>
                                    <textarea id="about" name="about" rows="7" placeholder="Опишите книгу"
                                              style="width: 100%; padding: 12px 20px; border: none; border-radius: 23px; background-color: #1e1e1e; color: #fff; font-size: 14px; transition: all 0.3s; resize: vertical; overflow: hidden;"></textarea>
                                </div>
                                <div style="text-align: center;">
                                    <button type="submit" class="btn btn-light p-2"
                                            style="width: 25%; text-align: center;"><i
                                                class="fa fa-add"></i> Добавить
                                    </button>
                                </div>
                            </form>
                            <div class="col-lg-12 pt-4">
                                <div class="main-button" style="text-align: center;">
                                    <a href="/admin">Вернуться на главную</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include '../includes/footer.php'; ?>