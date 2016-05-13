<script>
	var arrInterfaces = getMainMenuJson();
	var arr2matrix = lineArray2matrixArray(arrInterfaces, 2, 2);
	var menuHtml = iMainMenu(arr2matrix);
	//if (isMobile.any()){
	//} else {
		$(".mainContainer").append(menuHtml);
	//}
	
</script>