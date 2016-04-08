<table width="100%" border="0">
	<tr class="buttonsLine">
		<td>
			<table cellpadding="5" class="buttonsLine">
				<tr>
					<td align="center">
						<button id="bDocDownload" class="buttons"><img src="images/pdf.png" width="48" title="Открыть папспорт"/>
						<!--<div>Паспорт</div>-->
						</button>
					</td>
					<td align="center">
						<button id="bToMap" class="buttons"><img src="images/bMap.png" height="48" title="Показать объект на карте"/>
						<!--<div>На карте</div>-->
						</button>
					</td>
					<td align="center" class="toCadastr" hidden>
						<button id="bToCadastr" class="buttons"><img src="images/bCadastr.png" width="48" title="Показать объект на кадастровой карте"/>
						<!--<div>Росреестр</div>-->
						</button>
					</td><!--
					<td align="center" class="isPhoto">
						<button id="bPhoto" class="buttons"><img src="images/bPhoto.png" width="48" title="Показать/скрыть фотоматериал"/>
						<div>Фотоматериал</div>
						</button>
					</td>
					<td align="center" class="isFiles">
						<button id="bFiles" class="buttons"><img src="images/bFiles.png" width="48" title="Показать/скрыть файлы объекта"/>
						<div>Файлы</div>
						</button>
					</td>-->
				</tr>
			</table>
		</td>
		<td>
			<div id="fileContainer" style="border: 1px dashed #999; padding: 10px; height:100%; background-color:inherit; overflow-x:auto">
				<table cellpadding='5'>
					<tr>
					</tr>
				</table>
				<button id="bFileUpload" hidden>+</button><button id="bFileDelete" hidden>-</button>
			</div>
		</td>
	</tr>	
	<tr height="100%">
		<td valign="top">
			<div id="domtreecontainer" style="overflow:scroll; height:398px; width:525px" ><div class="domtree"></div></div>
		</td>
		<td align="center" valign="top" width="100%" id="imagesContainer">
			<div id="imgContainer" style="border: 1px dashed #999; padding: 10px; height:500px;" hidden>
				<table width="100%" class="highlight">
					<tr>
						<td align="center"><button id="bImgPrev"><<</button></td>
						<td align="center" width="100%">
							<img id="imgObject" />
						</td>
						<td align="center"><button id="bImgNext">>></button></td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
</table>

<script>
	var policy = arr2obj(currentUser.policy[currentUser.classes["Object"].ind], true);

	var objectId = null;
	<?php 
		echo "objectId = '".$_GET[$objectIdUrlKey]."';\n";
		//echo "policy = {add: true, delete: true};";
	?>
	$("#bDocDownload").bind("click", function(){ passportDownload(objectId) });
	$("#bToMap").bind("click", function(){bMap(objectId)});
	var bImgPrev = $("#bImgPrev");
	var bImgNext = $("#bImgNext");
	var imgObject = $("#imgObject");
	var fileContainer = $("#fileContainer");
	var imgContainer = $("#imgContainer");
	var bToCadastr = $("#bToCadastr");
	var bPhoto = $("#bPhoto");
	var bFiles = $("#bFiles");
	var objectPath = getObjectsDir()+"/"+objectId+"/";
	
	var uri = getQueryObject(objectId);
	var func = function(dataJSON) {
		var data = JSON.parse(dataJSON);
		var value = data.data[0];
		var columns = data.columns;
		var objectCadastrNumber = value[columns.indexOf("cadastr")];
		
		$(".toCadastr").each(function(){
			this.hidden = !(value[columns.indexOf("cadastr")]);
		});
		
		bToCadastr.bind("click", function(){bCadastr(objectCadastrNumber)});

		var imageFiles = [];
		var otherFiles = [];
		var filesChbxs = [];
		getHttp("scandir.php?path="+objectPath, function(imagesJSON) {
			var files = JSON.parse(imagesJSON);

			bPhoto.bind("click", function(){
				imgContainer.get()[0].hidden = !imgContainer.get()[0].hidden;
			});
			
			bFiles.bind("click", function(){
				fileContainer.get()[0].hidden = !fileContainer.get()[0].hidden;
			});
						
			imgContainer
				.css("height", windowHeight()-imgContainer.offset().top-80+"px")
				.attr("title", "открыть в полный размер");
			imgObject.css("max-width", "100%");
			imgObject.css("max-height", "100%");
			if (parseInt(imgObject.css("width")) / parseInt(imgObject.css("height")) > 1)
				imgObject.css("width", imgContainer.css("width"))
			else
				imgObject.css("height", imgContainer.css("height"));
			
			var tableFileContainer = fileContainer.find("table");
			
			var allFiles = splitObjectArray(files, {"images" : ["jpg", "png", "gif", "tif", "bmp"], "other" : []} );
			imageFiles = allFiles.images;
			otherFiles = allFiles.other;
			
			//imagesContainer
			/*
			$(".isPhoto").each(function(){
				this.hidden = !imageFiles.length;
			});
			$(".isFiles").each(function(){
				this.hidden = !otherFiles.length;
			});
			*/
			var filesHtml = [];
			var iconFile = "file.png";
			for (var i=0; i < otherFiles.length; i++) {
				iconFile = 
					(~otherFiles[i].indexOf(".pdf")) ? "pdf.png" : 
					(~otherFiles[i].indexOf(".doc")) ? "word.png" : 
					(~otherFiles[i].indexOf(".xls")) ? "excel.png" : 
					iconFile;
				
				var chDel = document.createElement("INPUT");
				chDel.setAttribute("type", "checkbox");
				chDel.setAttribute("id", "file"+i);
				if (!policy.del) chDel.setAttribute("hidden", "");
				filesChbxs.push(chDel);
				filesHtml.push(
					"<td>"+
					chDel.outerHTML+
					"<a href='#' onclick='openImageWindow(\""+
						domain+objectPath+otherFiles[i]+
					"\")' title='скачать файл' >"+
					"<table><tr align='middle'><td><img src='images/"+iconFile+"' width='32'/></td></tr>"+
					"<tr align='middle'><td>"+otherFiles[i]+"</td></tr></table></a></td>"
				);
			}
			tableFileContainer.append(filesHtml.join(""));
						
			var imgIndMax = imageFiles.length;
			var imgInd = 0;
			if (imgIndMax) {
				imgObject.attr("src", domain+objectPath+imageFiles[imgInd]);
				bImgPrev.get()[0].hidden = true;
				bImgNext.get()[0].hidden = !(imageFiles[imgInd+1] != undefined);
			} else {
				imgContainer.html("ФОТОМАТЕРИАЛ ОТСУТСТВУЕТ");
			}
			
			bImgPrev
				.unbind("click")
				.bind("click", function(){
					imgInd -= 1;
					imgObject.attr("src", domain+objectPath+imageFiles[imgInd]);
					if (imgInd == 0)
						this.hidden = true;
					bImgNext.get()[0].hidden = false;
				});
			bImgNext
				.unbind("click")
				.bind("click", function(){
					imgInd += 1;
					imgObject.attr("src", domain+objectPath+imageFiles[imgInd]);
					if (imgInd == imgIndMax-1)
						this.hidden = true;
					bImgPrev.get()[0].hidden = false;
				});
			imgObject
				.css('cursor','pointer')
				.unbind("click")
				.bind("click", function(){
					openImageWindow(this.src);
				})
		});
		
		var power = getObjectPowerJson(objectId);
		var manager = getObjectManagerJson(value[columns.indexOf("manager")]);
		var node = 
		{"name":"["+objectId+"] "+value[columns.indexOf("name")], "nodeType":1, "children": [
			{"name":"Общие характеристики", "nodeType":1, "children": [
				{"name":"Адрес:", "nodeType":3, "content":value[columns.indexOf("address")]},
				{"name":"Общая площадь:", "nodeType":3, "content":""},
				{"name":"Форма владения:", "nodeType":3, "content":""},
				{"name":"Расстояние до жилых домов:", "nodeType":3, "content":""},
				{"name":"Охрана:", "nodeType":3, "content":""}
			]},
			{"name":"Ответственный", "nodeType":1, "children": [
				{"name":"Территориальное управление:", "nodeType":3, "content":value[columns.indexOf("tu")]},
				{"name":"Имущественный комплекс:", "nodeType":3, "content":value[columns.indexOf("ik")]},
				{"name":"Руководитель:", "nodeType":3, "content":manager.fio},
				{"name":"Тел:", "nodeType":3, "content":manager.phone},
				{"name":"Email:", "nodeType":3, "content":manager.email}
			]},
			{"name":"Мощности", "nodeType":1, "children": [
				{"name":"", "nodeType":3, "content":(power ? power.contract : "")},
				{"name":"Доп:", "nodeType":3, "content":(power ? power.agreement : "")},
				{"name":"Макс разреш мощ, кВт:", "nodeType":3, "content":(power ? power.maxAuthorizedPower : "")},
				{"name":"Макс потреб мощ, кВт:", "nodeType":3, "content":(power ? power.maxConsumptionPower : "")},
				{"name":"Портребл средн, кВт*ч:", "nodeType":3, "content":(power ? power.powerConsumption : "")},
				{"name":"Перевыставление, % :", "nodeType":3, "content":(power ? power.excess : "")},
				{"name":"Источник:", "nodeType":3, "content":(power ? power.powerPoint : "")},
			]},
			/*{"name":"Состав объекта", "nodeType":1, "children": [
				{'name':'...', 'nodeType':3},
			]}*/
		]};
		
		var domtreecontainer = document.getElementById("domtreecontainer");
		domtreecontainer.style.height = (windowHeight() * (398/699))+"px";
		drawHtmlTree(node, 'div.domtree', 550*1.5, 21 *30+13);
		/* + see domtreecontainer height and width
		drawHtmlTree(node, 'div.domtree', width, height);
			height = elemCount*barHeight+13
			width = barWidth*1.5
				barWidth = 250,
				barHeight = 30,
				elemCount = ?
		*/
	
		domPanelFileUpload = document.createElement("DIV");
		domPanelFileUpload.style.position = "absolute";
		domPanelFileUpload.hidden = true;
		domPanelFileUpload.style.backgroundColor = "#fff";
		domPanelFileUpload.style.border = "1px solid #ccc";
		domPanelFileUpload.innerHTML = "<table cellspacing=5><tr><td>"+
			"<form enctype='multipart/form-data' action='upload.php' method='POST'>"+
			"<input type='hidden' name='MAX_FILE_SIZE' value='0' />"+
			"<input type='hidden' name='uploadPath' value='data/objects/"+objectId+"/' />"+
			"Загрузить файл: <input name='userfile' type='file' /><br><br>"+
			"<input type='submit' value='Загрузить' />"+
			"</form></td></tr></table>";

		domPanelFileDelete = document.createElement("DIV");
		domPanelFileDelete.style.position = "absolute";
		domPanelFileDelete.hidden = true;
		domPanelFileDelete.style.backgroundColor = "#fff";
		domPanelFileDelete.style.border = "1px solid #ccc";
		domPanelFileDelete.innerHTML = "";
		
		document.body.appendChild(domPanelFileUpload);
		document.body.appendChild(domPanelFileDelete);

		bFileUpload = document.getElementById("bFileUpload");
		bFileUpload.hidden = !policy.add;
		bFileUpload.onclick = function(e){
			domPanelFileUpload.style.left = e.clientX+2;
			domPanelFileUpload.style.top = e.clientY+2;
			domPanelFileUpload.hidden = !domPanelFileUpload.hidden;
		}

		bFileDelete = document.getElementById("bFileDelete");
		bFileDelete.hidden = !policy.del;
		bFileDelete.onclick = function(e){
			var arr = [];
			for (var i=0; i < otherFiles.length; i++){
				checked = document.getElementById("file"+i).checked;
				if (checked) {
					arr.push(domain+objectPath+otherFiles[i]);
				}
			}
			$.post('delete.php', {deletePath: "data/objects/"+objectId+"/", file: arr})//Решение о разделе.pdf
			.done(function( data ) {
				if (data && data.length) {
					for (var i=0; i < data.length; i++) {
						if (data[i].status == "fail") alert("Ошибка удаления файла: "+data[i].name)
					}
				}
				location.reload();
			});
		}
	
///indert code here	
	};
	getQueryJson(uri, func);
///stop code	
</script>