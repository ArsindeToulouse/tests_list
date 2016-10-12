<?php
/*
 * 1. Timestamp as ID (_GET['test-date'])
 * 2. Название теста - обязательное поле (_POST['test-title'])
 * 2. Уровень сложности - может быть не заполнено (_POST['test-level'])
 * 3. Кол-во вопросов - может быть не заполнено (_POST['test-count'])
 * 4. Время выполнения - может быть не заполнено (_POST['test-time'])
 * Название загружаемого на сервер файла с тестом формируется
 * следующим образом: test_[timestamp].json
 * Временное название загружаемого файла берется из _FILES['test-file']['tmp_name']
 */
session_start();

header ("Content-Type: text/html; charset=utf-8");
require_once 'utils.php';
error_reporting(E_ALL);
date_default_timezone_set('Europe/Moscow');

$tests = getListTests();
$is_delete = isUser() ? true : false;

if(isset($_GET['test-date'])){
	$test_id = $_GET['test-date'];
	if(isset($_FILES['test-file']['tmp_name'])){
		$test_tmp_file = $_FILES['test-file']['tmp_name'];
		// Проверяем загружаемый файл JSON на соответствие структуре данных
		$check_json_structure = testJSONStructure($test_tmp_file);
		if ($check_json_structure) {
			if (checkUploadFile($test_tmp_file, $test_id)) {
				if(isset($_POST)){
					$new_test = array();
					$new_test[] = $_GET['test-date'];
					$add_description = $_GET['test-date'];
					foreach ($_POST as $key => $value) {
						$new_test[] = $value;
						$add_description .= ":".$value;
					}

					if(addTestToList($add_description)){
						$msg_write = '<div class="msg success">Добавлена запись в список тестов</div>';
						$tests[] = $new_test;
						//$html_str = getHtmlTable($tests, $is_delete);
					}else{
						$msg_write = '<div class="msg faild">Невозможно добавить запись о новом тесте</div>';
					}
				} 

				$msg = '<div class="msg success">Файл успешно загружен</div>';

			}else{
				$msg = '<div class="msg faild">Не удалось загрузить файл. Возможно превышен лимит на размер загружаемого файла. Свяжитесь с администратором</div>';
			}
		}else{
			$msg = '<div class="msg faild">Структура загружаемого файла не соответствует требованиям приложения.</div>';
		}
	}else{
		$msg = '<div class="msg faild">Вы забыли выбрать файл для загрузки.</div>';
	}
}//else{
	(empty($tests)) ? $msg_list = '<div class="msg faild">Список тестов пока пуст, но Вы можете добавить новый тест выбрав пункт меню "ВВОД ТЕСТА"</div>' :  $html_str = getHtmlTable($tests, $is_delete);
//}
if (isset($_GET['msg'])) {
	$msg = $_GET['msg'] ? '<div class="msg success">Запись успешно удалена.</div>' : '<div class="msg faild">Не удалось удалить запрошенную запись.</div>';
	$html_str = getHtmlTable($tests, $is_delete);
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
			if (isset($msg_write)) printf($msg_write);
		?>
	</article>
	<article class="container-fluid">
		<?php
			if (isset($msg)) printf($msg);
		?>
	</article>
	<article class="container-fluid">
		<?php
			(isset($msg_list)) ? printf($msg_list) : printf($html_str);
		?>
	</article>
	<footer>
		<div class="end-phrase">"Вологодские леса" @ 2016</div>
	</footer>
</body>
</html>