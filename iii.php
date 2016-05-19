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
					<button id="bFontSizeMinus">-</button>
					<button id="bOrderByName">n</button>
					<button id="bOrderById">id</button>
					<button id="bEdit" onclick='document.getElementById("edit").hidden = !document.getElementById("edit").hidden'>#</button>
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
	<tr><td><table id='dataContainer' cellpadding=1></table></td></tr>
</table>

<script>
	var fs = 12;
	var oid1 = "";
	var oid2 = "";
	var n1 = "";
	var n2 = "";
	var oid = "";
	var stat = document.getElementById("stat");
	var txt = document.getElementById("txt");
	var bSave = document.getElementById("bSave");
	var link = document.getElementById("link");
	var lCount = document.getElementById("lCount");
	var order = " order by c desc, o1 ";
	var where = "and (o2 = 1 or o2 is null)";
	var query = "select * from ( \n"+
			"	select distinct link.o1, object.n, link.o2, case when class.o2 is not null then 'Класс' end c from ( \n"+
			"		select o1, o2 from link union all select o2, o1 from link \n"+
			"	)link \n"+
			"	join object on object.id = link.o1 \n"+
			"	left join link class on class.o1 = link.o1 and class.o2 in (select id from object where n='Класс') \n"+
			")xxx where 1=1 \n";
	
	var dom = document.getElementById("dataContainer");
	var load = function(where_, order_){
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
			cell.style.fontWeight = data[i][3] ? "bold" : "";
			if (data[i][3]) { countClass++; } else { countObjects++; };

			cell.onclick = function(){
				var n = this.innerHTML;
				$(txt).val(n);
				txt.select();
				document.execCommand("copy");
				this.focus();
				var id = this.id;
				if (link.checked){
					oid2 = id;
					n2 = n;
				} else {
					oid1 = id;
					n1 = n;
				}
				oid = oid1;
				bSave.oid = oid;
				stat.innerHTML = "oid1: "+oid1+" ("+n1+"), oid2: "+oid2+" ("+n2+")";
				where = " and o2 = "+id;
				reload();
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

	var reload = function(){
		return load(where, order);
	}
	
	reload();

	var resize = function(){
		var arr = document.getElementsByTagName("BUTTON");
		for (var i=0; i < arr.length; i++){
			arr[i].style.fontSize = fs;
		}
	}
	
	bFontSizePlus = document.getElementById("bFontSizePlus");
	bFontSizePlus.onclick = function(){
		resize(++fs);
	};
	bFontSizeMinus = document.getElementById("bFontSizeMinus");
	bFontSizeMinus.onclick = function(){
		resize(--fs);
	};

	bOrderByName = document.getElementById("bOrderByName");
	bOrderByName.onclick = function(){
		order = " order by c desc, n ";
		reload();
	};
	bOrderById = document.getElementById("bOrderById");
	bOrderById.onclick = function(){
		order = " order by c desc, o1 ";
		reload();
	};

	var bHome = document.getElementById("bHome");
	bHome.onclick = function(){
		where = "and (o2 = 1 or o2 is null)";
		reload();
	}

	var bCO = document.getElementById("bCO");
	bCO.onclick = function(){
		result = prompt("cO(n); cL(o1,"+oid+")", undefined);
		if (result) {
			var o1 = objectlink.cO(result);
			if (oid) objectlink.cL(o1, oid);
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
				alert("Создана связь: "+objectlink.cL(arr[0],arr[1]));
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
			alert("Удален объект: "+result);
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
				alert("Удалена связь: "+objectlink.eL(arr[0],arr[1]));
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
