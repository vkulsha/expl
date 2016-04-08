<table>
	<tr>
		<td>
			<div id="jstree_demo_div" style="position:absolute; text-align:left; background-color:#fffff0"></div>
		</td>
	</tr>
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
		select:"select * from objectlink where 1=1", 
		where:" and o2 is null ",
		order:" order by id "
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
		new Column({'id' : 0, 'name' : 'id', 'caption' : '№', 			'width' : 40, 	'visible' : true, 'class' : currentClass}),
		new Column({'id' : 1, 'name' : 'n',  'caption' : 'Наменование', 'width' : 500, 	'visible' : true, 'class' : currentClass}),
		new Column({'id' : 2, 'name' : 'o2', 'caption' : 'Связь', 		'width' : 40, 	'visible' : true, 'class' : currentClass}),
	];
	currentUser.classes[currentClass].columns = colsOpts;
	var tbHeight = windowHeight() * (380/699);
	var opts = {tableWidth:1200, tableHeight:tbHeight, columns: colsOpts, rowsColor: colors};
	var jsTable = new JsTable(query, opts, container);
	
	addMapButton2Table(jsTable, 0);
	
///classes
	$(function () {
		var data = getOrmObject(objectlink.gC().result, "rows2object");
		$('#jstree_demo_div').jstree( 
			{
				'core' : { 'data' : data }	//,"checkbox" : { "keep_selected_style" : false } //,"plugins" : [ "wholerow", "checkbox" ] 
			}
		);
		$('#jstree_demo_div').on("changed.jstree", function (e, data) {
			jsTable.queryWhere.set(" and o2 in ("+data.selected.join(",")+")");
		});
	});
	
</script>
