<?php
session_start();

header ("Content-Type: text/html; charset=utf-8");
require_once 'utils.php';
error_reporting(E_ALL);

//printDump($_POST);

if (isset($_POST['login']) && isset($_POST['password'])) {
	$json_file = CONFIG.$_POST['login'].".json";
	$json = getJSON($json_file);
	if ($json) {
		$auth_data = jsonObjToArray($json);

		if (!checkUserLogin($auth_data)) print_r('Проблемы с авторизацией');
		//printDump($json);
	}else{
		$post_array = array('login' => $_POST['login'], 'password' => $_POST['password']);
		saveJSON($post_array, $json_file) ? $_SESSION['login'] = $_POST['login'] : print_r('Проблемы с записью JSON');
	}
	
	//print_r($_SESSION['login']);
}
if (isset($_POST['guest'])) {
	setcookie('guest', $_POST['guest']);
	header('Location: index.php');
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
		<?php
			if (isUser() || isGuest()) {
				$user_name = isUser() ? htmlspecialchars($_SESSION['login']) : htmlspecialchars($_COOKIE['guest']);
		?>
		<div class="test-home">
			<p class="greeting">Приветствуем Вас, <?=$user_name;?>!</p>
		</div>
		<?php
		}else{
		?>
		<div class="test-home">
			<p>Приложение позволяет загрузить тест в формате JSON на сервер, <br>вывести список уже загруженных тестов и, выбрав интересное для себя,<br>пройти тест и получить мгновенный результат.</p>
		</div>
		<div class="test-home">
			<p>Вы можете зарегистрироваться на сайте и тогда Вам будут доступны <br>следующие опции:</p>
			<ul>
				<li> загрузка собственных тестов;</li>
				<li> редактирование тестов;</li>
				<li> удаление тестов.</li>
			</ul>
			<p>Но Вы также можете войти как Гость. Тогда Вы сможете выбрать тест<br>из общего списка и увидеть результат.</p>
		</div>
		<div class="test-auth">
			<form enctype="multipart/form-data" action="index.php" class="test-form-auth" method="post">
				<h3 class="form-h3 auth-h3">Зарегистрироваться</h3>
				<div class="test-form-auth-group">
					<label for="login" class="label-auth">Имя или ник:</label>
					<input type="text" pattern="[a-zA-Z]*" name="login">
				</div>
				<div class="test-form-auth-group">
					<label for="password" class="label-auth">Пароль:</label>
					<input type="password" name="password">
				</div>
				<div class="test-form-auth-group">
					<button class="button-auth">сохранить</button>
				</div>
			</form>
		</div>
		<div class="test-auth">
			<form enctype="multipart/form-data" action="index.php" class="test-form-guest" method="post">
				<h3 class="form-h3 auth-h3">Войти без регистрации</h3>
				<div class="test-form-auth-group">
					<label for="guest" class="label-auth">Представтесь, пожалуйста:</label>
					<input type="text" name="guest">
				</div>
				<div class="test-form-auth-group">
					<button class="button-auth">войти</button>
				</div>
			</form>
		</div>
		<?php
		}
		?>
		
	</article>
	<footer>
		<div class="end-phrase">"Вологодские леса" @ 2016</div>
	</footer>
</body>
</html>