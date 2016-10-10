<?php
session_start();

header ("Content-Type: text/html; charset=utf-8");
require_once 'utils.php';
error_reporting(E_ALL);

if(!isUser()) {
	http_response_code(403);
	header('Location: 404.php?code=403');
	die;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Lesson 2.2</title>
	<link rel="stylesheet" href="css/app.css">
</head>
<body>
	<header>
		<hgroup>
			<h1>Банк данных тестов</h1>
		</hgroup>
		<nav>
			<div class="main-menu">
			<?php
				if (isUser() || isGuest()) {
				?>
				<ul class="main-menu-ul">
					<li><a href="index.php">главная</a></li>
					<?php
					if(isUser()) {
					?>
					<li><a href="admin.php">ввод теста</a></li>
					<?php
					}
					?>
					<li><a href="list_of_tests.php">список тестов</a></li>
					<li><a href="test.php">пройти тест</a></li>
					<li><a href="logout.php">выход</a></li>
				</ul>
				<?php
				}
			?>
			</div>
		</nav>
	</header>
	<article class="container-fluid">
		<div class="admin-form">
			<form enctype="multipart/form-data" action="list_of_tests.php?test-date=<?=time();?>" method="POST">
				<div class="admin-form-group">
					<label for="test-title">Название теста</label>
					<input type="text" pattern="[a-zA-Zа-яА-Я ]*" maxlength="70" name="test-title" id="test-title" required><span>*</span>
					<span class="warning"></span>
				</div>
				<div class="admin-form-group">
					<label for="test-level">Уровень сложности</label>
					<input type="text" name="test-level" id="test-level">
				</div>
				<div class="admin-form-group">
					<label for="test-count">Кол-во вопросов</label>
					<input type="text" pattern="[0-9]*" maxlength="5" name="test-count" id="test-count">
				</div>
				<div class="admin-form-group">
					<label for="test-time">Время выполнения</label>
					<input type="text" pattern="[0-9]*" maxlength="5" name="test-time" id="test-time">
				</div>
				<div class="admin-form-group">
					<label for="test-file">Загрузка теста</label>
					<span>*</span><input type="file" maxlength="20" name="test-file" id="test-file" accept="application/json" required>
				</div>
				<div class="admin-form-button">
					<button>сохранить</button>
				</div>
			</form>
		</div>
	</article>
	<footer>
		<div class="end-phrase">"Вологодские леса" @ 2016</div>
	</footer>
</body>
</html>