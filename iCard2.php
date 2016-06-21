<div id="myModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <span class="close">&times;</span>
      <h3 id='modalTitle'></h2>
    </div>
    <div class="modal-body" id='modalBody'>
    </div>
    <div class="modal-footer" id='modalFooter'>
    </div>
  </div>
</div>
 
<table width='100%' height='100%' border='0'>
	<tr>
		<td valign='top'>
			<table width='100%' height='100%'>
				<tr valign='top'>
					<td width='50%' style='background-color:#e8e1ca'>
						<!--<table id='container' style='margin-left: 10px; margin-right: 10px; '></table>-->
						<table id='container'></table>
					</td>
					<td>
						<table id='container2'></table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<script>
	var objectId = $_GET(objectIdUrlKey);
	var oid = objectId;
	
	var modal = document.getElementById('myModal');
	var span = document.getElementsByClassName("close")[0];

	span.onclick = function() {
		modal.style.display = "none";
	}

	window.onclick = function(event) {
		if (event.target == modal) {
			modal.style.display = "none";
		}
	}
	
	window.onkeydown = function(event) {
		if (event.keyCode == 27) {
			modal.style.display = "none";
		}
	};

	function gB(val, isTxt){
		var tr = cDom("TR");
		tr.setAttribute("valign", "top");
		var td = tr.appendChild(cDom("TD"));
		var b = td.appendChild(!isTxt ? cDom("BUTTON", val) : cDom("LABEL", val));
		b.classList.add("card");
		b.style.width = "100%";
		b.style.textAlign = "left";
		return [tr,td,b];
	}
	
	function gObjects(cont, opts){
		cont.appendChild(gB(opts.caption ? opts.caption : opts.n2, true)[0]);
		var objects = objectlink.gOrm("gT",[[opts.n1, opts.n2], [],[],[],false, "*", "and `id "+opts.n1+"` = "+opts.id + (opts.n2 ? " order by `id "+opts.n2+"`" : "")]);
		for (var i=0; i < objects.length; i++){
			var n = objects[i][opts.n2 ? 3 : 1];
			var id = objects[i][opts.n2 ? 2 : 0];
			var b = gB((opts.isNum ? i+1+". " : "")+n);
			b[2].id = "b"+id;
			b[2].oid = id;
			b[2].n = n;
			b[2].c = opts.n2;
			cont.appendChild(b[0]);

			b[2].onmouseover = function(){
				var that = this;
				butmouseover(that);

				var svg = $("#svg"+this.oid).get()[0];
				if (svg) svgmouseover(svg);
			}

			b[2].onmouseout = function(){
				var that = this;
				butmouseout(that);

				var svg = $("#svg"+this.oid).get()[0];
				if (svg) svgmouseout(svg);
			}

			b[2].onclick = function(){
				var ver = getCardVersionByOid(this.oid);
				if (ver) {
					bCard(this.oid, ver);
				}
			}
		}
	}

	function svgmouseover(that){
			if (_fill) {
				that.setAttribute("fill", _fill);
			}
			if (_stroke) {
				that.setAttribute("stroke", _stroke);
			}
			that.style.cursor = "pointer";
		
	}

	function svgmouseout(that){
			that.setAttribute("fill", "transparent");
			that.setAttribute("stroke", "#7b5401");
			that.style.cursor = "auto";
		
	}

	function butmouseover(that){
			that.style.backgroundColor = "#d3b989";
		
	}

	function butmouseout(that){
			that.style.backgroundColor = "#f8f1da";
		
	}
	
	function createSVGobjects(svgCont, opts){
		var objects = objectlink.gOrm("gT",[["Объект", opts.n2, "Векторные схемы объектов"],[[2,1]],[],[],false, "*", "and `id Объект` = "+opts.oid1]);
		for (var i=0; i < objects.length; i++){
			var id = objects[i][2];//opts.n2
			var val = objects[i][5];//`Векторные схемы объектов`
			createSVGpolygon(svgCont, val, id, opts.fill, opts.funcClick, opts.caption ? i+1 : "", opts.stroke);
		}
	}
	
	var _fill;
	var _stroke;
	function createSVGpolygon(svgCont, points, id, fill, funcClick, caption, stroke){
		var el = svgCont
			.append("polygon")
			.attr("points", points)
			.attr("stroke-width", 1)
			.attr("stroke", "#7b5401")
			.attr("fill", "transparent");
			
		var el = el[0][0];
		el.id = "svg"+id;
		el.oid = id;
		el.caption = caption;

		_fill = fill;
		_stroke = stroke;
		
		el.onmouseover = function(){
			var that = this;
			svgmouseover(that);
			
			var but = $("#b"+this.oid).get()[0];
			if (but) butmouseover(but);
		};
		
		el.onmouseout = function(){
			var that = this;
			svgmouseout(that);
			
			var but = $("#b"+this.oid).get()[0];
			if (but) butmouseout(but);
		};
		
		el.onclick = funcClick;
		
		var arr = points.split(" ");
		var point = getCenterFromPoints(arr);
		var txt = svgCont
			.append("text")
			.attr("fill", "#7b5401")
			.attr("x", point[0]-5)
			.attr("y", point[1]+5);
		txt[0][0].innerHTML = el.caption;
		
		if (false){//pseudo-3D
			var arr2 = [];
			for (var i=0; i < arr.length; i++){
				var point = arr[i].split(",");
				var x1 = point[0];
				var y1 = point[1];
				var x2 = parseInt(x1) + 10;
				var y2 = parseInt(y1) - 10;
				arr2.push(x2+" "+y2);
				var el2 = svgCont
					.append("line")
					.attr("x1", x1)
					.attr("y1", y1)
					.attr("x2", x2)
					.attr("y2", y2)
					.attr("stroke-width", 1)
					.attr("stroke", "#7b5401")
					.attr("fill", "transparent");
				
			}
			var el2 = svgCont
				.append("polygon")
				.attr("points", arr2.join(" "))
				.attr("stroke-width", 1)
				.attr("stroke", "#7b5401")
				.attr("fill", "transparent");
			var el2 = el2[0][0];
			el2.id = "svg"+id;
			el2.oid = id;
			el2.onmouseover = el.onmouseover;
			el2.onmouseout = el.onmouseout;
			el2.onclick = el.onclick;
		}
	}
	
	var container = gDom("container");
	var container2 = gDom("container2");
	
//	gObjects(container, {n1:"Объект", id:oid, caption:"Общая информация по объекту:"});
//	$(container).append("<tr height='10'><td></td></tr>");
	gObjects(container, {n1:"Объект", n2:"Адрес", id:oid, caption:"Адрес объекта:"});
	
	$(container).append("<tr height='10'><td></td></tr>");
	gObjects(container, {n1:"Объект", n2:"Земельные участки", id:oid, caption:"Земельные участки:"});
	$(container).append("<tr height='10'><td></td></tr>");
	gObjects(container, {n1:"Объект", n2:"Здания и сооружения", id:oid/*, isNum:true*/, caption:"Состав имущества:"});
	$(container).append("<tr height='10'><td></td></tr>");

	funcbuttonclick = function(){
		var ver = "1424";
		if (ver) {
			bCard("1441", ver);
		}
	}

	funcbuttonclick2 = function(){
		var ver = "1425";
		if (ver) {
			bCard("1426", ver);
		}
	}

	var b = gB("Наличие коммуникаций", true)[0];
	container.appendChild(b);
	
	var b = gB("Электроснабжение", false)[0];
	b.onclick = funcbuttonclick;
	container.appendChild(b);

	var b = gB("ХВС", false)[0];
	b.onclick = funcbuttonclick;
	container.appendChild(b);

	var b = gB("Отопление", false)[0];
	b.onclick = funcbuttonclick;
	container.appendChild(b);

	var b = gB("Канализация", false)[0];
	b.onclick = funcbuttonclick;
	container.appendChild(b);

	var b = gB("Охрана", false)[0];
	b.onclick = funcbuttonclick;
	container.appendChild(b);
	
/*	
	$(container).append("<tr height='10'><td></td></tr>");
	var b = gB("Материально ответственное лицо", true)[0];
	container.appendChild(b);

	var b = gB("Солтан Н.П.", false)[0];
	b.onclick = funcbuttonclick;
	container.appendChild(b);

	$(container).append("<tr height='10'><td></td></tr>");
	var b = gB("Наличие текущих ремонтов", true)[0];
	container.appendChild(b);

	var b = gB("Ремонт кровли", false)[0];
	b.onclick = funcbuttonclick2;
	container.appendChild(b);
	$(container).append("<tr height='10'><td></td></tr>");
	var b = gB("Регистрация инцидентов на объекте", true)[0];
	container.appendChild(b);

	var b = gB("Инциденты не обнаружены", false)[0];
	b.onclick = funcbuttonclick;
	container.appendChild(b);
*/
	
	$(container).append("<tr height='10'><td></td></tr>");
	$(container).append("<tr height='10'><td></td></tr>");

	container2.appendChild(gB("Схема объекта и наличие коммуникаций", true)[0]);
	var tr = cDom("TR");
	tr.setAttribute("valign", "top");
	container2.appendChild(tr);
	var td = tr.appendChild(cDom("TD"));

	var svgContainer = d3.select(td).append("svg")
								.attr("width", 1000)
								.attr("height", 500);
								
	func = function(){
		var el = gDom("b"+this.oid);
		el.onclick();
	}
	createSVGobjects(svgContainer,  {oid1:oid, n2:"Земельные участки", funcClick:func/*, stroke:"#d3b989"*/});
	createSVGobjects(svgContainer,  {oid1:oid, n2:"Здания и сооружения", fill:"#d3b989", funcClick:func/*, caption:true*/});
	
	function getMinMaxCoordFromPoints(points, coordNum, minOrMax){
		var resultCoord = points[0].split(",")[coordNum];
		for (var i=0; i < points.length; i++){
			var coord = points[i].split(",");
			resultCoord = 
				parseInt(coord[coordNum]) < parseInt(resultCoord) && minOrMax ? coord[coordNum] : minOrMax ? resultCoord :
				parseInt(coord[coordNum]) > parseInt(resultCoord) ? coord[coordNum] : resultCoord;
		}
		return resultCoord;
	}
	
	function getCenterFromPoints(points){
		var maxX = getMinMaxCoordFromPoints(points, 0, false);
		var maxY = getMinMaxCoordFromPoints(points, 1, false);
		var minX = getMinMaxCoordFromPoints(points, 0, true);
		var minY = getMinMaxCoordFromPoints(points, 1, true);
		var centX = parseInt(minX) + Math.round((parseInt(maxX) - parseInt(minX)) / 2);
		var centY = parseInt(minY) + Math.round((parseInt(maxY) - parseInt(minY)) / 2);
		return [centX, centY];
	}
	
	$(container2).append("<tr height='10'><td></td></tr>");
	
	var tr = cDom("TR");
	tr.setAttribute("valign", "top");
	var td = tr.appendChild(cDom("TD"));
	tr.setAttribute("align", "right");
	var trbut = td.appendChild(cDom("TABLE").appendChild(cDom("TR")));
	//container2.appendChild(gB("Наличие коммуникаций", true)[0]);
	container2.appendChild(tr);
	var objects = objectlink.gOrm("gT",[["Класс", "Объект", "Фото"],[],[],[0],false, "*", "and `id Объект` = "+oid+" order by `id Класс`"]);

	for (var i=0; i < objects.length; i++){
		var n = objects[i][1];
		var id = objects[i][0];
		var imgFn = objects[i][5];

		var tdbut = trbut.appendChild(cDom("TD"));
		var b = tdbut.appendChild(cDom("BUTTON", n));
		b.classList.add("card");
		
		b.setAttribute("title", n);
		b.id = "b"+id;
		b.oid = id;
		b.n = n;
		b.c = "Коммуникации";
		b.onclick = function(){
				var ver = getCardVersionByOid(this.oid);
				if (ver) {
					bCard(this.oid, ver);
				}
		}
		if (imgFn) {
			b.innerHTML = "";
			var img = new Image();
			img.height = 64;
			img.src = imgFn;
			b.appendChild(img);
		} else {
			b.hidden = true;
		}
	}

</script>