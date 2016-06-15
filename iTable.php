<table>
	<tr>
		<td align="center">Объекты</td>
	</tr>
	<tr>
		<td class="jsTableContainer" id="divData">
		</td>
	</tr>
</table>

<script>
	var container = document.getElementsByClassName("jsTableContainer")[0];
	currentClass = "Object";

	var sel = objectlink.gOrm("gTq",[["Объект","Адрес","Кадастр","Широта","Долгота","Номер","ИК","ТУ","Ответственный"],[[7,6],[8,6]],[6,7],[],0]);
	sel = "select номер, ту, ик, ответственный, объект, адрес, кадастр, широта, долгота from ("+sel+")x where 1=1 ";
	var query = {
		select:sel,
		order:" order by ту desc, ик, case length(номер) when 1 then concat('00',номер) when 2 then concat('0',номер) else номер end "
	};
	var colors = [];
	var colsOpts = [
		new Column({'id' : 0, 'name' : 'номер', 'caption' : '№', 'width' : 40, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 1, 'name' : 'ту', 'caption' : 'ТУ', 'width' : 70, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 2, 'name' : 'ик', 'caption' : 'ИК', 'width' : 70, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 3, 'name' : 'ответственный', 'caption' : 'Ответственный', 'width' : 150, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 4, 'name' : 'объект', 'caption' : 'Наименование', 'width' : 200, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 5, 'name' : 'адрес', 'caption' : 'Адрес', 'width' : 300, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 6, 'name' : 'кадастр', 'caption' : 'Кадастр номер', 'width' : 150, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 7, 'name' : 'широта', 'caption' : 'Широта', 'width' : 80, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 8, 'name' : 'долгота', 'caption' : 'Долгота', 'width' : 80, 'visible' : true, 'class' : currentClass}),
	];
	//currentUser.classes[currentClass].columns = colsOpts;
	var tbHeight = windowHeight() * (380/699);
	var opts = {tableWidth:1200, tableHeight:tbHeight, columns: colsOpts, rowsColor: colors};
	var jsTable = new JsTable(query, opts, container);
	addMapButton2Table(jsTable, 0);

	var rows = jsTable.rows.get();
	var cellDblClickFunc = function(){
		bCard(jsTable.rows.get()[jsTable.selectedCell.get().row][0])
	};
	jsTable.cellDblClickFunc.set(cellDblClickFunc);

	
</script>
