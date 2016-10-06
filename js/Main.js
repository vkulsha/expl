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
		if (polyId != coords[i][1] && poly.length) {
			var p = funcL(poly, paramsL).addTo(map);
			p.oid = oid;
			ret.push(p);
			poly = [];
		}
		oid = coords[i][2];
		var coord = coords[i][0].split(" ");
		poly.push(coord);
		polyId = coords[i][1];
		if (i == coords.length-1){
			var p = funcL(poly, paramsL).addTo(map);
			p.oid = oid;
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
