"use strict";

var SQL = {
	sql	: function(query){ 
		var res = sql(query);//func sql from uService.js
		//console.log(query + " ["+res.data+"]");
		//console.log(res);
 		return {q:query, result: res} 
	},
	pD	: "",

	sT	: function(table, fields, cond, order, limit){ return this.sql("select "+fields+" from "+this.pD+table+" where 1=1 "+(cond ? cond : "")+" "+(order ? order : "")+" "+(limit ? limit : "")+";") },//select from table
	iT	: function(table, fields, select){ return this.sql("insert into "+this.pD+table+" "+fields+" "+select+";")	},//insert into table
	uT	: function(table, fieldValue, cond){ return this.sql("update "+this.pD+table+" set "+fieldValue+" where 1=1 "+cond+";") },//update table
	dT	: function(table, cond){ return this.sql("delete from "+this.pD+table+" where 1=1 "+cond+";") },//delete from table
	cT	: function(table, prop){ return this.sql("create table if not exists "+this.pD+table+" ("+prop+");") },//create table
	aT	: function(table, prop){ return this.sql("alter table "+this.pD+table+" "+prop+";") },//alter table
	eT	: function(table){ return this.sql("drop table if exists "+this.pD+table+";") },//drop table
	xT	: function(name, table, field){ return this.sql("create index "+name+" on "+this.pD+table+" ("+field+");")},//create index
	cV  : function(name, query){ return this.sql("create view "+name+" as "+query) },//create view
	
}

var objectlink = {
	oo  : "object",
	ll  : "link",

	o1o2 : function(o1, o2){
		return " and ((o1="+o1+" and o2="+o2+") or (o1="+o2+" and o2="+o1+")) ";
	},

	sql : SQL,
	
	cD	: function(){
		this.cT(this.oo, "id bigint not null auto_increment, n char(255), d timestamp, c bigint, primary key(id), index(n), index(d), index(c);");
		this.cT(this.ll, "id bigint not null auto_increment, o1 bigint, o2 bigint, c bigint, d timestamp, primary key(id), index(o1), index(o2), index(c), index(d)");
		var o = this.cO("Класс");
		return {q:"init database", result: "true"};
	},
	
	cO : function(n, pid){//return created object.id
		this.sql.iT(this.oo, "(n)", "select "+ifns(n));
		var oid = this.sql.sT(this.oo, "max(id)").result.data[0][0];
		if (pid) {
			this.cL(oid, pid);
		}
		return oid;
	},
	cL : function(o1, o2){//return created/count of updated link.id
		this.sql.iT(this.ll, "(o1, o2, c)", "values ("+o1+", "+o2+", 1)");
		var s = parseInt(this.gS(o1))+1 || 1;
		this.uS(o1, s);
		var s = parseInt(this.gS(o2))+1 || 1;
		this.uS(o2, s);
		return s;
	},
	
	gO : function(n){//return object.id from name
		var result = this.sql.sT(this.oo, "id", " and n='"+n+"'", "order by id", "limit 1").result.data;
		result = result.length ? result[0][0] : undefined;
		return result;
	},
	gN : function(id){//return object.n
		var result = this.sql.sT(this.oo, "n", " and id="+id, "", "limit 1").result.data;
		result = result.length ? result[0][0] : undefined;
		return result;
	},
	gS : function(id){
		var result = this.sql.sT(this.oo, "c", " and id="+id, "", "limit 1").result.data;
		result = result.length ? result[0][0] : undefined;
		return result;
	},
	gD : function(id){
		var result = this.sql.sT(this.oo, "d", " and id="+id, "", "limit 1").result.data;
		result = result.length ? result[0][0] : undefined;
		return result;
	},
	gL : function(o1, o2){//return link.id
		var result = this.sql.sT(this.ll, "id", this.o1o2(o1, o2), "", "limit 1").result.data;
		result = result.length ? result[0][0] : undefined;
		return result;
	},
	
	uO : function(id, n){//return count of updated objects
		var result = this.sql.uT(this.oo, "n = '"+n+"'", "and id = "+id).result.data[0][0];
		//chRs(id);
		return result;
	},
	uS : function(id, c){
		var result = this.sql.uT(this.oo, "c = '"+c+"'", "and id = "+id).result.data[0][0];
		return result;
	},
	uL : function(id, c){
		var result = this.sql.uT(this.ll, "c = '"+c+"'", "and id = "+id).result.data[0][0];
		return result;
	},
	
	dL : function(id){
		var result = this.uL(id, 0);
		return result;
	},
	dO : function(id){
		var result = getOrmObject(
			this.sql.sql("select id from link where o2 = "+id+" or o1 = "+id).result
			, "col2array"
		);
		
		for (var i=0; i < result.length; i++){
			this.dL(result[i]);
		}
		this.cL(id, gO("вымысел") || cO("вымысел"));
	},
	eO : function(id){
		this.sql.dT(this.ll, " and (o1 = "+id+" or o2 = "+id+") ");
		this.sql.dT(this.oo, " and id = "+id);
	},
	eL : function(o1, o2){
		return this.sql.dT(this.ll, " and ((o1 = "+o1+" and o2="+o2+") or (o2 = "+o1+" and o1="+o2+"))").result.data[0][0];
	},

	gC : function(id, fields){//get class object fields
		return this.sql.sql("select "+(fields ? fields : "*")+" from object where id in (select o2 from link where o2 in (select o1 from link where o2 = (select id from object where n='класс' limit 1)) and o1 = "+id+")").result.data;
	},
	gCn : function(id){//get class object names
		return this.gC(id, "n")
	},
	gCid : function(id){//get class object ids
		return this.gC(id, "id")
	},
	gOCQ : function(className){
		return ""+
			"select * from object where id in ( "+
			"select o1 from link where o2 = "+classes[className]+" "+
			"			and o1 not in (select o1 from link where o2 = 1) "+
			") ";
		
	},
	gCQ : function(){
		return "select o1.id, ifnull(link.o2, '#') parent, o1.n text from (select * from object where id in (select o1 from link where o2 = 1))o1 "+
				"left join link on o1 = o1.id and o2 <> 1 ";
		
	},
	gAND : function(arr){//return objects linked with every object from list arr //analog AND logic
		return getOrmObject(
			this.sql.sql(
				"select o1 from ( "+
				"	select o1 from link where o2 in ("+arr.join(",")+") and o1 is not null and o2 <> 1 "+
				"	union all "+
				"	select o2 from link where o1 in ("+arr.join(",")+") and o2 is not null and o1 <> 1 "+
				")o "+
				"group by o1 "+
				"having count(*) = "+arr.length+" "+
				"and (o1 not in (select o1 from link where o2 = 1) "+
				"	or o1 = 1) "+
				"order by o1"
			).result
			, "col2array"
		);
	},
	//gT : function(){
	//	return this.gAND([this.gO("время")])[0]
	//},
	cR : function(ruleName, executor, condObjectFrom, condObjTo, subjectFrom, subjectTo){//return created object-rule
		var result = this.cO(ruleName);
		var ruleclass = this.gO("правило") || this.cO("правило");
		
		var cond = this.gO(condObjTo) || this.cO(condObjTo);
		var subject = this.gO(subjectTo) || this.cO(subjectTo);
		
		this.cL(subjectFrom, subject);
		this.cL(condObjectFrom, cond);
		
		this.cL(result, ruleclass);
		this.cL(result, executor);
		this.cL(result, condObjectFrom);
		this.cL(result, cond);
		this.cL(result, subjectFrom);
		this.cL(result, subject);
		
		return result;
	},
	chRs : function(id, currentUser){//checked rules with condition=id
		if (id) {
			var isRuleCond = this.gL(id, this.gO("правило условие"));
			if (isRuleCond) {
				var rules = this.gAND([id, this.gO("правило")]);
				if (rules && rules.length) {
					for (var i=0; i < rules.length-1; i++) {
						this.chR(rules[i], currentUser);
					}
				}
			}
		}
		
	},
	chR : function(rule, currentUser){//checked or execute rule, return rule execution result or undefined
		var result;
		var executor = this.gAND([rule, this.gO("правило исполнитель")])[0];
		if (executor == currentUser) {
			result = this.gO("правило статус исполнено") || this.cO("правило статус исполнено");
			
			if (!gL(rule, result)) {
				var condition = this.gAND([rule, this.gO("правило условие")])[0];
				var conditionCopy = this.gAND([rule, this.gO("правило условие сравнение")])[0];
				if (this.gN(condition) == this.gN(conditionCopy)) {
					if (executor == this.gO("система")) {
						var subject = this.gAND([rule, this.gO("правило субъект")])[0];
						var subjectCopy = this.gAND([rule, this.gO("правило субъект состояние")])[0];
						if (subject) {
							this.uO(subject, this.gN(subjectCopy));
							this.chRs(subject, currentUser);
						} else {
							this.cO(this.gN(subjectCopy));
						}
						this.cL(rule, result);
						
					} else {
						result = this.gO("правило статус неисполнено") || this.cO("правило статус неисполнено");
						this.cL(rule, result);
					}
				}
			}
		}
		return result;
	},
	importSQL : function(params, fDebug){//import data from sql table to object-link //params={"table" : "", "id" : 1, "columns" : [], "data" : [][]}
		/*if (fDebug) fDebug("создание таблицы");
		var oTable = this.cO(params.table);*/
		var oClass = this.gO("класс") || this.cO("класс");
		var keyFieldIndex = params.id ? params.id : 0;

		if (fDebug) fDebug("создание полей");
		var oColumns = [];
		for (var i=0; i < params.columns.length; i++){
			oColumns.push(this.cO(params.columns[i]));
		}
				
		/*if (fDebug) fDebug("привязка полей к таблице");
		for (var i=0; i < params.columns.length; i++){
			this.cL(oColumns[i], oTable);
		}*/
		
		if (fDebug) fDebug("привязка полей к объекту-класс");
		for (var i=0; i < params.columns.length; i++){
			this.cL(oColumns[i], oClass);
		}

		if (fDebug) fDebug("привязка полей к ключевому полю");
		for (var i=0; i < oColumns.length; i++){
			if (i != keyFieldIndex) {
				this.cL(oColumns[i], oColumns[keyFieldIndex]);
			}
		}
			
		if (fDebug) fDebug("создание значений");
		var oData = [];
		for (var i=0; i < params.data.length; i++){
			var oDataRow = [];
			for (var j=0; j < params.data[i].length; j++){
				var oid = null;
				if (params.data[i][j]) {
					oid = this.cO(params.data[i][j]);
				}
				oDataRow.push(oid);
			}
			oData.push(oDataRow);
		}

		if (fDebug) fDebug("привязка значений всего столбца к полю по всем полям");
		for (var i=0; i < oData.length; i++){
			for (var j=0; j < oData[i].length; j++){
				if (oData[i][j]) {
					this.cL(oData[i][j], oColumns[j]);
				}
			}
		}
			
		if (fDebug) fDebug("привязка значений всех полей записи к записи по всем записям");
		for (var i=0; i < oData.length; i++){
			for (var j=0; j < oData[i].length; j++){
				if (j != keyFieldIndex) {
					if (oData[i][j]) {
						this.cL(oData[i][j], oData[i][keyFieldIndex]);
					}
				}
			}
		}
	},
	getTableQuery : function(params, groupbyind){
		var result = [];
		params = params || [];//[{id:1331, n:"ик", parentCol:0, linkParent:false}]
		if (groupbyind != false) {
			groupbyind = groupbyind || "0";
		}
		
		var head = [];
		var body = [];
		var foot = [];
		var i = -1;
		for (var ind=0; ind < params.length; ind++){
			var cc = params[ind];
			i = ind;
			if (cc.n) {
				//i++;
				var col = cc.n;
				var cid = classes[cc.n] || "null";
				
				if (i==0){
					var h = "select o"+i+".id `id "+col+"`, o"+i+".n `"+col+"` \n";
					var l = cc.id ? cc.id : cid;
					var b = 
						"from (#main class \n"+
						"	select id, n from object where id in ( \n"+
						"		select o1 from link where o2 = "+l+" \n"+
						(cc.inClass ? "" : "and o1 not in (select o1 from link where o2 = 1) \n")+
						"	) \n"+
						"	group by id \n"+
						")o"+i+" \n";
					head.push(h);
					body.push(b);
				} else {
					var h;
					if (groupbyind) {
						h = ",case when count(distinct o"+i+".id) <= 1 then group_concat(distinct o"+i+".id) else concat(o"+i+".id,'..') end `id "+col+"` "+
							",case when count(distinct o"+i+".id) <= 1 then group_concat(distinct o"+i+".n)  else concat(o"+i+".n,'..')  end `"+col+"` "+
							",count(distinct o"+i+".id) `кол-во "+col+"` \n";
					} else {
						h = ",o"+i+".id `id "+col+"` "+
							",o"+i+".n `"+col+"` ";
					}
					//var h = ",case when count(distinct o"+i+".id) <= 1 then o"+i+".n else count(distinct o"+i+".id) end `"+col+"` \n";
					var l = cc.id ? cc.id : cid;
					var selecto1o2 = cc.linkParent ? "select o1 o2, o2 o1 from link where o2 in (" : "select o1, o2 from link where o1 in (";
					var parentCol = cc.parentCol ? cc.parentCol : 0;
					var b = 
						"left join ( \n"+
						"	"+selecto1o2+" \n"+
						"		select o1 from link where o2 = "+l+" \n"+
						(cc.inClass ? "" : "and o1 not in (select o1 from link where o2 = 1) \n")+
						"	) \n"+
						"	group by o1, o2 \n"+
						")l"+i+" on l"+i+".o2 = o"+parentCol+".id left join object o"+i+" on o"+i+".id = l"+i+".o1 \n";
					
					head.push(h);
					body.push(b);
				}
			}
		}
		
		if (groupbyind) {
			foot.push("group by o"+groupbyind+".id having 1=1 \n\n");
		}
		result = head.join("")+body.join("")+foot.join("foot");
		return result;
		//return objectlink.gOrm("getTableQuery", [params, groupbyind]);
	},
/*	getClassLinkLevelQuery : function(fromClassName, toClassId, maxLevel){
		maxLevel = maxLevel || 1;
		var result = "";
		var head = [];
		var body = [];
		var body2 = [];
		var cond1 = [];
		var cond2 = [];
		var order = [];
		for (var i=1; i <= maxLevel; i++){
			head.push(" level"+i+".o1 o"+i+", obj"+i+".n n"+i+", level"+i+".t t"+i+""+(i < maxLevel ? "," : " "));
			body.push((i==1 ? "from" : "left join")+" (select o1, o2, 'child' t from link union all select o2, o1, 'parent' from link)level"+i+" "+(i == 1 ? "" : "on level"+i+".o2 = level"+(i-1)+".o1")+"\n");
			body2.push("left join object obj"+i+" on obj"+i+".id = level"+i+".o1 \n");
			cond1.push("level"+i+".o1 = "+toClassId+(i < maxLevel ? " or " : ""));
			cond2.push("and level"+i+".o1 in (select o1 from link where o2 = 1 and o1 <> 1) \n");
			order.push(" when level"+i+".o1 = "+toClassId+" then "+i+" ");
		}
		result = "select "+
			head.join("")+"\n"+
			body.join("")+"\n"+
			body2.join("")+
			"where 1=1 \n"+
			cond2.join("")+
			"and ("+cond1.join("")+
			") \n"+"and level1.o2 = "+classes[fromClassName]+" \n"+
			" order by case "+
			order.join("")+
			" else 1000 end";
		return result;
	},
	addClass2jsQuery : function(jsQ, newClassId, maxLevel){
		for (var i=0; i < jsQ.length; i++){
			var q = jsQ[i];
			var sql = this.getClassLinkLevelQuery(q.n, newClassId, maxLevel);
			var chain = lineArray2matrixArray(orm(sql, "row2array"), maxLevel, 3);
			var ind = i;
			for (var j=0; j < chain.length; j++){
				jsQ.push({"n":chain[j][1], "parentCol":ind, "linkParent":(chain[j][2] == "parent")});
				ind = jsQ.length-1;
				if (chain[j][0] == newClassId) return;
			}
		}
	},
	getlinkedObjectsQuery : function(id, className, isParent, isClass){
		var classes = this.gCn(id);
		if (classes.length) {
			var c = classes[0];
			return this.getTableQuery2([className, c],[],[!isParent ? 1 : 0],(isClass ? [0,1] : []))+" and `id "+c+"` = "+id;
		}
		
	},
	getlinkedObjects : function(id, className, isParent, isClass){
		var q = this.getlinkedObjectsQuery(id, className, isParent, isClass);
		return orm(q, "all2array");
	},
	getObjectByLinkedObjectQuery : function(class1Name, class2Name){
		return ""+
			"select o2 from ( "+
			"	select o1, o2 from link where o1 in (select o1 from link where o2 = "+classes[class2Name]+") "+
			"	and o2 in (select o1 from link where o2 = "+classes[class1Name]+" and o1 not in (select o1 from link where o2 = (select id from object where n='Класс' limit 1))) "+
			")link join object on object.id = link.o1 ";
	},
	getObjectByLinkedObject : function(class1Name, class2Name, class2ObjectName){
		var q = this.getObjectByLinkedObjectQuery(class1Name, class2Name);
		var ret = orm(q+" and n='"+class2ObjectName+"'", "all2array");
		if (ret.length) {
			return ret[0][0];
		} else {
			return undefined
		}
	},*/
	getObjectFromClass : function(className, n){
		var q = this.gOCQ(className);
		var ret = orm("select id from ("+q+")xx where 1=1 and n='"+n+"'", "all2array");
		if (ret.length) {
			return ret[0][0];
		} else {
			return undefined
		}
	},
/*	getObjectsFromClass : function(className){
		var q = this.gOCQ(className);
		var ret = orm(q, "all2array");
		if (ret.length) {
			return ret;
		} else {
			return undefined
		}
	},*/
	getTableQuery2 : function(nArr, parentColArr, linkParentArr, inClassArr){//["a","b","c"], [[1,0],[2,0],[3,1]], [1,2], [1]
		nArr = nArr || [];
		parentColArr = parentColArr || [];
		linkParentArr = linkParentArr || [];
		inClassArr = inClassArr || [];
		var opts = [];
		for (var i=0; i < nArr.length; i++){
			opts.push({n:nArr[i], parentCol:0, linkParent:false});
		}
		for (var i=0; i < parentColArr.length; i++){
			opts[parentColArr[i][0]].parentCol = parentColArr[i][1];
		}
		for (var i=0; i < linkParentArr.length; i++){
			opts[linkParentArr[i]].linkParent = true;
		}
		for (var i=0; i < inClassArr.length; i++){
			opts[inClassArr[i]].inClass = true;
		}
		return "select * from ("+this.getTableQuery(opts, false)+")x where true ";
	},
	gOrm : function(funcName, params){
		return sqlOrm({f:funcName, p:params})	
		
	},
	gOrmA : function(funcName, params, func){
		sqlOrmA({f:funcName, p:params}, func)	
		
	},
}














