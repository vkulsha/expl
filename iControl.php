<table>
	<tr>
		<td align="center">Контроль исполнения</td>
	</tr>
	<tr>
		<td class="jsTableContainer">
		</td>
	</tr>
</table>

<script>
	var container = document.getElementsByClassName("jsTableContainer")[0];
	currentClass = "ControlNf";
	var query = {
		select:"select * from `expls"+currentClass+"` where 1=1", 
		order:" order by rowid "
	};
	var colors = [
		new RowColorMarker({
			color: "#ffffaa",
			conditions: [new FieldCondition({field: "status", compareType: "=", value: "На контроле", condType: "and"})]
		}),
		new RowColorMarker({
			color: "#aaffaa",
			conditions: [new FieldCondition({field: "status", compareType: "=", value: "Исполнено", condType: "and"})]
		}),
		new RowColorMarker({
			color: "#ffaaaa",
			conditions: [new FieldCondition({field: "status", compareType: "=", value: "Просрочено", condType: "and"})]
		}),
	];
	var colsOpts = [
		new Column({'id' : 0, 'name' : 'rowid', 'caption' : '№', 'width' : 40, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 1, 'name' : 'number', 'caption' : '№ (вх, исх)', 'width' : 100, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 2, 'name' : 'date', 'caption' : 'Дата', 'width' : 70, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 3, 'name' : 'summary', 'caption' : 'Краткое содержание, название, обект, адрес', 'width' : 300, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 4, 'name' : 'applicant', 'caption' : 'Заявитель', 'width' : 200, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 5, 'name' : 'responsible', 'caption' : 'Ответственный, соисполнители', 'width' : 220, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 6, 'name' : 'stage', 'caption' : 'Стадия исполнения, подтверждающие документы', 'width' : 300, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 7, 'name' : 'status', 'caption' : 'Статус', 'width' : 70, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 8, 'name' : 'cause', 'caption' : 'Причина неисполнения', 'width' : 200, 'visible' : true, 'class' : currentClass})
	];
	currentUser.classes[currentClass].columns = colsOpts;
	var tbHeight = windowHeight() * (380/699);
	var opts = {tableWidth:1200, tableHeight:tbHeight, columns: colsOpts, rowsColor: colors};
	var jsTable = new JsTable(query, opts, container);
		

</script>

