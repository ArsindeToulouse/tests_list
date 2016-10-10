<?php
session_start();

header ("Content-Type: text/html; charset=utf-8");
require_once 'utils.php';
error_reporting(E_ALL);
date_default_timezone_set('Europe/Moscow');


if (isset($_GET['id'])) {

	$list = getListTests();
	$file_name = UPLOADS.$_GET['id'].".json";
	if(file_exists($file_name)) unlink($file_name);

	if (!empty($list)) {
		$list = array_filter($list, function($k) {
    		return $k[0] !== $_GET['id'];
		});
	}

	$tmp = array();
	for($i = 0; $i < count($list); $i++){
		$tmp[] = implode(':',$list[$i]);
	}
	$list_str = implode(";", $tmp);
	printDump($list_str);
	$msg = replaceListFile($list_str) ? true : false;

	header('Location: list_of_tests.php?msg='.$msg);
}