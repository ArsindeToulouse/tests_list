<?php
session_start();

header ("Content-Type: text/html; charset=utf-8");
require_once 'utils.php';
error_reporting(E_ALL);
date_default_timezone_set('Europe/Moscow');

if(isset($_GET['test'])){
	$file_name = UPLOADS.$_GET['test'].".json";
	$json_test = getJSON($file_name);
	if (!empty($json_test)) {
		//printDump($json_test);
		$form_elm = array();
		foreach ($json_test as $index => $test) {
			foreach ($test as $key => $value) {
				$form_elm[$index][$key] = $value;
			}
		}
		/*$html_form_str = '<form enctype="multipart/form-data" action="check_answer.php" method="POST"><div class="test-form-group"><label for="test-nick">Представтесь, пожалуйста!</label><input type="text" pattern="[a-zA-Zа-яА-Я ]*" maxlength="70" name="test-nick" id="test-nick" required><span>*</span><span class="warning"></span></div><hr>';*/
		$html_form_str = '<form enctype="multipart/form-data" action="check_answer.php" method="POST">';
		for ($i=0; $i < count($json_test); $i++) { 
			$html_form_str .= '<div class="test-form-group"><input name="count" type="hidden" value="'.count($json_test).'">'.getHtmlForm($form_elm[$i],$i,count($json_test)).'</div>';
		}
		$html_form_str .= '<div class="test-form-button"><button>проверить</button></div></form>';
	}else{
		header('Location: 404.php');
		exit;
	}
	
}else{
	$html_form_str = '<p class="test-absent">Для прохождения теста необходимо заглянуть на страницу со списком имеющихся тестов и выбрать желаемое!</p>';
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