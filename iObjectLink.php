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
	var jsTable = new JsTable(query, opts, container);
	
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

	var arrQuery = {query:[], parent:[], status:[]};
	
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
			var linkParent = false;
			var selectedN = data.node.text;
			var selectedId = data.node.id;
			var selectedPid = data.node.parent;
			var selectedChildren = data.node.children;

			var parent = arrQuery.parent.indexOf(selectedPid);
			for (var i=0; i < selectedChildren.length; i++){
				var ind = arrQuery.parent.indexOf(selectedChildren[i]);
				if (ind >= 0) {
					parent = ind;
					linkParent = true;
				}
			}
			console.log(parent + " " + linkParent);
			arrQuery.parent.push(selectedId);
			arrQuery.status.push(true);	
			arrQuery.query.push({"n":selectedN, "parentCol":~parent ? parent : undefined, "linkParent":linkParent});
			var getQuery = function(arrQ, arrStatus){
				var ret = [];
				for (var i=0; i < arrQ.length; i++){
					if (arrStatus[i]){
						ret.push(arrQ[i]);
					}
				}
				return ret;
			}
			var sel = objectlink.getTableQuery(getQuery(arrQuery.query, arrQuery.status));
			jsTable.querySelect.set(sel);

			var tr = lQuery.appendChild(document.createElement("TR"));
			var td = tr.appendChild(document.createElement("TD"));
			var bt = td.appendChild(document.createElement("BUTTON"));
			bt.id = arrQuery.query.length-1;
			bt.tr = tr;
			bt.innerHTML = "(x) "+selectedN;
			bt.onclick = function(){
				arrQuery.status[this.id] = false;
				var sel = objectlink.getTableQuery(getQuery(arrQuery.query, arrQuery.status));
				jsTable.querySelect.set(sel);
				this.tr.hidden = true;
				
				
			}
			
		});
	});
	
</script>
