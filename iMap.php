<style>
	#map {width: 1200px; height: 500px; }
</style>

<table border=0>
	<tr>
		<td>
			<table cellspacing="3">
				<tr>
					<td>
						<input type="text" id="addressSearch" autofocus placeholder="Поиск адреса на карте" title="Введите адрес и нажмите ENTER" style="width:200px" />
					</td>
					<td>
						<select id='tu' title="Выберите территориальное управление">
							<option selected>Все</option>
							<option>УЭИ</option>
							<option>СЗТП</option>
							<option>СиДВ</option>
						</select>
					</td>
					<td>
						<select id='manager' title="Выберите руководителя имущественного комплекса">
						</select>
					</td>
					<td>
						<button onClick="bMap(undefined, false)" id="bFiltersDel" title="Удалить фильтры" hidden ><img src="images/filter_del.png" width="15"/></button>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="1">
			<div id="map"></div>
		</td>
	</tr>
	<tr>
		<td>
			<label id="caption"></label>

		</td>
	</tr>
</table>
 
<script type='text/javascript'>
	var objectId = null;
	<?php if (isset($_GET[$objectIdUrlKey])){echo "objectId = '".$_GET[$objectIdUrlKey]."';";}; ?>

	var bFiltersDel = document.getElementById("bFiltersDel");
	
	var map = L.map('map');
	L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', 
		{attribution: '&copy; <a rel="nofollow" href="http://osm.org/copyright">OpenStreetMap</a> contributors'}
	).addTo(map);
	var cont = map.getContainer();
	cont.style.height = (windowHeight() * (450/699)) + "px";
	cont.style.width = (windowWidth() - 50)+"px"
	
	var markers = L.layerGroup();
	var addressMarker = L.marker([0, 0]);
	isAddressAdd = false;

////////
	var eSearch = document.getElementById('addressSearch');
	eSearch.onkeydown = function(e){
		var query = this.value;
		if (e.which == 13) {
			var q = "https://geocode-maps.yandex.ru/1.x/?format=json&geocode="+query;
				$.getJSON( q, {
				format: "json"
			})
			.done(function( data ) {
				var pos = data.response.GeoObjectCollection.featureMember[0].GeoObject.Point.pos;
				var lonLat = pos.split(" ");
				var latlng = L.latLng(lonLat[1],lonLat[0]);
				
				if (!isAddressAdd) {
					isAddressAdd = true;
					addressMarker.addTo(map);
					
					addressMarker
						.setLatLng(latlng)
						.bindPopup(query);
				}
				map.setView([lonLat[1], lonLat[0]], 17);
				bFiltersDel.hidden = false;
			})
			.fail(function(e) {
				console.log( e );
			})
		}
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
		$("#caption").html( "Объектов на карте: " + val['rowsCount'] )
	}

	function dblclick(val) {
		map.setView([val.lat, val.lon], 17);
	}

	if (objectId) {
		bFiltersDel.hidden = false;
	}
	
	iMap({"whereCond" : (objectId ? " and rowid in ("+objectId+") " : "")}, 
		callback, 
		dblclick 
	);

	//var polyline = L.polygon([[44,36],[65,36],[65,90],[44,90],[44,36]], {color: 'red'}).addTo(map);
///select tu and managers	
	var domManager = document.getElementById('manager');
	
	function setManagers4tu(dom, tu){
		var val = tu;
		val = val == "Все" ? "" : " and tu = '"+val+"'";
		var managers = getOrm("select distinct `manager` from `"+mapObjectsTableName+"` where 1=1 "+val, "col2array");
		dom.innerHTML = "";
		var opt = document.createElement("OPTION");
		opt.innerHTML = "Все";
		opt.selected = true;
		dom.appendChild(opt);

		for (var i=0; i < managers.length; i++){
			var opt = document.createElement("OPTION");
			opt.innerHTML = managers[i];
			dom.appendChild(opt);
		}
	}
	setManagers4tu(domManager, "Все");
	
	$("select#tu").on("change", function(){
		setManagers4tu(domManager, this.options[this.selectedIndex].text);
	});
///	
	$("select").on("change", function(){
		var val = this.options[this.selectedIndex].text;
		iMap({"whereCond" : val == "Все" ? "" : " and "+this.id+"='"+val+"' "}, 
			callback, 
			dblclick
		);
		bFiltersDel.hidden = false;
	});

	
</script>
