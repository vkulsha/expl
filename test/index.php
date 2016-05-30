<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<title>Expl QUnit Test</title>
	<link rel="stylesheet" href="../css/qunit-1.22.0.css">

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		
		<link rel="stylesheet" href="../css/leaflet.css" />
		<link rel="stylesheet" href="../js/jstree/themes/default/style.min.css" />
		<link rel="stylesheet" type="text/css" href="../css/jsTable.css">
		<link rel="stylesheet" type="text/css" href="../css/expl.css">

		<script src="../js/jquery-2.2.0.min.js"></script>
		<script src="../js/domtree.js"></script>
		<script src="../js/d3.min.js"></script>
		<script src="../js/leaflet.js"></script>		
		<script src="../js/jstree/jstree.min.js"></script>

		<script>
		<?php
			$interfaceUrlKey = "interface";
			$objectIdUrlKey = "objectId";
			$clientIp = $_SERVER['REMOTE_ADDR'];
			$host = $_SERVER['SERVER_NAME'];
			echo "
				var domain = 'http://$host/';
				var interfaceUrlKey = '$interfaceUrlKey';
				var objectIdUrlKey = '$objectIdUrlKey';
				var _clientIp = '$clientIp';
				var sessionLogin = '';
			";
		?>
		</script>
		<script src="../js/Column.js"></script>
		<script src="../js/uService.js"></script>
		<script src="../js/GetSet.js"></script>
		<script src="../js/Filter.js"></script>
		<script src="../js/JsTable.js"></script></head>
		<script src="../js/uodb.js"></script></head>
		<script src="../js/objectlink.js"></script></head>
		<script src="../js/JsObjTable.js"></script></head>
<body>
	<div id="qunit"></div>
	<div id="qunit-fixture"></div>
	<script src="../js/qunit-1.22.0.js"></script>
	<script src="test.js"></script>
</body>
</html>