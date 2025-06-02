<?php
//// Подключение к базе данных (пример для MySQL)
//$db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
//
//if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//	$username = $_POST['username'];
//	$email = $_POST['email'];
//	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
//
//	// Проверка на существующего пользователя
//	$stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
//	$stmt->execute([$email]);
//
//	if ($stmt->rowCount() > 0) {
//		$error = "Пользователь с таким email уже существует";
//	} else {
//		// Регистрация нового пользователя
//		$stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
//		$stmt->execute([$username, $email, $password]);
//
//		header("Location: login.php?registered=1");
//		exit();
//	}
//}
//?>
<!---->
<!--<!DOCTYPE html>-->
<!--<html lang="ru">-->
<!--<head>-->
<!--	<meta charset="UTF-8">-->
<!--	<title>Регистрация</title>-->
<!--	<style>-->
<!--		body { font-family: Arial, sans-serif; max-width: 400px; margin: 0 auto; padding: 20px; }-->
<!--		.form-group { margin-bottom: 15px; }-->
<!--		label { display: block; margin-bottom: 5px; }-->
<!--		input { width: 100%; padding: 8px; box-sizing: border-box; }-->
<!--		button { background: #4CAF50; color: white; padding: 10px 15px; border: none; cursor: pointer; }-->
<!--		.error { color: red; }-->
<!--	</style>-->
<!--</head>-->
<!--<body>-->
<!--<h1>Регистрация</h1>-->
<!---->
<?php //if (isset($error)): ?>
<!--	<div class="error">--><?php //= htmlspecialchars($error) ?><!--</div>-->
<?php //endif; ?>
<!---->
<!--<form method="POST">-->
<!--	<div class="form-group">-->
<!--		<label for="username">Имя пользователя:</label>-->
<!--		<input type="text" id="username" name="username" required>-->
<!--	</div>-->
<!---->
<!--	<div class="form-group">-->
<!--		<label for="email">Email:</label>-->
<!--		<input type="email" id="email" name="email" required>-->
<!--	</div>-->
<!---->
<!--	<div class="form-group">-->
<!--		<label for="password">Пароль:</label>-->
<!--		<input type="password" id="password" name="password" required>-->
<!--	</div>-->
<!---->
<!--	<button type="submit">Зарегистрироваться</button>-->
<!--</form>-->
<!---->
<!--<p>Уже есть аккаунт? <a href="login.php">Войти</a></p>-->
<!--</body>-->
<!--</html>-->

<?php
session_start();
$db = new mysqli('localhost', 'root', 'root', 'auth_db');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = $db->real_escape_string($_POST['username']);
	$email = $db->real_escape_string($_POST['email']);
	$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

	// Проверка существующего email
	$result = $db->query("SELECT * FROM users WHERE email = '$email'");
	if ($result->num_rows > 0) {
		$error = "Email уже занят!";
	} else {
		$db->query("INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')");
		header("Location: /login?registered=1");
		exit();
	}
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Регистрация</title>
</head>
<body>
<h1>Регистрация</h1>
<?php if (isset($error)): ?>
    <div style="color: red"><?= $error ?></div>
<?php endif; ?>
<form method="POST">
    <input type="text" name="username" placeholder="Имя" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Пароль" required>
    <button type="submit">Зарегистрироваться</button>
</form>
<p>Уже есть аккаунт? <a href="/auth/login">Войти</a></p>
</body>
</html>