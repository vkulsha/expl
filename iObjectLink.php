<table>
	<tr>
		<td>
			<table id="pClassAdd" style="position:absolute; text-align:left; background-color:#fffff0;" hidden>
				<tr>
					<td><div id="jstree"></div></td>
					<td valign="top"><table id='lQuery'></table></td>
				</tr>
			</table>
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
	var jstree = document.getElementById("jstree");
	var pClassAdd = document.getElementById("pClassAdd");
	var lQuery = document.getElementById("lQuery");

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
	var jsTable = new JsObjTable(query, opts, container);
	
	addMapButton2Table(jsTable, 0);
	
	but1 = document.createElement("BUTTON");
	but1.innerHTML = "class++";
	but1.onclick = function(){
		pClassAdd.hidden = !pClassAdd.hidden;
		
	}
	addToTable(jsTable, but1);
	
	pClassAdd.style.border = "1px solid #ccc";
	pClassAdd.style.left = windowWidth()/2 - 100;
	pClassAdd.style.top = 250;

	var arrQuery = {query:[], parent:[]};
	
	var reload = function(){
		var sel = objectlink.getTableQuery(arrQuery.query);
		jsTable.querySelect.set(sel);
		
	}
	
	var getClearQuery = function(q){
		var newQ = {query:[], parent:[], oldInd:[]};
		for (var i=0; i < q.parent.length; i++){
			if (q.parent[i]) {
				newQ.parent.push(q.parent[i]);
				newQ.query.push(q.query[i]);
				newQ.oldInd.push(i);
			}
		}
		
		for (var i=0; i < newQ.oldInd.length; i++){
			var oldInd = newQ.oldInd[i];
			for (var j=0; j < newQ.query.length; j++){
				var parentCol = newQ.query[j].parentCol;
				if (parentCol == oldInd) {
					newQ.query[j].parentCol = i;
				}
			}
		}
		return newQ;
	}
	
///classes
	$(function () {
		var data = orm(objectlink.gCQ()+" and o2 <1399 ", "rows2object");
		$('#jstree').jstree( 
			{
				'core' : { 'data' : data }	//,"checkbox" : { "keep_selected_style" : false } //,"plugins" : [ "wholerow", "checkbox" ] 
			}
		);
		
		$('#jstree').on("changed.jstree", function (e, data) {
			var node = data.node;
			var selectedN = node.text;
			var selectedId = node.id;
			var selectedPid = node.parent;
			var selectedChildren = node.children;

			var linkParent = false;
			var parent = arrQuery.parent.indexOf(selectedPid);
			for (var i=0; i < selectedChildren.length; i++){
				var ind = arrQuery.parent.indexOf(selectedChildren[i]);
				if (ind >= 0) {
					parent = ind;
					linkParent = true;
				}
			}
			arrQuery.parent.push(selectedId);
			arrQuery.query.push({"n":selectedN, "parentCol":~parent ? parent : undefined, "linkParent":linkParent});
			
			/*search with level link
			arrQuery.parent.push(selectedId);
			if (arrQuery.query.length == 0) {
				arrQuery.query.push({"n":selectedN, "parentCol": undefined, "linkParent":undefined});
			} else {
				objectlink.addClass2jsQuery(arrQuery.query, selectedId, 3);
			}
			*/
			reload();

			var tr = lQuery.appendChild(document.createElement("TR"));
			var td = tr.appendChild(document.createElement("TD"));
			var bt = td.appendChild(document.createElement("BUTTON"));
			bt.id = arrQuery.query.length-1;
			bt.tr = tr;
			bt.innerHTML = "(x) "+selectedN;
			bt.onclick = function(){
				arrQuery.query[this.id] = {};
				arrQuery.parent[this.id] = undefined;
				arrQuery = getClearQuery(arrQuery);
				this.tr.hidden = true;
				reload();
			}
		});
	});
	
</script>
