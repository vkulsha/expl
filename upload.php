<?php
header('Content-Type: text/html; charset=utf-8');
$uploaddir = $_POST['uploadPath'];
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], mb_convert_encoding($uploadfile,  "cp1251", "UTF-8"))) {
    echo "Файл корректен и был успешно загружен.\n";
	header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
	echo $_FILES['userfile']['tmp_name'];
    echo "Ощибка загрузки файла!\n\n";
	echo 'Отладочная информация:';
	print_r($_FILES);

}
print "</pre>";

?>