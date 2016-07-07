<?php
require('DB.php');
require('ObjectLink.php');
require('SQL.php');
header('Content-Type: text/html; charset=utf-8');

function getDbPrefix($arr, $host){
	return isset($arr[$host]) ? $arr[$host] : "";
	
};

$arr = array(
	"kulsha.ru" => "c5553_",
	"explguov.ru" => "ih162624_",
	"185.117.155.80" => "",
	"localhost" => ""
);

$host = $_SERVER['SERVER_NAME'];
$prefix = getDbPrefix($arr, $host);
$conn = new DB("localhost",$prefix."expl",$prefix."root","Rekmif1983",0);;
$explDb = $conn->db;
$explDbType = $explDb->getAttribute(PDO::ATTR_DRIVER_NAME);
$sql = new SQL($explDb);
$objectlink = new ObjectLink($sql);
