<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<title>III</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<script src="js/jquery-2.2.0.min.js"></script>
	<script src="js/domtree.js"></script>
	<script src="js/d3.min.js"></script>
	<script src="js/leaflet.js"></script>		
	<script src="js/jstree/jstree.min.js"></script>

	<script>
	<?php
		$interfaceUrlKey = "interface";
		$objectIdUrlKey = "objectId";
		$clientIp = $_SERVER['REMOTE_ADDR'];
		$host = $_SERVER['SERVER_NAME'];
		echo "
			var domain = 'http://$host/';
			var interfaceUrlKey = '$interfaceUrlKey';
			var objectIdUrlKey = '$objectIdUrlKey';
			var _clientIp = '$clientIp';
			var sessionLogin = '';
		";
	?>
	</script>
	<script src="js/Column.js"></script>
	<script src="js/uService.js"></script>
	<script src="js/GetSet.js"></script>
	<script src="js/Filter.js"></script>
	<script src="js/JsTable.js"></script></head>
	<script src="js/uodb.js"></script></head>
<body>

<table id="tbData" width="100%">
	<tr>
		<td style='background-color:#eee'>
			<table>
				<tr><td>
					<button id="bHome">/</button>
					<button id="bCO">cO</button>
					<button id="bCL">cL</button>
					<button id="bEO">eO</button>
					<button id="bEL">eL</button>
					&nbsp;
					<button id="bFontSizePlus">+</button>
					<button id="bOrderBy">n</button>
					<button id="bEdit">[..]</button>
					<button id="bTable">t</button>
					<br><label id="lCount" style='font-size:10px'>0</label>
				</td></tr>
				<tr><td>
					<input type='checkbox' id='link'/>
					<label id='stat' style='font-size:10px'>...</label>
				</td></tr>
				<tr><td><table id='edit' width='100%' border=0 hidden><tr>
					<td width='100%'><textarea id='txt' rows=2 style='width:100%'></textarea></td>
					<td><button id='bSave'>ok</button></td>
				</tr></table></td></tr>
			</table>
		</td>
	</tr>
	<tr><td id='table' hidden></td></tr>
	<tr><td><div style='overflow-y:auto' id='divContainer'><table id='dataContainer' cellpadding=1 style='width:100%'></table></div></td></tr>
</table>

<script>
	var fs = 12;
	var oid1 = "";
	var oid2 = "";
	var n1 = "";
	var n2 = "";
	var oid = "1";
	var arrQuery = [];
	var stat = document.getElementById("stat");
	var txt = document.getElementById("txt");
	var bSave = document.getElementById("bSave");
	var link = document.getElementById("link");
	var lCount = document.getElementById("lCount");
	var dom = document.getElementById("dataContainer");
	var divContainer = document.getElementById("divContainer");
	var bEdit = document.getElementById("bEdit");
	var bTable = document.getElementById("bTable");
	var table = document.getElementById("table");
	var ORDER = " order by c desc, o1 ";
	var order = ORDER;
	var query = "select * from ( \n"+
			"	select distinct link.o1, object.n, link.o2, case when class.o2 is not null then 'Класс' end c from ( \n"+
			"		select o1, o2 from link union all select o2, o1 from link \n"+
			"	)link \n"+
			"	join object on object.id = link.o1 \n"+
			"	left join link class on class.o1 = link.o1 and class.o2 in (select id from object where n='Класс') \n"+
			")xxx where 1=1 \n";
	
	divContainer.style.height = windowHeight()-95+"px";
	
	bTable.onclick = function(){
		table.hidden = !table.hidden;
	}
	
	if (location.hash == "#id"+oid) {
		hashchange();
	} else {
		location.href = "#id"+oid;
	}
	
	function load(where_, order_){
		var result = orm(query+where_+" "+order_, "all2array");
		var data = result;
		if (!data.length) return false;
		
		dom.innerHTML = "";
		var countClass = 0;
		var countObjects = 0;
		var countAll = data.length;
		for (var i=0; i < data.length; i++){
			var cell = document.createElement("BUTTON");
			cell.innerHTML = data[i][1];
			cell.id = data[i][0];
			cell.style.fontSize = fs+"px";
			cell.style.width = "100%";
			cell.style.textAlign = "left";
			cell.style.fontWeight = data[i][3] ? "bold" : "";
			if (data[i][3]) { countClass++; } else { countObjects++; };

			cell.onclick = function(){
				location.href = "#id"+this.id;
			};
			
			bSave.onclick = function(){
				var oid = this.oid;
				var val = $(txt).val();
				objectlink.uO(oid, val);

			}
			
			
			var td = document.createElement("TD");
			var tr = document.createElement("TR");
			td.appendChild(cell);
			tr.appendChild(td);
			dom.appendChild(tr);	
			
		}
		lCount.innerHTML = countAll+"("+countClass+"/"+countObjects+")";

		return true;
		
	};

	function reload(){
		return load(" and o2 = "+oid, order);
	}
	
	function hashchange(){
		var hash = location.hash;
		hash = hash.split("#id")[1];

		oid = hash;
		var n = objectlink.gN(oid);
		$(txt).val(n);

		if (link.checked){
			oid2 = oid;
			n2 = n;
		} else {
			oid1 = oid;
			n1 = n;
		}
		bSave.oid = oid;
		stat.innerHTML = "oid1: "+oid1+" ("+n1+"), oid2: "+oid2+" ("+n2+")";
		reload();
		
		if (!table.hidden){
			arrQuery.push({"n":n});
			
			var query = objectlink.getTableQuery(arrQuery);
			var domtable = orm(query, "all2domtable");
			domtable.setAttribute("border",1);
			table.innerHTML = "";
			table.appendChild(domtable);
		}
		
	}
	
	window.onhashchange = function(){
		hashchange();
	}

	function resize(){
		var arr = document.getElementsByTagName("BUTTON");
		for (var i=0; i < arr.length; i++){
			arr[i].style.fontSize = fs;
		}
	}
	
	bFontSizePlus = document.getElementById("bFontSizePlus");
	bFontSizePlus.onclick = function(){
		resize(++fs);
	};

	bOrderBy = document.getElementById("bOrderBy");
	bOrderBy.onclick = function(){
		this.innerHTML = order == ORDER ? "id" : "n";
		order = order == ORDER ? " order by c desc, n " : ORDER;
		reload();
	};

	var bHome = document.getElementById("bHome");
	function goHome(){
		oid = "1";
		location.href = "#id"+oid;
		
	}
	bHome.onclick = function(){
		goHome();
	}

	bEdit.onclick = function(){
		document.getElementById("edit").hidden = !document.getElementById("edit").hidden;
		divContainer.style.height = windowHeight()-(document.getElementById("edit").hidden ? 95 : 135)+"px";
		
	}

	var bCO = document.getElementById("bCO");
	bCO.onclick = function(){
		result = prompt("cO(n); cL(o1,"+oid+")", undefined);
		if (result) {
			var o1 = objectlink.cO(result);
			if (oid) objectlink.cL(o1, oid);
			reload();
		} else {
			alert("Недопустимое значение объекта!");
		}
	}

	var bCL = document.getElementById("bCL");
	bCL.onclick = function(){
		result = prompt("cL (id,id)", oid1+","+oid2);
		if (result) {
			var arr = result.split(",");
			if (arr && arr.length && arr[0] && arr[1] && arr[0] != arr[1]) {
				objectlink.cL(arr[0],arr[1]);
				reload();
			} else {
				alert("Недопустимое значение oid1 или oid2!");
			}
			
		} else {
			alert("Недопустимое значение oid1 или oid2!");
		}
	}

	var bEO = document.getElementById("bEO");
	bEO.onclick = function(){
		result = prompt("eO (id)", oid);
		if (result) {
			objectlink.eO(result);
			oid1 = "1";
			n1 = "Класс";
			goHome();
		} else {
			alert("Недопустимое значение id!");
		}
	}

	var bEL = document.getElementById("bEL");
	bEL.onclick = function(){
		result = prompt("eL (id,id)", oid1+","+oid2);
		if (result) {
			var arr = result.split(",");
			if (arr && arr.length && arr[0] && arr[1] && arr[0] != arr[1]) {
				objectlink.eL(arr[0],arr[1]);
				reload();
			} else {
				alert("Недопустимое значение oid1 или oid2!");
			}
			
		} else {
			alert("Недопустимое значение oid1 или oid2!");
		}
	}

</script>
</body>
</html>
