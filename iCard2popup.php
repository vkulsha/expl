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
	//var d = new Date();
	//var policy = arr2obj(currentUser.policy[currentUser.classes["Object"].ind], true);
	var objectId = $_GET(objectIdUrlKey);
	var numberCid = classes["Номер"];
	var numId = parseInt(objectlink.gOrm("gAnd",[[numberCid],"id",true,"and n='"+objectId+"'"]));
	var objectCid = classes["Объект"];
	var oid = objectlink.gOrm("gAnd",[[objectCid,numId],"id",true]);
	
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
				var svg = $("#svg"+this.oid).get()[0];
				if (svg) svg.onmouseover();
			}

			b[2].onmouseout = function(){
				var svg = $("#svg"+this.oid).get()[0];
				if (svg) svg.onmouseout();
			}

			b[2].onclick = function(){
				insertDataToModal(this);
			}
		}
	}

	function createSVGobjects(svgCont, opts){
		var objects = objectlink.gOrm("gT",[["Объект", opts.n2, "Векторные схемы объектов"],[[2,1]],[],[],false, "*", "and `id Объект` = "+opts.oid1]);
		for (var i=0; i < objects.length; i++){
			var id = objects[i][2];//opts.n2
			var val = objects[i][5];//`Векторные схемы объектов`
			createSVGpolygon(svgCont, val, id, opts.fill, opts.funcClick, opts.caption ? i+1 : "", opts.stroke);
		}
	}
	
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
		
		el.onmouseover = function(){
			if (fill) {
				this.setAttribute("fill", fill);
			}
			if (stroke) {
				this.setAttribute("stroke", stroke);
			}
			this.style.cursor = "pointer";
			
			//var but = $("#b"+this.oid).get()[0];
			//if (but) but.onmouseover();
		};
		
		el.onmouseout = function(){
			this.setAttribute("fill", "transparent");
			this.setAttribute("stroke", "#7b5401");
			this.style.cursor = "auto";
			
			//var but = $("#b"+this.oid).get()[0];
			//if (but) but.onmouseout();
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
	
	gObjects(container, {n1:"Объект", id:oid, caption:"Общая информация по объекту:"});
	$(container).append("<tr height='10'><td></td></tr>");
	gObjects(container, {n1:"Объект", n2:"Земельные участки", id:oid, caption:"Земельные участки:"});
	$(container).append("<tr height='10'><td></td></tr>");
	gObjects(container, {n1:"Объект", n2:"Здания и сооружения", id:oid, isNum:true, caption:"Состав имущества:"});

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
	createSVGobjects(svgContainer,  {oid1:oid, n2:"Здания и сооружения", fill:"#d3b989", funcClick:func, caption:true});
	
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
	var objects = objectlink.gOrm("gT",[["Класс", "Объект", "Фото"],[],[],[0],false, "*", "and `id Объект` = "+oid]);

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
			insertDataToModal(this);
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
	
	function insertDataToModal(object){
		$("#modalTitle").html(object.n);
		$("#modalBody").html("");
		$("#modalFooter").html("");
		var maincont = $("#modalCont");
		var cont = $(modalBody).get()[0];

		var tb = cont.appendChild(cDom("TABLE"));
		var tr = tb.appendChild(cDom("TR"));
		tb.style.width = "100%";
		//tb.setAttribute("border",1)
		var tdData = tr.appendChild(cDom("TD"));
		var tdImg = tr.appendChild(cDom("TD"));
		tdData.setAttribute("valign","top");
		tdData.setAttribute("align", "left");
		//tdData.style.border = "1px solid #000";
		tdImg.setAttribute("align", "right");

		var dataContainer = cDom("DIV");
		dataContainer.style.width = "100%";
		dataContainer.style.height = "1px";

		var imgContainer = cDom("DIV");
		$(imgContainer)
			.css("height", windowHeight()-230+"px")
			.css("width", maincont.css("width")-50+"px");
		var imgObject = new Image();
		
		//информация
		dataContainer.style.overflow = "auto";
		tdData.appendChild(dataContainer);
		tdImg.appendChild(imgContainer);
		
		switch (object.c) {
			case "Земельные участки":
			case "Здания и сооружения":
				
				var func = function(arr) {
					var cn = arr[0];
					var cid = objectlink.gOrm("gO",[cn]);
					$(dataContainer).append("<tr><td colspan='2'><h3>"+cn+"</h3></td></tr>");

					var arrC = arr;
					var inf = objectlink.gOrm("gT",[arrC, [],[arrC.length-1],[],false,"*"," and `id "+object.c+"` = "+object.oid]);
					inf = lineArray2matrixArray(inf[0], arrC.length, 2, true);
					var txt = [];
					var start = 1;
					var end   = arrC.length-1;
					for (var i=start; i < end; i++){
						if (inf.length && inf[i] && inf[i].length && inf[i][1]){
							var cellData = (i < (end-1)) ? inf[i][1] : getFileButtonHtml(inf[i][1]);
						} else {
							var cellData = "";
						}
						txt.push("<tr><td style='border-bottom:1px solid'>"+arrC[i]+":</td><td style='border-bottom:1px solid'><b>"+cellData+"</b></td></tr>");
						
					}
					$(dataContainer).append("<tr><td>"+txt.join("")+"</td></tr>");
					$(dataContainer).append("<tr height='10'><td colspan='2'></td></tr>");
				}

				func(["Свидетельство о государственной регистрации права", "Дата документа","Документы-основания","Субъект права","Вид права","Объект права","Кадастровый номер","Обременения","Номер записи регистрации","Файлы",object.c]);

				if (object.c == "Земельные участки") {
/*					$(dataContainer).append("<tr><td><b>Кадастровый паспорт</b></td></tr>");
					$(dataContainer).append("<tr><td>"+
					"дата документа <br>"+
					"кадастровый номер <br>"+
					"категория земель <br>"+
					"разрешенное использование <br>"+
					"кадастровая стоимость <br>"+
					"");
					$(dataContainer).append("<tr height='10'><td></td></tr>");*/
				} else {
					func(["Технический паспорт", "Дата документа", "Общая площадь", "Полезная площадь", "Площадь застройки", "Этажность", "Год постройки", "Высота этажа", "Стены", "Перегородки", "Перекрытия", "Фундамент", "Состояние", "Процент износа", "Файлы", object.c]);

				}
/*				
				$(dataContainer).append("<tr><td><b>Данные бухучета</b></td></tr>");
				$(dataContainer).append("<tr><td>"+
				"Инвентарный или условный номер <br>"+
				"Балансовая стоимость <br>"+
				"Остаточная стоимость на <br>"+
				"");

				$(dataContainer).append("<tr height='10'><td></td></tr>");
				$(dataContainer).append("<tr><td><b>Материально ответственное лицо</b></td></tr>");
				$(dataContainer).append("<tr><td>"+
				"ФИО <br>"+
				"Должность <br>"+
				"Телефон <br>"+
				"Email <br>"+
				"");

				$(dataContainer).append("<tr height='10'><td></td></tr>");
				$(dataContainer).append("<tr><td><b>Дополнительная информация</b></td></tr>");
				$(dataContainer).append("<tr><td>"+
				"Инженерные коммуникации <br>"+
				"");

				$(dataContainer).append("<tr height='10'><td></td></tr>");
*/				
				///Фото
				var cid = classes["Фото"];
				//var images = objectlink.gOrm("gAnd",[[object.oid, cid]]);
				var images = objectlink.gOrm("gT",[["Здания и сооружения","Фото"],[],[],[],false,"*","and `id Здания и сооружения` ="+object.oid+" order by `id Фото`"]);
				if (images && images.length) {
					var imgInd = 0;
					
					$(imgObject).attr("src", domain+images[imgInd][3]);
					$(imgObject).css("cursor", "pointer");
					imgContainer.appendChild(imgObject);
					
					$(imgObject)
						.css("max-width", "100%")
						.css("max-height", "100%")
						.attr("title", "кликните для перехода на следующее фото");

					if (parseInt($(imgObject).css("width")) / parseInt($(imgObject).css("height")) > 1)
						$(imgObject).css("width", $(imgContainer).css("width"))
					else
						$(imgObject).css("height", $(imgContainer).css("height"));
					
					imgObject.onclick = function(){
						imgInd = (imgInd >= (images.length-1)) ? 0 : imgInd+1;
						$(this).attr("src", domain+images[imgInd][3]);
					}
				}
/*
				///Файлы
				var policy = {add:true};
				var addButtonHtml = policy.add ? "<button id='bFileUpload'>+</button>" : "";
				var domPanelFileUploadHtml = "<div style='background-color:#fffff0; border:1px solid #ccc' hidden id='domPanelFileUpload'><table cellspacing=5><tr><td>"+
					"<form enctype='multipart/form-data' action='upload2.php' method='POST'>"+
					"<input type='hidden' name='MAX_FILE_SIZE' value='0' />"+
					"<input type='hidden' name='uploadPath' value='data/"+object.oid+"/' />"+
					"<input type='hidden' name='uploadId' value='"+object.oid+"' />"+
					"Загрузить файл: <input name='userfile[]' type='file' multiple /><br><br>"+
					"<input type='submit' value='Загрузить' />"+
					"</form></td></tr></table></div>";
				var html = "<div id='fileContainer' style='border: 1px dashed #999; padding: 10px; height:100%; background-color:inherit; overflow-x:auto'><table cellpadding='5'><tr id='trFileContainer'><td>"+addButtonHtml+"</td><td>"+domPanelFileUploadHtml+"</td></tr></table></div>";
					
				$("#modalFooter").html("");
				$("#modalFooter").append(html);
				//$("#modalFooter").append(domPanelFileUploadHtml);

				var fileContainer = $("#fileContainer");
				//fileContainer.css("width", imgContainer.css("width"));
				fileContainer.css("height", "100px");
				
				//fileContainer.find("table").find("tr").append(domPanelFileUploadHtml);
				var domPanelFileUpload = gDom("domPanelFileUpload")
				var bFileUpload = gDom("bFileUpload");
				if (bFileUpload && domPanelFileUpload) {
					bFileUpload.onclick = function(e){
						domPanelFileUpload.style.left = e.clientX+2;
						domPanelFileUpload.style.top = e.clientY+2;
						domPanelFileUpload.hidden = !domPanelFileUpload.hidden;
					}
				}

				var cid = classes["Файлы"];
				//var otherFiles = objectlink.gOrm("gAnd",[[object.oid, cid]]);
				var otherFiles = objectlink.gOrm("gT",[["Здания и сооружения","Файлы"],[],[],[],false,"*","and `id Здания и сооружения` ="+object.oid]);
				var filesHtml = [];
				var iconFile = "file.png";
				for (var i=0; i < otherFiles.length; i++) {
					var fn = otherFiles[i][3];
					var cap = fn.split("/")[fn.split("/").length-1];
					if (fn.split(".")[fn.split(".").length-1] == "pdf") {
						iconFile = getIconFile(otherFiles[i][3].toLowerCase());
						
						filesHtml.push(
							"<td><a href='#' onclick='openWindow(\""+domain+url2cp1251(fn)+"\")' title='скачать файл' >"+
							"<table><tr align='middle'><td><img src='"+iconFile+"' width='32'/></td></tr>"+
							"<tr align='middle'><td style='font-size:11px; width:10px'>"+cap+"</td></tr></table></a></td>"
						);
					}
				}
				$("#trFileContainer").append(filesHtml.join(""));
*/
			break;
			case "Объект":
				
			break;

			default:
			break;
		}
		modal.style.display = "block";
		dataContainer.style.height = modalBody.getBoundingClientRect().bottom - modalBody.getBoundingClientRect().top;
		
	}
		
</script>