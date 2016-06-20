<table border=0 width="100%" height="100%">
	<tr>
		<td id='tdHeader'>
		</td>
	</tr>
	<tr>
		<td height="100%">
			<div id="map" width="100%" height="100%"></div>
		</td>
	</tr>
</table>
 
<script type='text/javascript'>
	var objectId = $_GET(objectIdUrlKey);
	var bFiltersDel = document.getElementById("bFiltersDel");
	
	var map = L.map('map');
	//L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', 
	//	{attribution: '&copy; <a rel="nofollow" href="http://osm.org/copyright">OpenStreetMap</a> contributors'}
	//).addTo(map);
	
	L.tileLayer( '//mt{s}.googleapis.com/vt?lyrs=s,h&x={x}&y={y}&z={z}',
	{
	  //attribution: '&copy; <a rel="nofollow" href="http://osm.org/copyright">OpenStreetMap</a> contributors',
	  maxZoom: 18,
	  subdomains: [ 0, 1, 2, 3 ]
	} ).addTo( map );
	
	
	//$("#map").style.width = "100";
	//$("#map").style.height = "100";
	var cont = map.getContainer();
	cont.style.height = "100%";//(windowHeight() * (500/699)) + "px";
	cont.style.width = "100%";//(windowWidth() - 20)+"px"
	
	var markers = L.layerGroup();
	var addressMarker = L.marker([0, 0]);
	isAddressAdd = false;

////////search Address
	function searchAddress(query){
		var q = "https://geocode-maps.yandex.ru/1.x/?format=json&geocode="+query;
		var result;
		getHttp(q, function(data){
			var data = data;
			var pos = data.response.GeoObjectCollection.featureMember[0].GeoObject.Point.pos;
			result = pos.split(" ");
		}, false);
		
		return result;
	}
////////	
	function callback(val){
		markers.clearLayers();
		markers = val.markers;
		markers.addTo(map);

		var minLat = val.bounds.minLat, 
			minLon = val.bounds.minLon, 
			maxLat = val.bounds.maxLat, 
			maxLon = val.bounds.maxLon;
		if (minLat == maxLat || minLon == maxLon) {
			map.setView([minLat, minLon], 17);
		} else {
			map.fitBounds([
				[minLat, minLon],
				[maxLat, maxLon]
			]);
		}
		//$("#caption").html( "Объектов на карте: " + val['rowsCount'] )
	}

	iMap({"whereCond" : (objectId ? " and rowid in ("+objectId+") " : "")}, 
		callback 
	);

	var polylines = [];

	var paint = function(coords){
		var poly = [];
		var polyId = coords[0][1];
		for (var i=0; i < coords.length; i++){
			if (polyId != coords[i][1] && poly.length) {
				var p = L.polygon(poly, {color: '#ff5555'}).addTo(map);
				p.oid = oid;
				polylines.push(p);
				poly = [];
			}
			var oid = coords[i][2];
			var coord = coords[i][0].split(" ");
			poly.push(coord);
			polyId = coords[i][1];
			if (i == coords.length-1){
				var p = L.polygon(poly, {color: '#ff5555'}).addTo(map);
				p.oid = oid;
				polylines.push(p);
				
			}
		}
	}

	var zu = objectlink.gOrm("gT",[["Объект", "Земельные участки", "Полигоны на карте", "Координаты на карте"],[[2,1],[3,2]],[],[],false, "`Координаты на карте`, `id Полигоны на карте`, `id Земельные участки`", "and `id Объект` = "+115+" and `id Координаты на карте` is not null order by `id Полигоны на карте`,`id Координаты на карте`"]);
	var zd = objectlink.gOrm("gT",[["Объект", "Здания и сооружения", "Полигоны на карте", "Координаты на карте"],[[2,1],[3,2]],[],[],false, "`Координаты на карте`, `id Полигоны на карте`, `id Здания и сооружения`", "and `id Объект` = "+115+" and `id Координаты на карте` is not null order by `id Полигоны на карте`,`id Координаты на карте`"]);
	paint(zu);
	paint(zd);
	
	for (var i=0; i < polylines.length; i++){
		polylines[i].on('mouseover', function(e) {
			this.setStyle({color : "#00ff00"});
		});
		
		polylines[i].on('mouseout', function(e) {
			this.setStyle({color : "#ff5555"});
		});	

		polylines[i].on('click', function(e) {
			console.log(this);
		});	
	}

	
</script>
