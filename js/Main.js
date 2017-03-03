var mainHtmlPage = "";//"index.html";

function mapLoad(arrLatLon, opts, click){
	var ObjectIcon = L.Icon.extend({
		options: {
			iconSize:     [40, 40],
			iconAnchor:   [20, 40],
			popupAnchor:  [0, -40]
		}
	});

	var objectIcons = [
		new ObjectIcon({iconUrl: opts && opts.marker || 'images/marker00.png'}), 
	];

	var minLat = 1000, minLon = 1000, maxLat = 0, maxLon = 0;
	var markers = L.layerGroup();
	var arrLat = [];
	var arrLon = [];
	var arrOid = [];
	var arrZoom = [];
	
	$.each(arrLatLon, function(ind, value){
		var lat = value[0];
		var lon = value[1];
		var oid = value[2];
		var zoom = value[3] || 18;

		minLat = Math.min(lat, minLat);
		minLon = Math.min(lon, minLon);
		maxLat = Math.max(lat, maxLat);
		maxLon = Math.max(lon, maxLon);
		
		var icon = {icon:objectIcons[0]};
		
		var marker = L.marker([lat, lon], icon)
			.addTo(markers)
			.on("click", function(){
				click( {"lat":lat, "lon":lon, "oid":oid} )
			});

		arrOid.push(oid);
		arrLat.push(lat);
		arrLon.push(lon);
		arrZoom.push(zoom);

	});
	
	return {
		"markers":markers, 
		"objects":{"oid": arrOid, "lat" : arrLat, "lon" : arrLon, "zoom" : arrZoom},
		"bounds":{
			"minLat":minLat, 
			"minLon":minLon, 
			"maxLat":maxLat, 
			"maxLon":maxLon
		}
	};
	
}

function searchAddress(query){
	var q = "https://geocode-maps.yandex.ru/1.x/?format=json&geocode="+query;
	var result;
	getHttp(q, function(data){
		var data = data;
		var res = data.response.GeoObjectCollection.featureMember;
		if (res.length) {
			var pos = res[0].GeoObject.Point.pos;
			result = pos.split(" ");
		}
	}, false);
	
	return result;
}

function mapPaint(coords, funcL, paramsL, map){
	if (!coords || !coords.length) return;
	var ret = [];
	var poly = [];
	paramsL = paramsL || {};
	funcL = funcL || L.polygon;
	var polyId = coords[0][1];
	for (var i=0; i < coords.length; i++){
		var oid;
		var objid;
		if (polyId != coords[i][1] && poly.length) {
			var p = funcL(poly, paramsL).addTo(map);
			p.oid = oid;
			p.objid = objid;
			ret.push(p);
			poly = [];
		}
		oid = coords[i][2];
		objid = coords[i][3];
		var coord = coords[i][0].split(" ");
		poly.push(coord);
		polyId = coords[i][1];
		if (i == coords.length-1){
			var p = funcL(poly, paramsL).addTo(map);
			p.oid = oid;
			p.objid = objid;
			ret.push(p);
			
		}
	}
	return ret;
}

////////////////center point from arr points
function getMinMaxCoordFromPoints(points, coordNum, minOrMax){
	var resultCoord = points[0][coordNum];
	for (var i=0; i < points.length; i++){
		var coord = points[i];
		resultCoord = 
			parseFloat(coord[coordNum]) < parseFloat(resultCoord) && minOrMax ? coord[coordNum] : minOrMax ? resultCoord :
			parseFloat(coord[coordNum]) > parseFloat(resultCoord) ? coord[coordNum] : resultCoord;
	}
	return resultCoord;
}

function getCenterFromPoints(points){
	var maxX = getMinMaxCoordFromPoints(points, 0, false);
	var maxY = getMinMaxCoordFromPoints(points, 1, false);
	var minX = getMinMaxCoordFromPoints(points, 0, true);
	var minY = getMinMaxCoordFromPoints(points, 1, true);
	var centX = parseFloat(minX) + ((parseFloat(maxX) - parseFloat(minX)) / 2);
	var centY = parseFloat(minY) + ((parseFloat(maxY) - parseFloat(minY)) / 2);

	return [centX, centY];
}
///////////

function initMap(map){
	L.tileLayer( '//mt{s}.googleapis.com/vt?lyrs=s,h&x={x}&y={y}&z={z}',
	{
	  maxZoom: 18,
	  subdomains: [ 0, 1, 2, 3 ]
	} ).addTo( map );
	
	var cont = map.getContainer();
	cont.style.height = "100%";
	cont.style.width = "100%";
	
	var markers = L.layerGroup();

	var markerClick = function(val){
		location.href = mainHtmlPage+"#oid="+val.oid;
	}
	
	var arrObjects = objectlink.gOrm("gT2",[["Объект","Широта","Долгота","Масштаб на карте"],[],[],false,["`Широта`", "`Долгота`", "`id_Объект`","`Масштаб на карте`"]]);
	var mapObjects = mapLoad(arrObjects, {}, markerClick);

	markers.clearLayers();
	markers = mapObjects.markers;
	markers.addTo(map);
	map.fitBounds([
		[mapObjects.bounds.minLat, mapObjects.bounds.minLon],
		[mapObjects.bounds.maxLat, mapObjects.bounds.maxLon]
	]);
	
	return mapObjects;
	
	
}

////////////////load to panel
function loadPanel(arr, container, idInd, valInd, funcClick, funcOver, funcOut, iconInd, cells2row, paramsInd, polylines){
	var drow;
	var buttons = [];
	if (cells2row) {
		drow = container.appendChild(cDom("DIV"));
		drow.classList.add("div-table-row");
		drow.classList.add("alpha");
	}
	for (var i=0; i < arr.length; i++){
		var row = arr[i];
		if (!cells2row) {
			drow = container.appendChild(cDom("DIV"));
			drow.classList.add("div-table-row");
			drow.classList.add("alpha");
		}
		var dcol = drow.appendChild(cDom("DIV"));
		if (!cells2row) { dcol.classList.add("div-table-col") } else { dcol.style.display = "inline" };
		dcol.classList.add("alpha");
		dcol.style.backgroundColor = "rgba(0,0,0,0.1)";
		var but = dcol.appendChild(cDom("BUTTON"));
		but.oid = row[idInd];
		but.id = "but"+but.oid;
		but.params = row[paramsInd];
		but.row = row;
		but.ind = i;
		var iconUrl = row[iconInd];
		but.innerHTML = iconUrl ? "<img src='"+iconUrl+"' style='width:32px'>" : row[valInd];
		but.setAttribute("title", iconUrl ? row[valInd] : "");
		but.style.width = cells2row ? "auto" : "100%";
		but.classList.add("alpha");

		but.onclick = funcClick;
		but.onmouseover = funcOver;
		but.onmouseout = funcOut;
		but.polylines = polylines;
		buttons.push(but);
	}
	return buttons;
}

function fillCard2(arr, oid, cont){
	if (!cont || !arr || !arr.length) {
		$(cont).append("<h3 style='color:#999'>Нет данных</h3>");
		return;
	}
	var cn_ = arr[0];
	$(cont).append("<tr class='h3caption'><td colspan='2'><h3 style='color:#999'>"+cn_+"</h3></td></tr>");
	var arrC = arr;
	//var dt = new Date();
	//var rows = objectlink.gOrm("gT",[arrC, [],[arrC.length-1],[],false,decorateArr(arrC, "`").concat(decorateArr(arrC, "`id_", "`")).join(","),
	//	" and `id_"+arr[arr.length-1]+"` = "+oid+" order by "+decorateArr(arrC, "`id_", "`").join(",")]);
	var rows = objectlink.gOrm("gT2",[arrC, [],/*[arrC.length-1],*/[],false,decorateArr(arrC, "`").concat(decorateArr(arrC, "`id_", "`"))/*.join(",")*/,
		" and `id_"+arr[arr.length-1]+"` = "+oid+" order by "+decorateArr(arrC, "`id_", "`").join(",")]);
	//console.log(new Date()-dt);
	//console.log(rows);
	var tb = cont.appendChild(cDom("TABLE"));
	var filesInd = arrC.indexOf("Файлы");

	var vals = [];
	var isValsNotNull = false;
	var startColumnNum = arrC[0] == "Файлы" ? 0 : 1;
	
	for (var i=startColumnNum; i < arrC.length-1; i++){
		var isFiles = filesInd >= 0 && filesInd == i;
		var tr = tb.appendChild(cDom("TR"));
		var td1 = tr.appendChild(cDom("TD"));
		var td2;
		td1.style.borderBottom = "1px solid #333";
		td1.style.color = "#999";
		var divVal = td1.appendChild(cDom("DIV"));
		
		if (isFiles) {
			td1.setAttribute("colspan", 2);
		} else {
			td1.innerHTML = arrC[i];
			td1.setAttribute("valign", "top");
			td2 = tr.appendChild(cDom("TD"));
			td2.style.borderBottom = "1px solid #333";
		}
		
		var val = null;
		var vals = [];
		for (var j=0; j < rows.length; j++){
			var val_ = rows[j][i];
			if (val_ && isFiles && val_.toLowerCase().indexOf(".pdf") == -1) continue;
			if (val != val_ && vals.indexOf(val_) == -1){
				vals.push(val_);
				if (isFiles) {
					$(divVal).append("<input class='chFileButtonHtml' type='checkbox' id='oid"+rows[j][(i+1)*2]+"' value='"+domain+url2cp1251(val_)+"' hidden/>");
					$(divVal).append(getFileButtonHtml(val_));
				} else {
					divVal = td2.appendChild(cDom("DIV"));
					divVal.innerHTML = val_;
				}
			}
			val = val_;
			isValsNotNull = isValsNotNull || (val_ != "")
		}
		if (!isFiles && (divVal.innerHTML == "")) {
			tr.hidden = true;
		}
	}
	var caps = document.getElementsByClassName("h3caption")
	caps[caps.length-1].hidden = !isValsNotNull;
}	

function fillCardEasy(arr, id, cont){
	if (!cont || !arr || !arr.length) {
		$(cont).append("<h3 style='color:#999'>Нет данных</h3>");
		return;
	}

	var arrC = arr;
	var filesInd = arrC.indexOf("Файлы");
	var rows = objectlink.gOrm("gT2",[arrC,[],[],false,decorateArr(arrC, "`")," and `id_"+arrC[0]+"`="+id+" order by "+decorateArr(arrC, "`id_", "`").join(",")]);
	var tb = cont.appendChild(cDom("TABLE"));

	var vals = [];
	var isValsNotNull = false;
	for (var i=0; i < arrC.length; i++){
		var isFiles = filesInd && filesInd == i;

		var tr = tb.appendChild(cDom("TR"));
		var td1 = tr.appendChild(cDom("TD"));
		var td2;
		td1.style.borderBottom = "1px solid #333";
		td1.style.color = "#999";
		var divVal = td1.appendChild(cDom("DIV"));
		
		if (isFiles) {
			td1.setAttribute("colspan", 2);
		} else {
			td1.innerHTML = arrC[i];
			td1.setAttribute("valign", "top");
			td2 = tr.appendChild(cDom("TD"));
			td2.style.borderBottom = "1px solid #333";
		}
		
		var val = null;
		var vals = [];
		for (var j=0; j < rows.length; j++){
			if (val != rows[j][i] && vals.indexOf(rows[j][i]) == -1){
				vals.push(rows[j][i]);
				if (isFiles) {
					$(divVal).append(getFileButtonHtml(rows[j][i]));
				} else {
					divVal = td2.appendChild(cDom("DIV"));
					divVal.innerHTML = rows[j][i];
				}
			}
			val = rows[j][i];
			isValsNotNull = isValsNotNull || (rows[j][i] != "")
		}
		if (!isFiles && (divVal.innerHTML == "")) {
			tr.hidden = true;
		}
	}
}	