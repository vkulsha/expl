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
	move_uploaded_file($tempfile, mb_convert_encoding($uploadfile,  "cp1251", "UTF-8"));
	$filetype = $_FILES['userfile']['type'][$i];
	
	if (in_array($filetype, $typePhoto)){
		$files[]["photo"] = $uploadfile;
	} else {
		$files[]["other"] = $uploadfile;
	};
	
}

$cidFile = $objectlink->gO(["Файлы"]);
$cidPhoto = $objectlink->gO(["Фото"]);

for($i=0; $i<count($files); $i++){
	$filename = array_key_exists("photo", $files[$i]) ? $files[$i]["photo"] : $files[$i]["other"];
	$oid = $objectlink->cO([$filename, $uploadid]);
	
	if ($oid) {
		if ($cidFile) {	$objectlink->cL([$oid, $cidFile]); }
		if ($cidPhoto && array_key_exists("photo", $files[$i])) { $objectlink->cL([$oid, $cidPhoto]);	}
	}
}

//header('Location: ' . $_SERVER['HTTP_REFERER']);
header("location:javascript://history.go(-1)");

?>