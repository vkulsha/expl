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
					<button id="bHome">Class</button>
					<button id="bCO">cO</button>
					<button id="bCL">cL</button>
					<button id="bEO">eO</button>
					&nbsp;
					<button id="bFontSizePlus">+</button>
					<button id="bFontSizeMinus">-</button>
					<button id="bOrderByName">by n</button>
					<button id="bOrderById">by id</button>
					<textarea id='txt' cols=1 rows=1 style='background-color:#eee;color:#eee;border: 0px;resize:none;overflow:hidden' ></textarea>
				</td></tr>
				<tr><td id='stat' style='font-size:10px'>...</td></tr>
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
	var order = " order by c desc, o1 ";
	var where = "and (o2 = 1 or o2 is null)";
	
	var dom = document.getElementById("dataContainer");
	var load = function(where_, order_){
		var result = orm("select * from objectlinkall where 1=1 "+where_+" "+order_, "all2array");
		var data = result;
		if (!data.length) return false;
		
		dom.innerHTML = "";
		for (var i=0; i < data.length; i++){
			var cell = document.createElement("BUTTON");
			cell.innerHTML = data[i][1];
			cell.id = data[i][0];
			cell.style.fontSize = fs+"px";
			cell.style.fontWeight = data[i][3] ? "bold" : "";

			cell.onclick = function(){
				var n = this.innerHTML
				txt.innerHTML = n;
				txt.select();
				document.execCommand("copy");
				this.focus();
				var id = this.id;
				if (oid1 == ""){
					oid1 = id;
					n1 = n;
				} else {
					if (oid2 == ""){
						oid2 = id;
						n2 = n;
					} else {
						oid1 = id;
						oid2 = "";
						n1 = n;
					}
				}
				oid = (oid2 || oid1);
				stat.innerHTML = "oid1: "+oid1+" ("+n1+"), oid2: "+oid2+" ("+n2+")";
				where = " and o2 = "+id;
				if (!reload()){
					var div = document.createElement("DIV");
					div.linkedButton = this;
					var memo = document.createElement("TEXTAREA");
					memo.innerHTML = n;
					var b = document.createElement("BUTTON");
					b.innerHTML = "ok";
					b.linkedMemo = memo;
					b.linkedDiv = div;
					div.appendChild(memo);
					div.appendChild(b);
					b.onclick = function(){
						var val = $(this.linkedMemo).val();
						objectlink.uO(id, val);
						this.linkedDiv.linkedButton.innerHTML = val;
						$(this.linkedDiv).replaceWith(this.linkedDiv.linkedButton);

					};
					$(this).replaceWith(div);
					
				}
			};
			
			var td = document.createElement("TD");
			var tr = document.createElement("TR");
			td.appendChild(cell);
			tr.appendChild(td);
			dom.appendChild(tr);	
			
		}
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

</script>
</body>
</html>
