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
	currentClass = "ObjectPower";
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
		new Column({'id' : 3, 'name' : 'name', 'caption' : 'Объект', 'width' : 150, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 4, 'name' : 'address', 'caption' : 'Адрес', 'width' : 300, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 5, 'name' : 'contract', 'caption' : 'Договор энергоснабжения', 'width' : 300, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 6, 'name' : 'agreement', 'caption' : 'Допсогл на ДУ №, дата', 'width' : 300, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 7, 'name' : 'maxAuthorizedPower', 'caption' : 'Макс разрешенная мощность кВт (по договору)', 'width' : 100, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 8, 'name' : 'maxConsumptionPower', 'caption' : 'Макс потребляемая мощность кВт', 'width' : 100, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 9, 'name' : 'powerConsumption', 'caption' : 'Портреб эл.энергии средн кВч.ч', 'width' : 100, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 10, 'name' : 'excess', 'caption' : 'Перевыставление в %', 'width' : 80, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 11, 'name' : 'powerPoint', 'caption' : 'Энергоснабжение объекта от ТП,ПЦ', 'width' : 200, 'visible' : true, 'class' : currentClass}),
		new Column({'id' : 12, 'name' : 'powerId', 'caption' : 'Ссылка на номер в Excel-файле', 'width' : 80, 'visible' : true, 'class' : currentClass})
	];
	currentUser.classes[currentClass].columns = colsOpts;
	var tbHeight = windowHeight() * (380/699);
	var opts = {tableWidth:1200, tableHeight:tbHeight, columns: colsOpts, rowsColor: colors};
	var jsTable = new JsTable(query, opts, container);
		
	addMapButton2Table(jsTable, 0);
	
		
</script>