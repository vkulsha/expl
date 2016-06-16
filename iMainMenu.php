<script>
	var arrInterfaces = objectlink.gOrm("gT",[["Главное меню","Ключи интерфейсов","Файлы"]]);
	arrInterfaces = getOrmObject({columns:["id Главное меню","Главное меню","id Ключи интерфейсов","Ключи интерфейсов","id Файлы","Файлы"],data:arrInterfaces},"rows2object");
	var arr2matrix = lineArray2matrixArray(arrInterfaces, 1, 2);
	var menuHtml = iMainMenu(arr2matrix);
	//if (isMobile.any()){
	//} else {
		$(".mainContainer").append(menuHtml);
	//}
	var policy = showInterfaceElements(userId, curInterface);
	
</script>