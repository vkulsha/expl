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
	var query = {
		select:"select * from `expls"+currentClass+"` where 1=1", 
		order:" order by rowid "
	};
	var colors = [
/*		new RowColorMarker({
			color: "#ffeeee",
			conditions: [new FieldCondition({field: "tu", compareType: "=", value: "УЭИ", condType: "and"})]
		}),
		new RowColorMarker({
			color: "#eeeeff",
			conditions: [new FieldCondition({field: "tu", compareType: "=", value: "СЗТП", condType: "and"})]
		}),
		new RowColorMarker({
			color: "#eeffee",
			conditions: [new FieldCondition({field: "tu", compareType: "=", value: "СиДВ", condType: "and"})]
		}),*/
	];
	var colsOpts = [
		new Column({'id' : 0, 'name' : 'rowid', 'caption' : '№', 'width' : 40, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 1, 'name' : 'tu', 'caption' : 'ТУ', 'width' : 70, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 2, 'name' : 'ik', 'caption' : 'ИК', 'width' : 70, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 3, 'name' : 'manager', 'caption' : 'Ответственный', 'width' : 150, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 4, 'name' : 'name', 'caption' : 'Наименование', 'width' : 200, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 5, 'name' : 'address', 'caption' : 'Адрес', 'width' : 300, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 6, 'name' : 'comment', 'caption' : 'Комментарий', 'width' : 300, 'visible' : false, 'class' : currentClass}),
		new Column({'id' : 7, 'name' : 'cadastr', 'caption' : 'Кадастр номер', 'width' : 150, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 8, 'name' : 'lat', 'caption' : 'Широта', 'width' : 80, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 9, 'name' : 'lon', 'caption' : 'Долгота', 'width' : 80, 'visible' : true, 'class' : currentClass}),
	];
	currentUser.classes[currentClass].columns = colsOpts;
	var tbHeight = windowHeight() * (380/699);
	var opts = {tableWidth:1200, tableHeight:tbHeight, columns: colsOpts, rowsColor: colors};
	var jsTable = new JsTable(query, opts, container);
	
	addMapButton2Table(jsTable, 0);
	
</script>
