<?php
require('conn.php');
header('Content-Type: text/html; charset=utf-8');
$uploaddir = $_POST['uploadPath'];
$uploadid = $_POST['uploadId'];
mkdir($uploaddir);

$typePhoto = ["image/jpeg", "image/png"];

$files = [];

for($i=0; $i<count($_FILES['userfile']['name']); $i++){
	$uploadfile = $uploaddir . basename( $_FILES['userfile']['name'][$i] );
	$tempfile = $_FILES['userfile']['tmp_name'][$i];
	
	if (move_uploaded_file($tempfile, mb_convert_encoding($uploadfile,  "cp1251", "UTF-8"))) {
		echo $uploadfile." Файл корректен и был успешно загружен.\n";
	} else {
		echo $uploadfile." Ошибка загрузки файла!\n\n";
		echo 'Отладочная информация:';
		print_r($_FILES);

	}
	
	$filetype = $_FILES['userfile']['type'][$i];
	if (in_array($filetype, $typePhoto)){
		$files[]["photo"] = $uploadfile;
	} else {
		$files[]["other"] = $uploadfile;
	};
	
}
//print $_FILES['userfile']['type'][$i];// image/jpeg image/png application/pdf

$query = "select id from object where n='Файл' ";
$cidFile = $explDb->query($query, PDO::FETCH_NUM)->fetchAll(PDO::FETCH_NUM);
if ($cidFile) {	$cidFile = $cidFile[0][0]; } else { $cidFile = '1410'; };

$query = "select id from object where n='Фото' ";
$cidPhoto = $explDb->query($query, PDO::FETCH_NUM)->fetchAll(PDO::FETCH_NUM);
if ($cidPhoto) { $cidPhoto = $cidPhoto[0][0]; } else { $cidPhoto = '1423'; };

for($i=0; $i<count($files); $i++){
	$filename = "";
	if (array_key_exists("photo", $files[$i])) {
		$filename = $files[$i]["photo"];
	} else {
		$filename = $files[$i]["other"];
	};
	
	$query = "insert into object (n) values ('".$filename."')";
	$result = $explDb->exec($query);
	$query = "select max(id) from object";
	$oid = $explDb->query($query, PDO::FETCH_NUM)->fetchAll(PDO::FETCH_NUM);
	if ($oid) {
		$oid = $oid[0][0]; 
	
		$query = "insert into link (o1, o2) values ('".$oid."', '".$uploadid."')";
		$result = $explDb->exec($query);
		
		if ($cidFile) {	
			$query = "insert into link (o1, o2) values ('".$oid."', '".$cidFile."')";
			$result = $explDb->exec($query);
		};
		
		if ($cidPhoto && array_key_exists("photo", $files[$i])) {
			$query = "insert into link (o1, o2) values ('".$oid."', '".$cidPhoto."')";
			$result = $explDb->exec($query);
		}
	}
}

//header('Location: ' . $_SERVER['HTTP_REFERER']);

?>