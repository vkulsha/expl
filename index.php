<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		
		<link rel="stylesheet" href="css/leaflet.css" />
		<link rel="stylesheet" href="js/jstree/themes/default/style.min.css" />
		<link rel="stylesheet" type="text/css" href="css/jsTable.css">
		<link rel="stylesheet" type="text/css" href="css/expl.css">

		<script src="js/jquery-2.2.0.min.js"></script>
		<script src="js/domtree.js"></script>
		<script src="js/d3.min.js"></script>
		<script src="js/leaflet.js"></script>		
		<script src="js/jstree/jstree.min.js"></script>

		<script>
		<?php
			session_start();
			require('conn.php');
			$interfaceUrlKey = "interface";
			$objectIdUrlKey = "objectId";
			$clientIp = $_SERVER['REMOTE_ADDR'];
			$host = $_SERVER['SERVER_NAME'];
			echo "
				var host = '$host';
				var domain = 'http://$host/';
				var interfaceUrlKey = '$interfaceUrlKey';
				var objectIdUrlKey = '$objectIdUrlKey';
				var _clientIp = '$clientIp';
				var sessionLogin = '';
			";
			if (isset($_SESSION['auth'])) {
				echo "
					sessionLogin = '".$_SESSION['login']."';
					";
			}
		?>
		</script>
		<script src="js/Column.js"></script>
		<script src="js/uService.js"></script>
		<script src="js/GetSet.js"></script>
		<script src="js/Filter.js"></script>
		<script src="js/JsTable.js"></script>
		<script src="js/objectlink.js"></script></head>
		<script src="js/JsObjTable.js"></script>
		<script>
			$(document).keydown( function(event) {
				if (event.which == 17) isCtrl = true;
				if (event.which == 88 && isCtrl) {
					location.href = "auth.php?logout";
				}
			});
			
			$(document).keyup( function(event) {
				if (event.which == 17) isCtrl = false;
			});		
		</script>
	</head>
	<body>
		<table width="100%" height="100%" border="0">
		<tr>
			<td valign="top" colspan="2" class="highlight">
				<table cellpadding="10" align="center" class="highlight" width="100%">
					<tr>
						<td align="center"><a href="?interface=iMainMenu" class="highlight"><img width="70" src="/images/logo.png" id="logo"></a></td>
					</tr>
					<tr>
						<td><div id="mainCaption" style="font-size:14px" align="center">База Данных Управления Эксплуатации Имущества</div></td>
					</tr>
				</table>
			</td>
		<tr>
		<tr height="100%" width="100%">
			<td align="center" valign="middle" class="mainContainer">
			<!-- offset for debuging included php files
			
			
			
			
			
			
			
			
			
			
			
						
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			-->
			<?php 
				$link = "auth.php";
				if (isset($_SESSION['auth']) /*|| $_SERVER['SERVER_NAME'] == 'kulshavi.guss.ru' || $_SERVER['SERVER_NAME'] == 'localhost'*/) {
					$link = "iMainMenu.php";
					if (isset($_GET["interface"]) && file_exists($_GET["interface"].".php"))
						$link = $_GET["interface"].".php";
				}
				require $link; //line number "script.php" = line number "?interface=script" - 39
			?>
			</td>
		</tr>
	</body>
</html>