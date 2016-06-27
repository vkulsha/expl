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
		<script src="js/Column.js"></script>
		<script src="js/uService.js"></script>
		<script src="js/GetSet.js"></script>
		<script src="js/Filter.js"></script>
		<script src="js/JsTable.js"></script>
		<script src="js/objectlink.js"></script></head>
		<script src="js/JsObjTable.js"></script>

		<script>
			location.href = "expl.html";
			var classes_ = objectlink.gOrm("gAnd",[[1],"n,id"]);
			var classes = hash4arr(classes_);

			var key = $_GET("key");
			var userKey = key || objectlink.getObjectFromClass("Ключи доступа пользователей", "undefined") || 0;
			var userId = objectlink.gAND([userKey, classes["Пользователи"]]);
			if (userId.length){
				userId = userId[0];
				var mainInterface = getMainInterfaceKey(userId);
				var curInterface = $_GET(interfaceUrlKey);
				
				if (curInterface && key){
				} else {
					curInterface = mainInterface;
				}
				if (curInterface) {
					$(document).ready(function() {
						var iGoHome = gDom("iGoHome");
						iGoHome.setAttribute("href", "?interface="+(mainInterface ? mainInterface : "auth")+"&key="+userKey);

						var policy = showInterfaceElements(userId, curInterface);
						var mainContainer = gDom("mainContainer");
						if (!mainContainer.hidden) {
							getHttp(curInterface+".php"+location.search, function(data){
								$(mainContainer).append(data);
							}, true);
						}
					});
				}
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
			<td align="center" valign="middle" class="mainContainer" id="mainContainer" hidden>
			</td>
		</tr>
	</body>
</html>