<table>
	<tr>
		<td>
			<div id="jstree" style="position:absolute; text-align:left; background-color:#fffff0"></div>
		</td>
	</tr>
	<tr>
		<td align="center" id="labelComment">ObjectLinkMobile</td>
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
		select:"select * from objectlinkall where 1=1", 
		where:" and (o2 = 1 or o2 is null) ",
		order:" order by c desc, o1 "
	};
	var colors = [
		new RowColorMarker({
			color: "#f8f1da",
			conditions: [new FieldCondition({field: "c", compareType: "=", value: "класс", condType: "and"})]
		}),
	];
	var fs = (windowHeight()*(20/windowHeight()))+"px";
	var colsOpts = [
		new Column({'id' : 0, 'name' : 'o1', 'caption' : 'o1',   'width' : 40,  'visible' : false, 'class' : currentClass}),
		new Column({'id' : 1, 'name' : 'n',  'caption' : 'name', 'width' : windowWidth()-80,  'visible' : true,  'class' : currentClass, 'fontSize' : fs}),
		new Column({'id' : 2, 'name' : 'o2', 'caption' : 'o2',   'width' : 70,  'visible' : false, 'class' : currentClass}),
		new Column({'id' : 3, 'name' : 'c',  'caption' : 'c',    'width' : 150, 'visible' : false, 'class' : currentClass}),
	];
	currentUser.classes[currentClass].columns = colsOpts;
	var tbHeight = windowHeight() * (380/699);
	var opts = {tableWidth : windowWidth()-50, tableHeight:tbHeight, columns: colsOpts, rowsColor: colors};
	var jsTable = new JsTable(query, opts, container);
	
	addMapButton2Table(jsTable, 0);
	
	var oid1 = "";
	var oid2 = "";
	var oid = "";

	var cellClickFunc = function(){
		var id = jsTable.rows.get()[jsTable.selectedCell.get().row][0];
		if (oid1 == ""){
			oid1 = id
		} else {
			if (oid2 == ""){
				oid2 = id;
			} else {
				oid1 = id;
				oid2 = "";
			}
		}
		oid = (oid2 || oid1);
		
		jsTable.queryWhere.set(" and o2 = "+oid);
		
	};
	jsTable.cellClickFunc.set(cellClickFunc);
	
	var dom = document.createElement("BUTTON");
	dom.innerHTML = "Home";
	dom.onclick = function(){
		jsTable.queryWhere.set(" and (o2 = 1 or o2 is null) ");
	}
	addToTable(jsTable, dom);

	var dom = document.createElement("BUTTON");
	dom.innerHTML = "cO";
	dom.onclick = function(){
		result = prompt("cO(n); cL(o1,"+oid+")", undefined);
		if (result) {
			var o1 = objectlink.cO(result);
			if (oid) objectlink.cL(o1, oid);
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
		result = prompt("eO (id)", oid);
		if (result) {
			objectlink.eO(result);
			alert("Удален объект: "+result);
		} else {
			alert("Недопустимое значение id!");
		}
	}
	addToTable(jsTable, dom);	

</script>
