<table width='100%' height='100%' border='0'>
	<tr>
		<td valign='top'>
			<table width='100%' height='100%'>
				<tr valign='top'>
					<td width='50%' style='background-color:#e8e1ca' id='container'>
						<img id='imageContainer'/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<script>
	var oid = 115;
	var container = gDom("container");
	var img = gDom("imageContainer");
	var imagePath = "data/objects/21/plan.png";
	img.src = imagePath;
	
	var oidCoord = oid;
	arrCoord = [];
	arrCoordDom = [];
	img.onclick = function(e){
		var contCoords = this.getBoundingClientRect();
		var contInnerCoords = {
			top: contCoords.top + this.clientTop,
			left: contCoords.left + this.clientLeft
		};

		var x = e.clientX - contInnerCoords.left;
		var y = e.clientY - contInnerCoords.top;
		var dom = cDom("INPUT");

		arrCoord.push(x + "," + y);
		arrCoordDom.push(dom);
		
		dom.setAttribute("type", "radio");
		dom.checked = true;
		container.appendChild(dom);
		dom.style.position = "absolute";
		dom.style.top = e.clientY-7;
		dom.style.left = e.clientX-10;

		dom.ondblclick = function(e){
			if (confirm("Создать объект-схему из координат для "+oidCoord+"?")){
				createCoordObject(arrCoord, oidCoord);
			};
			for (var i=0; i < arrCoordDom.length; i++){
				arrCoordDom[i].remove();
			}
			arrCoord = [];
		}
	}
	function createCoordObject(arr, pid){
		val = arr.join(" ");
		if (val && pid) {
			var oid = objectlink.cO(val, pid);
		}
		
		var oidShemaClass = objectlink.gO("Векторные схемы объектов");
		if (oidShemaClass) {
			objectlink.cL(oid, oidShemaClass);
		}
	}

	var userKey = objectlink.getObjectFromClass('Пользователи','undefined');
	console.log(getInterfacesAccess(userKey, 'просмотр'));
	
/*	
/////////////CANVAS
	$(container2).append("<canvas height='500' width='1000' id='canvas'>Обновите браузер</canvas>");
	var canvas = document.getElementById("canvas"),
    ctx = canvas.getContext('2d');

	var objects = objectlink.getlinkedObjects(oid, "Векторные схемы объектов");
	for (var i=0; i < objects.length; i++){
		var val = objects[i][1];
		var arr = val.split(" ");
		ctx.beginPath();
		
		for (var j=0; j < arr.length; j++){
			var point = arr[j].split(",");
			if (j == 0){
				ctx.moveTo(point[0], point[1]);
			} else {
				ctx.lineTo(point[0], point[1]);
			}
		}
		ctx.closePath();
		ctx.stroke();
	}
//////////////	
*/	
	
</script>