<?php
// Определяем путь до файла со списком тестов
define('LIST_FILE', __DIR__."\\uploads\\tests_list.lst");
define('UPLOADS', __DIR__."\\uploads\\");
define('CONFIG', __DIR__."\\config\\");

/*
 * Функция читает LST-файла со списком загруженных тестов.
 * Возвращает массив с данными, если файл существует и он не пустой.
 * В противном случае - пустой массив.
 */
function getListTests(){
	$tests_list = array();

	if (file_exists(LIST_FILE) && filesize(LIST_FILE) !== 0) {
		$fp = fopen(LIST_FILE, "r");
		$contents = fread($fp, filesize(LIST_FILE));
		fclose($fp);

		$tests_array = explode(";", $contents);
		foreach ($tests_array as $value) {
			if(!empty($value)) $tests_list[] = explode(":", $value);
		}
	}

	return $tests_list;
}
/*
 * Функция дописывает новую строку с описание загруженного
 * теста. Возвращает TRUE, если запись прошла успешно.
 * В противном случае - FALSE.
 */
function addTestToList($str){
	$fp = fopen(LIST_FILE, "a+");
	if (fwrite($fp, ";".$str)) {
		fclose($fp);
		return true;
	}
	fclose($fp);
	return false;
}

function replaceListFile($str){
	$fp = fopen(LIST_FILE, "w+");
	if (fwrite($fp, $str)) {
		fclose($fp);
		return true;
	}
	fclose($fp);
	return false;
}
/*
 * Функция проверяет запись временно загруженного файла на сервер.
 * Возвращает TRUE, если файл загружен успешно.
 * В противном случае - FALSE.
 */
function checkUploadFile($tmp_name,$name){
	$file_name = UPLOADS.$name.'.json';
	if(move_uploaded_file($tmp_name, $file_name)){
		return true;
	}else{
		return false;
	}
}
/*
 * Функция формирует HTML-строку с таблицей, заполняемой
 * данным, полученным из LST-файла со списком загруженных тестов. 
 * Принимает в качестве параметров:
 * $arr - массив с данными из LST-файла;
 * $del - флаг авторизации - если true - то в таблице пояляется
 * возможность удалить файл с тестом и его описание
 */
function getHtmlTable($arr,$del){
	$html_str = '<div class="list"><table class="list-table"><tr><th class="td-title">Название теста</th><th>Уровень</th><th>Кол-во вопросов</th><th>Время выполнения</th>';
	$html_str .= ($del) ? '<th>Удалить</th></tr>' : '</tr>';

		foreach ($arr as $test) {
			$html_str .= '<tr>';
			$id = 0;
			foreach ($test as $key => $value) {
				if ($key === 0) {
					$id = intval($value);
				}elseif ($key === 1) {
					$html_str .= '<td class="td-title"><a href="test.php?test='.$id.'">'.$value.'</a></td>';
				}else{
					$html_str .= '<td>'.$value.'</td>';
				}
			}
				$html_str .= $del ? '<td><a href="delete.php?id='.$id.'"><img src="images/delete_small.png" alt=""></a></td></tr>': '</tr>';
				unset($id);
		}
	$html_str .= '</table></div>';

	return $html_str;
}
/*
 * Функция читает данные из JSON-файла.
 * Возвращает массив, если файл существует.
 * В противном случае - false.
 */
function getJSON($file_name){
	//$file_name = UPLOADS.$name.".json";
	if (file_exists($file_name)) {
		$json_array = file($file_name);
		$json_str = implode("", $json_array);
		$json = json_decode($json_str);

		return $json;
	}

	return false;
}
/*
 * Функция формирует HTML-строку с элементами формы согласно 
 * данным, полученным из JSON-файла с тестом. Принимает в качестве параметров:
 * $arr - массив с данными из тестового файла;
 * $index - индекс передаваемого массива в файле JSON.
 */
function getHtmlForm($arr,$index){
	$form = '<h3 id="h3_'.$index.'" class="form-h3">'.$arr['question'].'</h3>';
	for ($i=0; $i < count($arr['choice']); $i++) { 
		$form .= '<p><input name="choice_'.$index.'" type="radio" value="'.$i.'">'.$arr['choice'][$i].'</p>';
	}
	$form .= '<input name="answer_'.$index.'" type="hidden" value="'.$arr['answer'].'">';

	return $form;
}
/*
 * Функция создает открытку принимая в качестве параметра
 * $text - ткст, отображаемый на открытке в верхнем левом углу;
 * $img_file - путь к файлу с картинкой в формате .png.
 */
function createImg($text, $img_file){
	$img = @imagecreatetruecolor(800, 300) or die('Не установлена библиотека GD2');
	$bg = imagecolorallocate($img, 0xff, 0xff, 0xff);
	imagefill($img, 0, 0, $bg);
	$card = imagecreatefrompng($img_file);
	$card_size = getimagesize($img_file);

	$text_color = imagecolorallocate($img, 0x22, 0x31, 0x44);
	$font_file = realpath(__DIR__.'/fonts/b52.ttf');
	imagefttext($img, 24, 0, 50, 50, $text_color, $font_file, $text);
	imagecopy($img, $card, 800 - intval($card_size[0]), 300 - intval($card_size[1]), 0, 0, $card_size[0], $card_size[1]);
	imagepng($img);
}
// Функция принимает объект JSON и переводит его в ассоциативный массив
function jsonObjToArray($obj){
	$arr = array();
	foreach ($obj as $key => $value) {
		$arr[$key] = $value;
	}

	return $arr;
}
//Функция проверяет login и password пользователя на соответствие
function checkUserLogin($arr){
	return ($arr['login'] === $_POST['login'] && $arr['password'] === $_POST['password']) ? $_SESSION['login'] = $arr['login'] : null;
}

function saveJSON($arr, $file_name){
	$json = json_encode($arr);
	$fp = fopen($file_name, "w+");
	if(fwrite($fp, $json)) return true;
	fclose($fp);

	return false;
}

function isUser(){
	return isset($_SESSION['login']);
}

function isGuest(){
	return isset($_COOKIE['guest']);
}
// Структурированный вывод массива или объекта
function printDump($tmp){
	echo "<pre>";
		print_r($tmp);
	echo "</pre>";
}