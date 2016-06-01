<table width='100%' height='100%' border='0'>
	<tr>
		<td valign='top'>
			<table id='container'></table>
		</td>
	</tr>
</table>

<script>
	function gB(val, isTxt){
		var tr = cDom("TR");
		tr.setAttribute("valign", "top");
		var td = tr.appendChild(cDom("TD"));
		var b = td.appendChild(!isTxt ? cDom("BUTTON", val) : cDom("LABEL", val));
		return [tr,td,b];
	}
	
	function gObjects(oid1, n2){
		container.appendChild(gB(n2, true)[0]);
		var objects = objectlink.getlinkedObjects(oid1, n2);
		for (var i=0; i < objects.length; i++){
			var n = objects[i][1];
			var id = objects[i][0];
			var b = gB(n);
			b[2].id = id;
			container.appendChild(b[0]);

			b[2].onclick = function(){
				console.log(this.id);
			}
			
		}
	}
	
	var container = gDom("container");
	
	gObjects(115, "Адрес");
	gObjects(115, "Земельные участки");
	gObjects(115, "Здания и сооружения");
	gObjects(115, "Класс");
	
	

</script>