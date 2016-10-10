<?php
require_once 'utils.php';
error_reporting(E_ALL);

$good = "Вы отлично справились с задачей.\nВаша оценка - 5";
$well = "Вы хорошо справились с задачей.\nВаша оценка - 4";
$bad = "Вам стоит повторить эту тему снова.\nВаша оценка - 3";
$zerro = "Увы, Вы не прошли тест. \nПопробуйте еще раз.";

header('Content-type: image/png');
if (isset($_GET['mark'])) {
	$mark = $_GET['mark'];
	$user_name = $_GET['name'];
	switch ($mark) {
		case 5:
			$text = $user_name."!\n".$good;
			$file = "images/7.png";
			break;
		case 4:
			$text = $user_name."!\n".$well;
			$file = "images/8.png";
			break;
		case 3:
			$text = $user_name."!\n".$bad;
			$file = "images/9.png";
			break;
		case 2:
			$text = $user_name.".\n".$zerro;
			$file = "images/13.png";
			break;
		default:
			break;
	}
	createImg($text, $file);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
</head>
<body>
</body>
</html>