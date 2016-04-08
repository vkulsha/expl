<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<title>Expl Benchmark Test</title>
	<link rel="stylesheet" href="../css/qunit-1.22.0.css">

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		
		<link rel="stylesheet" href="../css/leaflet.css" />
		<link rel="stylesheet" type="text/css" href="../css/jsTable.css">
		<link rel="stylesheet" type="text/css" href="../css/expl.css">

		<script src="../js/jquery-2.2.0.min.js"></script>
		<script src="../js/domtree.js"></script>
		<script src="../js/d3.min.js"></script>
		<script src="../js/leaflet.js"></script>		

		<script>
		<?php
			$interfaceUrlKey = "interface";
			$objectIdUrlKey = "objectId";
			$clientIp = $_SERVER['REMOTE_ADDR'];
			echo "
				var interfaceUrlKey = '$interfaceUrlKey';
				var objectIdUrlKey = '$objectIdUrlKey';
				var _clientIp = '$clientIp';
			";
		?>
		</script>
		<script src="../js/Column.js"></script>
		<script src="../js/uService.js"></script>
		<script src="../js/GetSet.js"></script>
		<script src="../js/Filter.js"></script>
		<script src="../js/JsTable.js"></script></head>
		
		<script src="../js/lodash.js"></script>
		<script src="../js/platform.js"></script>
		<script src="../js/benchmark.js"></script>
		
<body>
<script src="bench.js">

</script>
</body>
</html>