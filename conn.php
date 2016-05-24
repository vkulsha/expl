<?php
header('Content-Type: text/html; charset=utf-8');

$constr = "";
$login = "";
$pass = "";
if ($_SERVER['SERVER_NAME'] == "kulsha.ru") {
	$constr	= "mysql:host=kulsha.ru;port=3306;dbname=c5553_expl;charset=utf8";
	//$constr	= "pgsql:host=kulsha.ru;port=5432;dbname=c5553_expl";
	$login = "c5553_root";
	$pass = "Rekmif1983";
} else {
	$constr	= "mysql:host=localhost;port=3306;dbname=expl;charset=utf8";
	//$constr	= "pgsql:host=localhost;port=5432;dbname=expl";
	$login = "root";
	$pass = "Rekmif1983";
};

$explDb = new PDO($constr, $login, $pass);
$explDb -> exec("set names utf8");
$explDbType = $explDb->getAttribute(PDO::ATTR_DRIVER_NAME);
