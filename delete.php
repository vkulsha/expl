<?php
header('Content-Type: text/html; charset=utf-8');

$deletepath = $_POST['deletePath'];
$fnames = $_POST['file'];
if (isset($fnames) && isset($deletepath)) {
	$result = "";
	foreach ($fnames as $fname) {
		$path = $deletepath.basename(mb_convert_encoding($fname, "cp1251", "UTF-8"));
		try {
			unlink($path);
			$result .= '{name: "'.basename($fname).'", status: "ok"},';
		} catch(Exception $e) {
			$result .= '{name: "'.basename($fname).'", status: "fail"},';
		}
	}
	$result = "[".$result."]";
	echo $result;
}

?>