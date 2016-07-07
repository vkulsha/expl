<?php
require('DB.php');
require('ObjectLink.php');
require('SQL.php');
header('Content-Type: text/html; charset=utf-8');

$conn;
if ($_SERVER['SERVER_NAME'] == "kulsha.ru") {
	$conn = new DB("localhost","c5553_expl","c5553_root","Rekmif1983",0);
} elseif ($_SERVER['SERVER_NAME'] == "explguov.ru") {
	$conn = new DB("localhost","ih162624_expl","ih162624_root","Rekmif1983",0);
} elseif ($_SERVER['SERVER_NAME'] == "185.117.155.80") {
	$conn = new DB("localhost","expl","expl","Rekmif1983",0);
} else {
	$conn = new DB("localhost","expl","root","Rekmif1983",0);
};
$explDb = $conn->db;
$explDbType = $explDb->getAttribute(PDO::ATTR_DRIVER_NAME);
$sql = new SQL($explDb);
$objectlink = new ObjectLink($sql);
