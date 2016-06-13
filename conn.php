<?php
require('DB.php');
require('ObjectLink.php');
require('SQL.php');
header('Content-Type: text/html; charset=utf-8');

$conn;
if ($_SERVER['SERVER_NAME'] == "kulsha.ru") {
	$conn = new DB("kulsha.ru","c5553_expl","c5553_root","Rekmif1983",0);
} else {
	$conn = new DB("localhost","expl","root","Rekmif1983",0);
};
$explDb = $conn->db;
$explDbType = $explDb->getAttribute(PDO::ATTR_DRIVER_NAME);
$objectlink = new ObjectLink($conn->db);
//$sql = new SQL($conn->db);
