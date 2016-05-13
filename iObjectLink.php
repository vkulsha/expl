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
		<td align="center">
			<table style='border:1px solid #999'>
				<tr align="center"><td><input type='radio' id='ro1' name="link" checked></td><td><input type='radio' id='ro1' name="link"></td></tr>
				<tr align="center"><td>id1</td><td>id2</td></tr>
				<tr align="center"><td id="o1"></td><td id="o2"></td></tr>
			</table>
		</td>
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
	var colors;
	var colsOpts;
	currentUser.classes[currentClass].columns = colsOpts;
	var tbHeight = windowHeight() * (380/699);
	var opts = {tableWidth:1200, tableHeight:tbHeight, columns: colsOpts, rowsColor: colors};
	var jsTable = new JsTable(query, opts, container);
	
	addMapButton2Table(jsTable, 0);
	
	var o1 = document.getElementById("o1");
	var o2 = document.getElementById("o2");
	var ro1 = document.getElementById("ro1");
	var ro2 = document.getElementById("ro2");
	var oid1 = "";
	var oid2 = "";

	var ro1ro2 = function(id){
		if (ro1.checked) {
			oid1 = id;
			o1.innerHTML = id;
		} else {
			oid2 = id;
			o2.innerHTML = id;
		}
		
	}

	var cellClickFunc = function(){
		var id = jsTable.rows.get()[jsTable.selectedCell.get().row][0];
		ro1ro2(id);
	};
	jsTable.cellClickFunc.set(cellClickFunc);
	
	var dom = document.createElement("BUTTON");
	dom.innerHTML = "cO";
	dom.onclick = function(){
		result = prompt("cO(n)", undefined);
		if (result) {
			objectlink.cO(result);
			
		} else {
			alert("Недопустимое значение объекта!");
		}
	}
	addToTable(jsTable, dom);

	var dom = document.createElement("BUTTON");
	dom.innerHTML = "cL";
	dom.onclick = function(){
		result = prompt("cL (id,id)", oid1+","+oid2);
		if (result) {
			var arr = result.split(",");
			if (arr && arr.length && arr[0] && arr[1] && arr[0] != arr[1]) {
				alert("Создана связь: "+objectlink.cL(arr[0],arr[1]));
			} else {
				alert("Недопустимое значение oid1 или oid2!");
			}
			
		} else {
			alert("Недопустимое значение oid1 или oid2!");
		}
	}
	addToTable(jsTable, dom);

	var dom = document.createElement("BUTTON");
	dom.innerHTML = "eO";
	dom.onclick = function(){
		result = prompt("eO (id)", oid1);
		if (result) {
			objectlink.eO(result);
			alert("Удален объект: "+result);
		} else {
			alert("Недопустимое значение id!");
		}
	}
	addToTable(jsTable, dom);
	
///classes
	$(function () {
		var data = getOrmObject(objectlink.gC().result, "rows2object");
		$('#jstree').jstree( 
			{
				'core' : { 'data' : data }	//,"checkbox" : { "keep_selected_style" : false } //,"plugins" : [ "wholerow", "checkbox" ] 
			}
		);
		$('#jstree').on("changed.jstree", function (e, data) {
			var selectedClasses = data.selected;
			jsTable.queryWhere.set(" and o2 in ("+selectedClasses.join(",")+")");

			var id = selectedClasses[0];
			ro1ro2(id);
		});
	});
	

</script>
