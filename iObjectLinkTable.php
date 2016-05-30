<table>
	<tr>
		<td>
			<div id="jstree" style="position:absolute; text-align:left; background-color:#fffff0"></div>
		</td>
	</tr>
	<tr>
		<td align="center" id="labelComment">ObjectLinkTable</td>
	</tr>
	<tr>
		<td class="jsObjectTable" id="divData">
			<table border=0 id='parentDom'>
			</table>
		</td>
	</tr>
</table>

<script>
	var o2t = function(parentDom, valueDom, isTD){
		var tr = isTD ? parentDom : parentDom.appendChild(cDom("TR"));
		var td1 = tr.appendChild(cDom("TD"));
		td1.appendChild(valueDom);
		var td2 = tr.appendChild(cDom("TD"));
		var tb = td2.appendChild(cDom("TABLE"));
		tb.style.border = "1px solid #999";
		return [tb, tr];
	}
	
	var b2t = function(parentDom, value, isTD){
		return o2t(parentDom, cDom("BUTTON", value), isTD);
	}
	
	var pd = gDom("parentDom");
	var o = b2t(pd, "ПЭИ2");

	var o1 = b2t(o[0], "объект1");
	var o2 = b2t(o[0], "объект2");

	var adr1 = b2t(o1[1], "адрес1", true);
	var p1 = b2t(o1[1], "парам1", true);
	var adr2 = b2t(o2[1], "адрес2", true);
	var p2 = b2t(o2[1], "парам2", true);

	var o11 = b2t(o1[0], "здание1");
	var o12 = b2t(o1[0], "здание2");
	var o21 = b2t(o2[0], "здание3");
	var o22 = b2t(o2[0], "здание4");

	var o111 = b2t(o11[0], "файл1");
	var o112 = b2t(o11[0], "файл2");
	var o121 = b2t(o12[0], "файл3");
	var o122 = b2t(o12[0], "файл4");
	var o211 = b2t(o21[0], "файл5");
	var o212 = b2t(o21[0], "файл6");
	var o221 = b2t(o22[0], "файл7");
	var o222 = b2t(o22[0], "файл8");
	
	
</script>
