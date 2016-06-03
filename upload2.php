<?php
require('conn.php');
header('Content-Type: text/html; charset=utf-8');
$uploaddir = $_POST['uploadPath'];
$uploadid = $_POST['uploadId'];
mkdir($uploaddir);

$typePhoto = ["image/jpeg", "image/png"];

$files = array(
	"photo" => [],
	"other" => []
);

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
		$files["photo"][] = $uploadfile;
	} else {
		$files["other"][] = $uploadfile;
	};
	
}
//var_dump($files);
//print $_FILES['userfile']['type'][$i];// image/jpeg image/png application/pdf

for($i=0; $i<count(files["photo"]); $i++){
	$query = "insert into object (n) select ".files["photo"][$i]."; select max(id) from object;"
	$oid = $explDb->exec($query);

	$query = "insert into link (o1, o2) select ".$oid.", ".$uploadid;
	$result = $explDb->exec($query);
	
	$query = "select id from object where n='Файл' ";
	$cid = $explDb->exec($query);
	$query = "insert into link (o1, o2) select ".$oid.", ".$cid;
	$result = $explDb->exec($query);
	
	$query = "select id from object where n='Фото' ";
	$cid = $explDb->exec($query);
	$query = "insert into link (o1, o2) select ".$oid.", ".$cid;
	$result = $explDb->exec($query);
	
}

for($i=0; $i<count(files["other"]); $i++){
	$query = "insert into object (n) select ".files["other"][$i]."; select max(id) from object;"
	$oid = $explDb->exec($query);

	$query = "insert into link (o1, o2) select ".$oid.", ".$uploadid;
	$result = $explDb->exec($query);
	
	$query = "select id from object where n='Файл' ";
	$cid = $explDb->exec($query);
	$query = "insert into link (o1, o2) select ".$oid.", ".$cid;
	$result = $explDb->exec($query);
	
}

//header('Location: ' . $_SERVER['HTTP_REFERER']);

?>