"use strict";

QUnit.test( "uService.js test", function( a ) {
	var done1 = a.async();
	getHttp(domain+"sql2json.php?q=select * from test", 
		function(d){
			a.ok( JSON.parse(d).columns.length == 3, "getHttp1" );
		}, true,
		function(){	a.ok( false, "getHttp1" ) }, 
		function(){ done1() }
	);
	var done2 = a.async();
	getHttp(domain+"ERRORquery2html.php?type=json&q=select * from test", 
		function(d){
			a.ok( false, "getHttp2" );
		}, true,
		function(){	a.ok( true, "getHttp2" ) }, 
		function(){ done2() }
	);
	var done3 = a.async();
	getHttp(domain+"sql2json.php?a=select * from test", 
		function(d){
			a.ok( JSON.parse(d).data[0][0] == false , "getHttp3" );
		}, true,
		function(){	a.ok( false, "getHttp3" ) }, 
		function(){ done3() }
	);
	var done4 = a.async();
	getQueryJson("select * from test", 
		function(d){
			a.ok( JSON.parse(d).columns.length == 3, "getQueryJson1" );
		}, true,
		function(){	a.ok( false, "getQueryJson1" ) }, 
		function(){ done4() }
	);

	var done5 = a.async();
	getQueryJson("select * from errorTableName", 
		function(d){
			a.ok( JSON.parse(d).data[0][0] == false, "getQueryJson2" );
		}, true,
		function(){	a.ok( true, "getQueryJson2" ) }, 
		function(){ done5() }
	);

	var done6 = a.async();
	iMap({"whereCond" : " and rowid <= 5 "}, 
		function(val){
			a.ok( val.markers._layers['5']._latlng.lat > 50 && val.rowsCount == 5, "iMap1" );
		},
		undefined,
		function(){	a.ok( false, "iMap1" ) }, 
		function(){ done6() }
	);

	var done7 = a.async();
	iMap({"whereCond" : " and ERRORfield = 0 "}, 
		function(val){
			a.ok( val == undefined, "iMap2" );
		},
		undefined,
		function(){	a.ok( false, "iMap2" ) }, 
		function(){ done7() }
	);
	
	a.ok( ifnull(null) == "", "isnull1"  );
	a.ok( ifnull("value") == "value", "isnull2" );
	a.ok( ifns("") == "null", "ifns1" );
	a.ok( ifns("abc") == "'abc'", "ifns2" );
	a.ok( ifns("a 'b' c") == "'a ''b'' c'", "ifns3" );
	a.ok( replace("a 'b' c", "'", "''") == "a ''b'' c", "replace" );
	
	a.ok( getClientIp(), "getClientIp" );
	
	a.ok( getObjectUri(undefined), "getObjectUri1" );
	a.ok( getObjectUri("objId"), "getObjectUri2" );
	a.ok( getObjectUri("1")[getObjectUri(1).length] != "1", "getObjectUri3" );
	
	a.ok( getOrm("select * from test", "row2object").f2 == "b1", "getOrm1 row2object" );//sql, orm
	a.ok( getOrm("select * from test", "col2object").a2.id == "a2", "getOrm2 col2object" );
	a.ok( getOrm("select * from test", "all2object").columns[1] == "f2", "getOrm3 all2object" );
	a.ok( getOrm("select * from test", "row2array")[1] == "b1", "getOrm4 row2array" );
	a.ok( getOrm("select * from test", "col2array")[1] == "a2", "getOrm5 col2array" );
	a.ok( getOrm("select * from test", "all2array")[1][1] == "b2", "getOrm6 all2array" );
	a.ok( getOrm("select * from test", "rows2object")[2]['f3'] == "c3", "getOrm7 rows2object" );
	a.ok( 
		getOrm("select * from test where f1='a1'", "row2table")[0][0] == "f1" && 
		getOrm("select * from test where f1='a1'", "row2table")[0][1] == "a1" && 
		getOrm("select * from test where f1='a1'", "row2table")[1][0] == "f2" && 
		getOrm("select * from test where f1='a1'", "row2table")[1][1] == "b1" 
	, "getOrm8 row2table" );

	var domtable = getOrm("select * from test", "all2domtable");
	a.ok( 
		$(domtable).find("thead").find("tr").find("th")[2].innerHTML == "f3" &&
		$(domtable).find("tbody").find("tr").find("td")[0].innerHTML == "a1"
	, "getOrm9 rows2object" 
	);
	
	var rt = getRowTable4Class('Object', 1);
	a.ok( rt[0][0] == "rowid" && rt[0][1] == "1", "getRowTable4Class" );
	
	a.ok( isArraysIntersect([1,2,3],[1,3]), "isArraysIntersect1" );
	a.ok( isArraysIntersect([1,2,3],[3,8]), "isArraysIntersect2" );
	a.ok( !isArraysIntersect([1,2,3],[9,8]), "isArraysIntersect3" );
	
	a.ok( compareObjectArrays({"a" : ["b","c"]},{"a":["b","c"]}), "compareObjectArrays1" );
	a.ok( !compareObjectArrays({"a" : ["b","c"]},{"a":["d","c"]}), "compareObjectArrays2" );
	a.ok( !compareObjectArrays({"a" : ["b","c"]},{"d":["b","c"]}), "compareObjectArrays3" );
	
	a.ok( 
		compareObjectArrays(
			splitObjectArray(
				{"jpg" : ["j1", "j2"], "png" : ["p1", "p2"], "doc" : ["d1", "d2"], "txt" : ["t1", "t2"]},
				{"images" : ["jpg", "png"], "other" : ["doc", "txt"]}	
			), 
			{"images" : ["j1", "j2", "p1", "p2"], "other" : ["d1", "d2", "t1", "t2"]}
		)
		, "splitObjectArray1"
	);
	a.ok( 
		compareObjectArrays(
			splitObjectArray(
				{"jpg" : ["j1", "j2"], "png" : ["p1", "p2"], "doc" : ["d1", "d2"], "txt" : ["t1", "t2"]},
				{"images" : ["jpg", "png"], "docs" : ["doc"], "other" : []}	
			), 
			{"images" : ["j1", "j2", "p1", "p2"], "docs" : ["d1", "d2"], "other" : ["t1", "t2"]}
		)
		, "splitObjectArray2"
	);
	a.ok( 
		compareObjectArrays(
			splitObjectArray(
				{"jpg" : ["j1", "j2"], "png" : ["p1", "p2"], "doc" : ["d1", "d2"], "txt" : ["t1", "t2"]},
				{"other" : []}	
			), 
			{"other" : ["j1", "j2", "p1", "p2", "d1", "d2", "t1", "t2"]}
		)
		, "splitObjectArray3"
	);
	a.ok( 
		compareObjectArrays(
			splitObjectArray(
				{"jpg" : ["j1", "j2"], "png" : ["p1", "p2"], "doc" : ["d1", "d2"], "txt" : ["t1", "t2"]},
				undefined
			), 
			{"other" : ["j1", "j2", "p1", "p2", "d1", "d2", "t1", "t2"]}
		)
		, "splitObjectArray4"
	);
	a.ok( 
		compareObjectArrays(
			splitObjectArray(
				undefined,
				{"other" : []}	
			), 
			{}
		)
		, "splitObjectArray5"
	);
	
	a.ok( 
		JSON.stringify(lineArray2matrixArray([1,2,3,4,5,6], 2, 4)) == "[[1,2,3,4],[5,6]]" &&
		JSON.stringify(lineArray2matrixArray([1,2,3,4,5,6], 2, 4, true)) == "[[1,2,3,4],[5,6,null,null]]" &&
		JSON.stringify(lineArray2matrixArray([], 2, 4)) == "[]" &&
		JSON.stringify(lineArray2matrixArray(undefined, 2, 4)) == "[]",
		"lineArray2cubeArray1" );

	var interfaces = [
		[
		{"name" : "iMap", 			"caption" : "Объекты на карте", 		"src" : "images/bMap.png"},
		{"name" : "iTable", 		"caption" : "Объекты в таблице", 		"src" : "images/bSearch.jpg"},
		],
		[
		{"name" : "iPhoto", 		"caption" : "Фото материалы", 			"src" : "images/bPhoto.png"},
		{"name" : "iDocs", 			"caption" : "Документы", 				"src" : "images/bDocs.jpg"},
		]
	];
	
	var table = iMainMenu(interfaces);
	a.ok(
		$(table).find("tr").length == 2 && 
		$(table).find("tr").get()[0].getElementsByTagName("td").length == 2 && 
		$(table).find("td").length == 4 &&
		$(table).find("td").get()[3].getElementsByTagName("img")[0].src == domain+"images/bDocs.jpg"
		,
		"iMainMenu1"
	)

	var interfaces = [
		{"name" : "iMap", 			"caption" : "Объекты на карте", 		"src" : "images/bMap.png"},
		{"name" : "iTable", 		"caption" : "Объекты в таблице", 		"src" : "images/bSearch.jpg"},
		{"name" : "iPhoto", 		"caption" : "Фото материалы", 			"src" : "images/bPhoto.png"},
		{"name" : "iDocs", 			"caption" : "Документы", 				"src" : "images/bDocs.jpg"},
	];
	
	var table = iMainMenu(lineArray2matrixArray(interfaces,2,2));
	a.ok(
		$(table).find("tr").length == 2 && 
		$(table).find("tr").get()[0].getElementsByTagName("td").length == 2 && 
		$(table).find("td").length == 4 &&
		$(table).find("td").get()[3].getElementsByTagName("img")[0].src == domain+"images/bDocs.jpg"
		,
		"iMainMenu2"
	)
	
	var val = getOrm("select name from "+territorialDepartmentTableName, "col2array");
	//a.ok (val.length >= 3, "CAT_TERRITORIAL_DEPARTMENT");

	a.ok( slice("1,2,3,") == "1,2,3", "slice" );
	
	a.ok( val2Arr("abc") == ["abc"], "val2Arr" );

	var comp = cmpOperator;
	a.ok( comp("=", "Тест", "теСт"), "cmpOperator1" );
	a.ok( comp(">", "2ц", "1%"), "cmpOperator2" );
	a.ok( comp("<", "1у", "2.5%"), "cmpOperator3" );
	a.ok( comp("like", "Тест", "ест"), "cmpOperator4" );
	a.ok( comp("!=", "1", "2"), "comp5" );
	a.ok( comp("in", "а", '["а","б","в"]'), "cmpOperator6" );
	a.ok( comp("not in", "а", '["е","б","в"]'), "cmpOperator7" );
	
	var cols = [new Column({name:"f1", caption:"Поле1"}), new Column({name:"f2", caption:"Поле2"}), ];
	a.ok( JSON.stringify(fieldValsFromObjects(cols, "name")) == '["f1","f2"]', "fieldValsFromObjects" );

	var arr = ["add", "view"];
	var obj = arr2obj(arr, "val");
	a.ok( obj.add == "val" && obj.view == "val", "arr2obj" );
	
});

QUnit.test( "jTable.js GetSet class test", function( a ) {
	var getset1 = new GetSet("getset1", "1", null);
	var getset11 = new GetSet("", "q", null);
	var getset2 = new GetSet("getset2", null, function(){
		return getset1.get()+2
	});
	var getset3 = new GetSet("getset3", null, function(){
		return getset2.get()+3
	});
	var getset4 = new GetSet("getset4", null, function(){
		return getset3.get()+4
	});
	var getset5 = new GetSet("getset5", null, function(){
		return getset11.get()+" "+getset4.get()
	});
	
	a.ok(getset2.get() == "12", 1);
	a.ok(getset4.get() == "1234", 2);
	a.ok(getset5.get() == "q 1234", 3);
	getset2.listen([getset1]);
	getset3.listen([getset2]);
	getset4.listen([getset3]);
	getset5.listen([getset1]);
	a.ok(getset2.get() == "12", 4);
	a.ok(getset4.get() == "1234", 5);
	a.ok(getset5.get() == "q 1234", 6);
	getset1.set("a");
	getset11.set("qq");
	a.ok(getset2.get() == "a2", 7);
	a.ok(getset4.get() == "a234", 8);
	a.ok(getset5.get() == "qq a234", 9);
	getset5.unlisten();
	getset5.listen([getset1, getset11]);

	var getset6 = new GetSet("getset6", null, function(){
		return getset11.set(getset11.get()+"q")
	});
	getset6.listen(getset1);
	
	a.ok(getset11.get() == "qqq", 10);
	a.ok(getset5.get() == "qqq a234", 11);
	getset1.set("a");
	a.ok(getset11.get() == "qqq", 12);
	a.ok(getset5.get() == "qqq a234", 13);
	getset1.set("b");
	a.ok(getset11.get() == "qqqq", 14);
	a.ok(getset5.get() != "qqq a234", 15);
	a.ok(getset5.get() != "qqq b234", 16);
	a.ok(getset5.get() != "qqqq a234", 17);
	a.ok(getset5.get() == "qqqq b234", 18);
	
	//toString
	var val = new GetSet("a", null, function(){return {a:1,b:2} });
	a.ok( val == '{"a":1,"b":2}', 19 );
	var val = new GetSet("a", null, function(){return 123 });
	a.ok( val == 123, 20 );
	var val = new GetSet("val", null, function(){return "aaa" });
	a.ok( val == "aaa", 21 );
	
	///get(refresh) return primitive
	var getValue = "123";
	var val = new GetSet("val", null, function(){var result = getValue.length; return result });
	a.ok( val.get() == 3, 22 );
	getValue = "12";
	a.ok( val.get(false) == 3, 23 );
	a.ok( val.get() == 2, 24 );
	
	///get(refresh) return Object
	var getValue = {a:1};
	var val = new GetSet("val", null, function(){var result = getValue; return result });
	a.ok( JSON.stringify(val.get()) == '{"a":1}', 25 );
	getValue.a = 2;
	a.ok( JSON.stringify(val.get()) != '{"a":1}', 26 );
	a.ok( JSON.stringify(val.get()) == '{"a":2}', 27 );
	

});

QUnit.test( "functional-style Class test", function( a ) {
	function ClassC(val, func){
		var that = this;
		var _val = val;
		func();
		
		this.get = function(){
			this.getVal();
			return val;
		}

		this.getVal = function(){
			return _val;
		}
		
		this.getV = function(){
			return this.get()+that.get();
		}
		
		this.set = function(value){
			val = value;
		}

		this.setVal = function(value){
			_val = value;
		}
		
		this.call = function(){
			_val = func();
			return this.getVal();
		}
		
		this.callFunc = func;

		this.getVal();
		
	}

	var cc = new ClassC("val", function(){return 1});
	a.ok(cc.getV() == "valval", "CC1");
	a.ok(cc.getVal() == "val", "CC2");
	cc.set("aaa");
	cc.setVal("bbb");
	a.ok(cc.getV() == "aaaaaa", "CC3");
	a.ok(cc.getVal() == "bbb", "CC4");
	a.ok(cc.call() == 1, "CC5");
	a.ok(cc.getVal() == 1, "CC6");
	a.ok(cc.callFunc() == 1, "CC7");
	
});

QUnit.test( "Column, Filter and CheckValue Class test", function( a ) {
	///Column
	var col = new Column();
	a.ok(
		col.id == 0 &&
		col.name == "" &&
		col.caption == "" &&
		col.width == 100 &&
		col.height == "100%" &&
		col.visible &&
		col.class == ""
	, "Column1")
	
	///CheckValue
	var ch = new CheckedValue();
	a.ok (ch.value == "" && ch.checked == false, "CheckValue1");
	var ch = new CheckedValue({});
	a.ok (ch.value == "" && ch.checked == false, "CheckValue2");
	var ch = new CheckedValue({checked:false});
	a.ok (ch.value == "" && ch.checked == false, "CheckValue3");
	var ch = new CheckedValue({checked:true});
	a.ok (ch.value == "" && ch.checked == true, "CheckValue4");
	var ch = new CheckedValue({checked:""});
	a.ok (ch.value == "" && ch.checked == false, "CheckValue5");
	var ch = new CheckedValue({checked:"123"});
	a.ok (ch.value == "" && ch.checked == true, "CheckValue6");
	var ch = new CheckedValue({value:"123", checked:"123"});
	a.ok (ch.value == "123" && ch.checked == true, "CheckValue7");
	var ch = new CheckedValue({value:[1,2,3], checked:"123"});
	a.ok (ch.value == "1,2,3" && ch.checked == true, "CheckValue8");
	
	//Filter
	var query = " select * from test where 1=1 ";
	var container = document.createElement("DIV");
	var jsTable = new JsTable(query, {tableWidth:100, tableHeight:100}, container);
	var cols = jsTable.columns.get();
	
	//var filter = new Filter({ columns: cols, queryAll: query });
	var filter = jsTable.filter.get()//new Filter({ "jsTable":jsTable });
	var uFilter = " and f1 in ('a1', 'a2') ";
	a.ok( jsTable.rows.get().length == 3, "Filter0" );
	var columnsFilter = filter.columnsFilter.get();
	a.ok( filter instanceof Filter, "Filter1" );
	a.ok( filter.columns.get().length == 3, "Filter3" );
	a.ok( filter.columns.get()[0] instanceof Column, "Filter4" );
	a.ok( columnsFilter.length == 3, "Filter5" );
	a.ok( columnsFilter[0].columnFilter instanceof ColumnFilter, "Filter6" );
	a.ok( columnsFilter[0].userFilter == "", "Filter7" );
	a.ok( filter.columns.get()[0].name == "f1", "Filter8" );
	a.ok( filter.queryAll.get() == query, "Filter9" );

	filter.currentColumn.set(cols[0]);
	a.ok( filter.currentColumn.get() instanceof Column, "Filter10" );
	a.ok( filter.currentColumn.get().name == "f1", "Filter11" );
	var vals = filter.jsDistinctValuesOfCurrentColumn.get();
	a.ok( vals && vals.length && vals.length == 3 && vals[0] == "a1", "Filter12" )
	
	a.ok( columnsFilter.length == 3, "Filter13" );
	a.ok( columnsFilter[0].columnFilter instanceof ColumnFilter, "Filter14" );
	a.ok( columnsFilter[0].columnFilter.checkedValues[0].value == "a1", "Filter15" );

	var pFilter = filter.domPanelFilter.get();
	a.ok( pFilter.getElementsByTagName("DIV").length == 7, "Filter16" );
	a.ok( pFilter.getElementsByTagName("LABEL").length == 4, "Filter17" );
	a.ok( pFilter.getElementsByTagName("INPUT").length == 4, "Filter18" );
	a.ok( pFilter.getElementsByTagName("LABEL")[1].innerHTML == "a1", "Filter19" );
	a.ok( pFilter.getElementsByTagName("LABEL")[3].innerHTML == "a3", "Filter20" );

	filter.userFilter.set(uFilter);
	a.ok( jsTable.rows.get().length == 2, "Filter0" );
	a.ok( filter.userFilter.get() == uFilter, "Filter2" );

	a.ok( getWhereForColumns(cols, "val") == " and (1=2  or `f1` like '%val%'  or `f2` like '%val%'  or `f3` like '%val%' ) ", "getWhereForColumns" );
	
});

QUnit.test( "jTable.js JsTable class test", function( a ) {
	var query = {
		select : "select * from test where 1=1",
		where  : " and 2=2 ",
		order  : " order by f1",
		limit  : " limit 1"
	}
	var container = document.createElement("DIV");
	var jsTable = new JsTable(query, {tableWidth:100, tableHeight:100}, container);
	a.ok(jsTable.querySelect.get() == query.select, "querySelect");
	a.ok(jsTable.queryWhere.get() == query.where, "queryWhere");
	a.ok(jsTable.queryOrder.get() == query.order, "queryOrder");
	a.ok(jsTable.queryLimit.get() == query.limit, "queryLimit");
	a.ok(jsTable.queryAll.get() == query.select+query.where+query.order+query.limit, "queryAll1")
	var limitNum = 3;
	var limitNew = " limit "+limitNum;
	jsTable.queryLimit.set(limitNew);
	a.ok(jsTable.queryAll.get() == query.select+query.where+query.order+limitNew, "queryAll2");

	var jsHead = jsTable.jsHead.get(); 
	a.ok(typeof jsHead == "object", "jsHead1");
	a.ok(jsHead[0].name == "f1", "jsHead2");
	
	var jsBody = jsTable.jsBody.get(); 
	a.ok(typeof jsBody == "object", "jsBody1");
	a.ok(jsBody.length == limitNum, "jsBody2");

	var domHead = jsTable.domHead.get(); 
	a.ok(domHead.getElementsByTagName("label")[0].innerHTML == "f1", "domHead");

	var domBody = jsTable.domBody.get(); 
	a.ok(domBody.getElementsByTagName("textarea")[0].innerHTML == "a1", "domBody");

	
	
///colors	
	var cond1 = new FieldCondition({
		field: "tu", 
		compareType: "=", 
		value: "УЭИ", 
		condType: "or"
	});
	var condStr = JSON.stringify(cond1)
	a.ok( condStr == '{"field":"tu","compareType":"=","value":"УЭИ","condType":"or"}', "FieldCondition");
	
	var colors = [
		new RowColorMarker({
			color: "#ffeeee",
			conditions: [
				cond1
			]
		})
	];
	var colorStr = JSON.stringify(colors);
	a.ok( colorStr == '[{"conditions":['+condStr+'],"color":"#ffeeee"}]', "RowColorMarker");
	
	var comp = cmpOperator;
	var accSucc = function(conds, col, val){
		var result = false;
		var succ = false;

		for (var i=0; i < conds.length; i++){
			var cond = conds[i];
			var field = cond.field.toLowerCase();
			
			if (col == field) {
				succ = comp(cond.compareType, val, cond.value);
			} else {
				succ = false;
			}
			
			if (cond.condType == "and") {
				if (i==0) {
					result = true;
				}
				result = result && succ;
			} else {
				result = result || succ;
			}

		}
		return result;
	}

	var conds = [
		{"field":"f1","compareType":"=","value":"ббб","condType":"or"},
		{"field":"f2","compareType":"=","value":"ааа","condType":"or"},
		{"field":"f1","compareType":"=","value":"ааа","condType":"or"}
	];
	var succ = accSucc(conds, "f1", "Ааа");
	a.ok ( succ, "accSucc1" );
	
	var conds = [
		{"field":"f1","compareType":"=","value":"аАа","condType":"and"},
		{"field":"f1","compareType":"like","value":"а","condType":"and"},
		{"field":"f1","compareType":"in","value":'["ааа","ббб"]',"condType":"and"}
	];
	var succ = accSucc(conds, "f1", "Ааа");
	a.ok ( succ, "accSucc2" );
	
	var conds = [
		{"field":"f1","compareType":"=","value":"аАа","condType":"and"},
		{"field":"f1","compareType":"like","value":"б","condType":"and"},
		{"field":"f1","compareType":"in","value":'["ааа","ббб"]',"condType":"and"}
	];
	var succ = accSucc(conds, "f1", "Ааа");
	a.ok ( !succ, "accSucc3" );

//legend of colors
	var cols = [
		new Column({name:"f1", caption:"Поле1"}),
		new Column({name:"f2", caption:"Поле2"}), 
		new Column({name:"f3", caption:"Поле3"}), 
		new Column({name:"f4", caption:"Поле4"}), 
	];
	
	var cond1 = new FieldCondition({field: "f1", compareType: "=", value: "val1", condType: "and"});
	var cond2 = new FieldCondition({field: "f2", compareType: "=", value: "val2", condType: "and"});
	var cond3 = new FieldCondition({field: "f3", compareType: "=", value: "val3", condType: "and"});
	var cond4 = new FieldCondition({field: "f4", compareType: "=", value: "val4", condType: "and"});
	var marker1 = new RowColorMarker({color: "#ff0000",	conditions: [cond1, cond2]});
	var marker2 = new RowColorMarker({color: "#0000ff",	conditions: [cond3, cond4]});
	var colors = [marker1, marker2];

	var val = domLegendCond(cond1, cols);
	var tds = val.getElementsByTagName("TD");
	
	a.ok(
		tds[0].innerHTML == 'Поле "Поле1"' &&
		tds[1].innerHTML == "=" &&
		tds[2].innerHTML == '"val1"' &&
		tds[3].innerHTML == "и"
		, "domLegendCond" );

	var val = domLegendColor(marker1, cols);
	a.ok(
		val.firstChild.style.backgroundColor == "rgb(255, 0, 0)" &&
		val.lastChild.firstChild.getElementsByTagName('TR').length == 2
		, "domLegendColor"
	);
		
	var val = domLegend(colors, cols);
	a.ok( val.firstChild.childNodes.length == 2, "domLegend" );
	
//	var dom = domCardTable(1);
//	a.ok( dom.firstChild.getElementsByTagName("tr").length == 10 && dom.firstChild.firstChild.firstChild.innerHTML == "rowid", "domCardTable" );
	
});

QUnit.test( "uodb test", function( a ) {
	/*
	var start = new Date();
	var val = uodb.cD();
	var end = new Date();
	console.log(start.getTime() - end.getTime());
	*/
	
	a.ok( uodb.gC(0) == undefined, "gC 1" );
	var c = uodb.gC(1);
	if (c) {
		a.ok( uodb.gC(1).n == "Admin", "gC 2" );
		a.ok( uodb.gC("Admin").id == 1, "gC 3" );
		
		a.ok( uodb.gO("User","Amdin") == undefined, "gO 1");
		a.ok( uodb.gO("User","Admin").id == 1, "gO 2");
		a.ok( uodb.gO("User",1).n == "admin", "gO 3");
		
		a.ok( uodb.gP("User", 20).data.length == 0, "gP 1" );
		a.ok( uodb.gP("ERRORUser", 1) == undefined, "gP 2" );
		a.ok( uodb.gP("User").data.length == 9, "gP 2" );
		a.ok( uodb.gP(3).data.length == 9, "gP 3" );
		a.ok( uodb.gP("User", 1).id == 21, "gP 4" );
	}
	
	
});

QUnit.test( "objectlink test", function( a ) {
	a.ok( 1 == 1, "test" );
	
	if (false) {
		var o1 = objectlink.cO("тест1");
		var o2 = objectlink.cO("тест2");
		var o3 = objectlink.cO("тест3");
		var l1 = objectlink.cL(o1, o2);
		l1 = objectlink.cL(o1, o2);
		var l2 = objectlink.cL(o1, o3);

		a.ok( objectlink.gO("тест1") == o1 && objectlink.gO("тест2") == o2 && objectlink.gO("тест3") == o3, "cO, cL, gO" );
		a.ok( objectlink.gL(o1, o2) == l1 && objectlink.gL(o1, o3) == l2, "gL");
		a.ok(
			objectlink.uO(o1, "тест11") == 1 && 
			objectlink.gN(o1) == "тест11" && 
			objectlink.uO(o1, "тест1") == 1
			, "uO, gN" 
		);
		
		var arr = objectlink.gAND([o2,o3]);
		a.ok( arr.length && arr[0] == o1, "gAND" );
		var arr = objectlink.gAND([o1,o3]);
		a.ok( !arr.length, "gAND 2" );
		
		var arr = objectlink.gOR([o2,o3]);
		a.ok( arr.length && arr[0] == o1, "gOR" );
		var arr = objectlink.gOR([o2]);
		a.ok( arr.length && arr[0] == o1, "gOR 2" );
		var arr = objectlink.gOR([o1]);
		a.ok( arr.length == 2, "gOR 3" );
		var arr = objectlink.gAND([o1,o3]);
		a.ok( !arr.length, "gOR 4" );
		
		objectlink.sql.sql( "delete from link where o1 in (select id from object where n in ('тест1', 'тест2', 'тест3') )" );
		objectlink.sql.sql( "delete from link where o2 in (select id from object where n in ('тест1', 'тест2', 'тест3') )" );
		objectlink.sql.sql( "delete from object where n in ('тест1', 'тест2', 'тест3')" );
	}

	//console.log(objectlink.gN(objectlink.gT()));
	//var tb = sql("select * from transexplobject");
	//var tb = sql("select * from transexplterritorialdepartment");
	//var tb = sql("select * from transexplobjectmanager");
	/*var tb = sql("select * from transexplik");
	var func = function(val){
		console.log(val);
	};*/
	//objectlink.importSQL(tb, func);
	
	/*
	var query = objectlink.getTableQuery([
		{id:1331, n:"ик"},
		{n:"ответственный"},
		{n:"объект"},
		{n:"ту", linkParent:true},
		{n:"email", parentCol:1},
	]);
	
	console.log(query);
	var domtable = orm(query, "all2domtable");
	domtable.setAttribute("border",1);
	$("body").append(
		domtable
	)
	*/
});




