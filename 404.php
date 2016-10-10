<?php
session_start();

header ("Content-Type: text/html; charset=utf-8");
require_once 'utils.php';
error_reporting(E_ALL);
date_default_timezone_set('Europe/Moscow');

$html_form_str = '<p class="test-404">Ошибка 404!</p><p class="test-absent">Запрашиваемый Вами тест не обнаружен в нашем банке. <br>Попробуйте выбрать другой!</p>';
if (isset($_GET['code'])) {
	$html_form_str = '<p class="test-404">Ошибка '.$_GET['code'].'!</p><p class="test-absent">Несанкционированный доступ! <br>Вы должны зарегистрироваться.</p>';
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
		<div class="test-form">
			<?=$html_form_str;?>
		</div>
	</article>
	<footer>
		<div class="end-phrase">"Вологодские леса" @ 2016</div>
	</footer>
</body>
</html>