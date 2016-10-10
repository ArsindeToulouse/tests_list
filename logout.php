<?php
session_start();

header ("Content-Type: text/html; charset=utf-8");
require_once 'utils.php';
error_reporting(E_ALL);
date_default_timezone_set('Europe/Moscow');

isUser()?session_destroy():setcookie('guest',$_COOKIE['guest'],time()-3600);
header('Location: index.php');