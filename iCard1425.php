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
		<td align="center" id='label'></td>
	</tr>
	<tr>
		<td valign='top' id="container">
		</td>
	</tr>
</table>

<script>
	var cid = 1425;
	var cn = objectlink.gOrm("gN",[cid]);
	var oid = $_GET(objectIdUrlKey);
	var objectName = objectlink.gOrm("gN",[oid]);
	$("#label").html(objectName);
	
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

	var container = gDom("container");

	var tb = container.appendChild(cDom("TABLE"));
	var tr = tb.appendChild(cDom("TR"));
	tb.style.width = "100%";
	//tb.setAttribute("border",1)
	var tdData = tr.appendChild(cDom("TD"));
	var tdImg = tr.appendChild(cDom("TD"));
	tdData.setAttribute("valign","top");
	tdData.setAttribute("align", "left");
	//tdData.style.border = "1px solid #000";
	tdImg.setAttribute("align", "center");
	//tdData.style.width = "50%";
	tdImg.style.width = "1000px";
	var dataContainer = cDom("DIV");
	dataContainer.style.width = "100%";
	dataContainer.style.height = windowHeight() - 230 + "px";//modalBody.getBoundingClientRect().bottom - modalBody.getBoundingClientRect().top;
	dataContainer.style.overflow = "auto";
	tdData.appendChild(dataContainer);

	var imgContainer = cDom("DIV");
	tdImg.appendChild(imgContainer);
	$(imgContainer)
		.css("height", dataContainer.style.height)
		//.css("width", dataContainer.getBoundingClientRect().right - dataContainer.getBoundingClientRect().left);

	var imgObject = new Image();
	
	//информация
	
	var func = function(arr) {
		var cn_ = arr[0];
		//var cid = objectlink.gOrm("gO",[cn_]);
		$(dataContainer).append("<tr><td colspan='2'><h3>"+cn_+"</h3></td></tr>");

		var arrC = arr;
		var rows = objectlink.gOrm("gT",[arrC, [],[arrC.length-1],[],false,"*"," and `id "+cn+"` = "+oid]);
		row = lineArray2matrixArray(rows[0], arrC.length, 2, true);
		var txt = [];
		var start = 1;
		var end   = arrC.length-1;
		var sliceNum = arrC.indexOf("Файлы")-arrC.length-1;
		for (var i=start; i < end; i++){
			var cellData = "";
			if (row.length && row[i] && row[i].length && row[i][1]){
				if (i < (end-1)){
					cellData = "<b>"+row[i][1]+"</b>";
				} else {
					if (rows.length == 1) {
						cellData = getFileButtonHtml(row[i][1]);
					} else {
						for (var j=0; j < rows.length; j++) {
							cellData = cellData + "<td>" + getFileButtonHtml(rows[j].slice(sliceNum)[0]) + "</td>";
						}
						cellData = "<table><tr>"+cellData+"</tr></table>"
					}
				}
			} else {
				var cellData = "нет данных";
			}
			txt.push("<tr><td style='border-bottom:1px solid #746a55'>"+arrC[i]+":</td><td style='border-bottom:1px solid #746a55'>"+cellData+"</td></tr>");
			
		}
		$(dataContainer).append("<tr><td>"+txt.join("")+"</td></tr>");
		$(dataContainer).append("<tr height='10'><td colspan='2'></td></tr>");
	}

	func(["Правоустанавливающие документы", "Объект права", "Адрес", "Субъект права", "Вид права", "Документы-основания", "Кадастровый номер", "Обременения", "Номер записи регистрации в ЕГРП", "Дата документа", "Файлы",cn]);
	func(["Технические данные", "Общая площадь", "Полезная площадь", "Площадь застройки", "Число этажей", "Высота этажа", "Коммуникации в здании", "Стены", "Перегородки", "Перекрытия", "Фундамент", "Год постройки", "Состояние", "Процент износа", "Литера", "Инвентарный номер", "Дата документа", "Файлы", cn]);

	func(["Материально ответственное лицо", "ФИО", "Телефон", "Email", "Файлы", cn]);
	//func(["Текущие ремонты", "Наименование ремонта", "Ответственный за проведения ремонта", "Планируемая дата начала ремонта", "Планируемая дата завершения ремонта", "Дата составления дефектной ведомости", "Дата согласования сметы", "Ответственный за проверку сметы", "Дата заключения договора", "Подрядчик", "Представитель подрядчика", "Файлы", cn]);
	///Фото
	var images = objectlink.gOrm("gT",[["Здания и сооружения","Фото"],[],[],[],false,"*","and `id Здания и сооружения` ="+oid+" order by `id Фото`"]);
	if (images && images.length) {
		var imgInd = 0;
		
		$(imgObject).attr("src", domain+images[imgInd][3]);
		$(imgObject).css("cursor", "pointer");
		imgContainer.appendChild(imgObject);
		
		$(imgObject)
			.css("max-width", "100%")
			.css("max-height", "100%")
			.attr("title", "кликните для перехода на следующее фото");

		if (parseInt($(imgObject).css("width")) / parseInt($(imgObject).css("height")) > 1) {
			if ($(imgObject).width() > $(imgContainer).width())
				$(imgObject).css("width", $(imgContainer).width())
		} else {
			if ($(imgObject).height() > $(imgContainer).height())
				$(imgObject).css("height", $(imgContainer).height());
		}
		
		imgObject.onclick = function(){
			imgInd = (imgInd >= (images.length-1)) ? 0 : imgInd+1;
			$(this).attr("src", domain+images[imgInd][3]);
		}
	}

</script>