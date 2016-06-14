<?php
	require('conn.php');
	
	if (isset($_GET['f'])) {
		$f = $_GET['f'];
		$p = $_GET['p'];
	} else if (isset($_POST['f'])) {
		$f = $_POST['f'];
		$p = $_POST['p'];
	};

	$func = array($objectlink, $f);
	$ret = $func(json_decode($p));
	echo json_encode($ret, JSON_UNESCAPED_UNICODE);

?>