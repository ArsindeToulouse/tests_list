<?php
session_start();

header ("Content-Type: text/html; charset=utf-8");
require_once 'utils.php';
error_reporting(E_ALL);
date_default_timezone_set('Europe/Moscow');

if(isset($_POST)){
	$number = 0;
	//printDump($_POST);
	for ($i=0; $i < $_POST['count']; $i++) { 
		if (isset($_POST['choice_'.$i]) && intval($_POST['answer_'.$i])-intval($_POST['choice_'.$i]) === 0) {
			$number ++;
		}
	}

	$result = round($number/intval($_POST['count']),2);
	$nickname = isUser() ? htmlspecialchars($_SESSION['login']) : htmlspecialchars($_COOKIE['guest']);

	if ($result > 0.8 && $result <= 1.0) {
		$html_str = '<div class="test-mark"><img src="diplom.php?mark=5&name='.$nickname.'" alt=""></div>';
	}elseif ($result > 0.6 && $result <= 0.8) {
		$html_str = '<div class="test-mark"><img src="diplom.php?mark=4&name='.$nickname.'" alt=""></div>';
	}elseif ($result > 0.4 && $result <= 0.6) {
		$html_str = '<div class="test-mark"><img src="diplom.php?mark=3&name='.$nickname.'" alt=""></div>';
	}else{
		$html_str = '<div class="test-mark"><img src="diplom.php?mark=2&name='.$nickname.'" alt=""></div>';
	}
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
			printf($html_str);
		?>

	</article>
	<footer>
		<div class="end-phrase">"Вологодские леса" @ 2016</div>
	</footer>
</body>
</html>