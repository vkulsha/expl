<table>
	<tr>
		<td>
			<div id="jstree" style="position:absolute; text-align:left; background-color:#fffff0"></div>
		</td>
	</tr>
	<tr>
		<td align="center" id="labelComment">ObjectLink</td>
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
		select:"",//"select 1,2, 3,4,5, 6,7,8, 9,10,11, 12,13,14, 15,16,17", 
		where:"",
		order:""
	};
	var colors;
	var colsOpts;
	currentUser.classes[currentClass].columns = colsOpts;
	var tbHeight = windowHeight() * (380/699);
	var opts = {tableWidth:1200, tableHeight:tbHeight, columns: colsOpts, rowsColor: colors};
	var jsTable = new JsTable(query, opts, container);
	
	addMapButton2Table(jsTable, 0);
	
	var arrQuery = [];
	var arrParent = [];
	
///classes
	$(function () {
		var data = orm(objectlink.gCQ()+" and o2 <1399 ", "rows2object");
//		console.log(objectlink.gCQ());
		$('#jstree').jstree( 
			{
				'core' : { 'data' : data }	//,"checkbox" : { "keep_selected_style" : false } //,"plugins" : [ "wholerow", "checkbox" ] 
			}
		);
		$('#jstree').on("changed.jstree", function (e, data) {
			//console.log(data);
			var selectedN = data.node.text;
			var selectedId = data.node.id;
			var selectedPid = data.node.parent;
			var parent = arrParent.indexOf(selectedPid);
			arrParent.push(selectedId);
			arrQuery.push({"n":selectedN, "parentCol":~parent ? parent : undefined});
			//console.log(arrQuery);
			var sel = objectlink.getTableQuery(arrQuery);
			jsTable.querySelect.set(sel);
		});
		$('#labelComment').on("click", function(){
			$('#jstree').get()[0].hidden = !$('#jstree').get()[0].hidden;
		})
	});
	
</script>
