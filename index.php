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
			echo "
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
			var userKey = $_GET("key");
			var userId = userKey || objectlink.getObjectFromClass('Пользователи',"undefined");
			var interfaces = getInterfacesAccess(userId, 'просмотр');
			var curInterface = $_GET(interfaceUrlKey);
			if (curInterface && ~interfaces.indexOf(curInterface)){
			} else {
				curInterface = interfaces[0];
			}
			if (curInterface) {
				$(document).ready(function() {
					var iGoHome = gDom("iGoHome");
					iGoHome.setAttribute("href", "?interface=iMainMenu&key="+userKey);
					var mainContainer = gDom("mainContainer");
					getHttp(curInterface+".php"+location.search, function(data){
						$(mainContainer).append(data);
					}, true);
				});
			}
		</script>

	</head>
	<body>
		<table width="100%" height="100%" border="0">
		<tr>
			<td valign="top" colspan="2" class="highlight">
				<table cellpadding="10" align="center" class="highlight" width="100%">
					<tr>
						<td align="center"><a href="#" id="iGoHome" class="highlight"><img width="70" src="/images/logo.png" id="logo"></a></td>
					</tr>
					<tr>
						<td><div id="mainCaption" style="font-size:14px" align="center">База Данных Управления Эксплуатации Имущества</div></td>
					</tr>
				</table>
			</td>
		<tr>
		<tr height="100%" width="100%">
			<td align="center" valign="middle" class="mainContainer" id="mainContainer">
			</td>
		</tr>
	</body>
</html>