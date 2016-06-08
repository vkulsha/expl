<script>
	//var arrInterfaces = getMainMenuJson();
	var sel = objectlink.getTableQuery([
		{n:"Главное меню"},//0
		{n:"Ключи интерфейсов"},//1
		{n:"Файлы"},//2
		{n:"classes", linkParent:true},//3
	]);
	var arrInterfaces = orm(sel, "rows2object");
	var arr2matrix = lineArray2matrixArray(arrInterfaces, 1, 2);
	var menuHtml = iMainMenu(arr2matrix);
	//if (isMobile.any()){
	//} else {
		$(".mainContainer").append(menuHtml);
	//}
	
</script>