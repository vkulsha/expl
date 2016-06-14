<?php
	require('conn.php');

	//$func = array($objectlink, $_GET["f"]);
	//$ret = $func(json_decode($_GET["p"]));

	//print(json_encode($ret, JSON_UNESCAPED_UNICODE));
	
	$a = $objectlink->gT([["Объект", "Земельные участки"],[],[],[],"*"," and `id Объект`=115"]);
	print(json_encode($a, JSON_UNESCAPED_UNICODE));
	//print($a[0]);

?>

