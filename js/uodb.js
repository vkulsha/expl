"use strict";

var uodb = {
	pD	: "z",//database prefix
	pC	: "c",//class table prefix
	pL	: "l",//link table prefix
	cc  : "Admin",//class Class
	pp  : "AdminProp",//class ClassProp
	uu  : "User",//class User

	dcp	: {//default class properties
		id : {name:"id", text:"ID", type:"int"},
		n : {name:"n", text:"Name", type:"varchar(255)"},
		c : {name:"c", text:"Int", type:"int"},
		x : {name:"x", text:"CoordX", type:"float"},
		y : {name:"y", text:"CoordY", type:"float"},
		z : {name:"z", text:"CoordZ", type:"float"},
		s : {name:"s", text:"Status", type:"int"},
		u : {name:"u", text:"User", type:"int"},
		d : {name:"d", text:"Timestamp", type:"timestamp"},
		t : {name:"text", text:"Text", type:"varchar(255)"},
		type : {name:"type", text:"Type", type:"varchar(255)"},
	}, 
	dlp : {//default link properties
		id : {name:"id", text:"ID", type:"int"},
		c1 : {name:"c1", text:"Class1", type:"int"},
		o1 : {name:"o1", text:"Object1", type:"int"},
		c2 : {name:"c2", text:"Class2", type:"int"},
		o2 : {name:"o2", text:"Object2", type:"int"},
		c  : {name:"c", text:"Int", type:"int"},
		s  : {name:"s", text:"Status", type:"int"},
		u  : {name:"u", text:"User", type:"int"},
		d  : {name:"d", text:"Timestamp", type:"timestamp"},
	}, 
	inc	: " NOT NULL AUTO_INCREMENT ",//autoincrement sql command
	key : function(){ return " PRIMARY KEY ("+this.dcp.id.name+") " },//primary key sql command
	def	: function(){ return this.dcp.id.name+" "+this.dcp.id.type+this.inc+","+this.key() },//default create class sql command
	ifo : function(o, intFieldName, stringFieldName){
		intFieldName = intFieldName || this.dcp.id.name;
		stringFieldName = stringFieldName || this.dcp.n.name;
		return (typeof o == "number") ? intFieldName : stringFieldName; 
	},
	get : function(c){
		return (typeof c == "number") ? this.gC(c).n : c;
	},
	sql	: function(query){ 
		var res = sql(query);//func sql from uService.js
		//console.log(query + " ["+res.data[0][0]+"]");
 		return {q:query, result: res} 
	},

	sT	: function(table, fields, cond, order, limit){ return this.sql("select "+fields+" from "+this.pD+table+" where 1=1 "+(cond ? cond : "")+" "+(order ? order : "")+" "+(limit ? limit : "")+";") },//select from table
	iT	: function(table, fields, select){ return this.sql("insert into "+this.pD+table+" "+fields+" "+select+";")	},//insert into table
	uT	: function(table, fieldValue, cond){ return this.sql("update "+this.pD+table+" set "+fieldValue+" where 1=1 "+cond+";") },//update table
	dT	: function(table, cond){ return this.sql("delete from "+this.pD+table+" where "+cond+";") },//delete from table
	cT	: function(table, prop){ return this.sql("create table if not exists "+this.pD+table+" ("+prop+");") },//create table
	aT	: function(table, prop){ return this.sql("alter table "+this.pD+table+" "+prop+";") },//alter table
	eT	: function(table){ return this.sql("drop table if exists "+this.pD+table+";") },//drop table
	xT	: function(name, table, field){ return this.sql("create index "+name+" on "+this.pD+table+" ("+field+");")},//create index
	cV  : function(name, query){ return this.sql("create view "+name+" as "+query) },//create view
	
	cD	: function(){//create Database
		var table = this.pC+this.cc;
		this.cT(table, this.def());//create table ccClass
		
		var table = this.pC+this.pp;
		this.cT(table,//create table ccClassProp
			this.dcp.id.name+" "+this.dcp.id.type+this.inc+","+
			this.dcp.n.name+" "+this.dcp.n.type+", "+
			this.dcp.c.name+" "+this.dcp.c.type+", "+
			this.dcp.x.name+" "+this.dcp.x.type+", "+
			this.dcp.y.name+" "+this.dcp.y.type+", "+
			this.dcp.z.name+" "+this.dcp.z.type+", "+
			this.dcp.s.name+" "+this.dcp.s.type+", "+
			this.dcp.u.name+" "+this.dcp.u.type+", "+
			this.dcp.d.name+" "+this.dcp.d.type+", "+
			this.dcp.t.name+" "+this.dcp.t.type+", "+
			this.dcp.type.name+" "+this.dcp.type.type+", "+
			this.key()
		);
		
		this.cC(this.cc);//create class Class again

		this.cC(this.pp);//create class Prop again
		var pc = {id:1,name:this.pp};
		this.cP(pc, this.dcp.t);//create dop Prop
		this.cP(pc, this.dcp.type);//create dop Prop

		this.cC(this.uu);//create class User
		var o
		o = this.cO(this.uu);
		o = o.result.data[0][0];
		this.uO(this.uu, o, "n", "admin");
	},
	
	cC : function(c){//create Class cC(c):cT(c),cO(cc),cP(pp),uO(cc),cT(lc),sT(cc, c)
		var table = this.pC+c;
		this.cT(table, this.def());//create table ccNewClass
		
		var o;
		o = this.cO(this.cc);//create object in class Class
		o = o.result.data[0][0];
		
		var pc = {id:o,name:c};
		this.cP(pc, this.dcp.id);//create prop in class ClassProp
		this.cP(pc, this.dcp.n);
		this.cP(pc, this.dcp.c);
		this.cP(pc, this.dcp.x);
		this.cP(pc, this.dcp.y);
		this.cP(pc, this.dcp.z);
		this.cP(pc, this.dcp.s);
		this.cP(pc, this.dcp.u);
		this.cP(pc, this.dcp.d);

		this.uO(this.cc, o, this.dcp.n.name, c);//update object in class Class - set name of new Class
		
		var table = this.pL+c;
		this.cT(table,//create link table clNewClass
			this.dlp.id.name+" "+this.dlp.id.type+this.inc+","+
			this.dlp.c1.name+" "+this.dlp.c1.type+", "+
			this.dlp.o1.name+" "+this.dlp.o1.type+", "+
			this.dlp.c2.name+" "+this.dlp.c2.type+", "+
			this.dlp.o2.name+" "+this.dlp.o2.type+", "+
			this.dlp.c.name +" "+this.dlp.c.type +", "+
			this.dlp.s.name +" "+this.dlp.s.type +", "+
			this.dlp.u.name +" "+this.dlp.u.type +", "+
			this.dlp.d.name +" "+this.dlp.d.type +", "+
			this.key()
		);

		var table = this.pC+this.cc;
		return this.sT(table, "max("+this.dcp.id.name+")");//return select id of new object from table ccNewClass
		
	},
	cP : function(c, p){//create Prop cP(c):aT(c),xT(c),cO(pp),uO(pp),sT(pp, p)
		var table = this.pC+c.name;
		this.aT(table, " add column "+p.name+" "+p.type);//add column newProp into table ccNewClass
		
		this.xT("i"+table+p.name, table, p.name);//add index for column newProp into table ccNewClass
		
		var n;
		n = this.sT(this.pC+this.pp, "count(*)", "and "+this.dcp.y.name+" = "+c.id);//select count props from class ClassProp = newClass
		n = n.result.data[0][0];
		var o;
		o = this.cO(this.pp);//create object newProp in class ClassProp
		o = o.result.data[0][0];

		this.uO(this.pp, o, this.dcp.x.name, parseInt(n));//update object newProp in class ClassProp
		this.uO(this.pp, o, this.dcp.y.name, c.id);
		this.uO(this.pp, o, this.dcp.n.name, p.name);
		this.uO(this.pp, o, this.dcp.s.name, 1);
		this.uO(this.pp, o, this.dcp.t.name, p.text);
		this.uO(this.pp, o, this.dcp.type.name, p.type);

	},
	cO : function(c){//create Object cO(c):iT(c),sT(c)
		var table = this.pC+this.get(c);
		this.iT(table, "("+this.dcp.s.name+")", "select 1");//insert new object into table ccNewClass
		
		return this.sT(table, "max("+this.dcp.id.name+")");//return select id of new object from table ccNewClass
	},
	cLC: function(c1, c2){//create Link Class cLC(c1,c2):iT(lcc, c1,c2)
		var table = this.pL+this.cc;
		this.iT(table, "("+this.dlp.o1.name+", "+this.dlp.o2.name+")", "select "+c1+", "+c2+" ");
	},
	cLO: function(c1, o1, c2, o2){//create Link Object cLO(c1,o1,c2,o2):iT(lc1, o1,c2,o2),iT(lc2, o2,c1,o1)
		var table = this.pL+this.gC(c1).n;
		this.iT(table, "("+this.dlp.c1.name+", "+this.dlp.o1.name+", "+this.dlp.c2.name+", "+this.dlp.o2.name+" )", "select "+c1+", "+o1+", "+c2+", "+o2+" ");
		
		var table = this.pL+this.gC(c2).n;
		this.iT(table, "("+this.dlp.c1.name+", "+this.dlp.o1.name+", "+this.dlp.c2.name+", "+this.dlp.o2.name+" )", "select "+c2+", "+o2+", "+c1+", "+o1+" ");
	},
	
	uC : function(c, p, value){//update Class uC(c):uO(cc, c)
		this.uO(this.cc, c, p, value);
	},
	uO : function(c, o, p, value){//update Object uO(c,o):uT(c, o)
		var table = this.pC+this.get(c);
		this.uT(table, p+"='"+value+"'", " and "+this.ifo(o)+"="+o);//update table ccNewClass set p=value where id = o
	},
	uP : function(c, p, value){//update Prop uP(c,p):uO(pp, c,p)
		var table = this.pC+this.pp;
		this.uT(table, p+"='"+value+"'", " and y="+c+" "+this.ifo(p)+"="+p);
		
	},
	uLC: function(c1, c2){//update Link Class uLC(c1,c2):uT(lcc, c1,c2)
	},
	uLO: function(c1, o1, c2, o2){//update Link Object uLO(c1,o1,c2,o2):uT(lc1, o1,c2,o2),uT(lc2, o2,c1,o1)
	},

	gC : function(c){//get Class gC(c):gO(cc, c)
		return this.gO(this.cc, c);
	},
	gO : function(c, o){//get Object gO(c,o):gC(cc),sT(c, o)
		c = (c == this.cc) ? {n:this.cc, s:1} : this.gC(c);
		
		if (c && c.s) {
			var table = this.pC+c.n;
			var result = this.sT(table, "*", " and "+( this.ifo(o) )+"='"+o+"' ");
			if (!result.result || !result.result.data || !result.result.data[0] || !result.result.data[0][0]) return undefined;
			return getOrmObject(result.result, "row2object");
		} else {
			return undefined
		}
	},
	gP : function(c, p){//get Class Props gP(c, p):gC(cc),sT(pp, c, p) = []
		c = (c == this.cc) ? {n:this.cc, s:1} : this.gC(c);
		p = p ? " and " + ( this.ifo(p, this.dcp.x.name) )+"='"+p+"' " : "";
		
		if (c && c.s) {
			var table = this.pC+this.pp;
			var result = this.sT(table, "*", " and y="+c.id+" "+p);
			if (!result.result || !result.result.data || !result.result.data[0] || !result.result.data[0][0]) return undefined;
			return getOrmObject(result.result);
		} else {
			return undefined
		}
	},
	gLC: function(c){//get Links of Class gLC(c):gC(cc),sT(lcc, c) = []
	},
	gLO: function(c, o){//get Links of Object gLO(c,o):gC(cc),sT(lc, o) = []
	},
	
	dO : function(c, o){//disable Object dO(c,o):uO(c, o),uLO(c, o),gLO(c, o)>uLO(*,*,c,o)
		uO(c, o,"s", 0);
	},
	dC : function(c){//disable Class dC(c):uC(c),uLC(c)
		uC(c, "s", 0);
	},
	dP : function(c, p){//disable Prop dP(c,p):uP(c, p)
		uP(c, "s", 0);
	},
	dLC: function(c1, c2){//disable Link Class dLC(c1,c2):uLC(c1,c2)
	},
	dLO: function(c1, o1, c2, o2){//disable Link Object dLO(c1,o1,c2,o2):uLO(c1, o1,c2,o2)
	},
	
	eO : function(c, o){//erase Object eO(c,o):dT(c, o),dT(lc, o),gLO(c, o)>dT(lc*, o)
	},
	eC : function(c){//erase Class eC(c):dT(cc, c),eT(c),eT(lc),gLC(c)>dT(lc*, c)
	},
	eP : function(c, p){//erase Prop eP(c,p):aT(c, p),dT(pp, c,p)
	},
	eLC: function(c1, c2){//erase Link Class eLC(c1,c2):dT(lcc, c1,c2),gLC(c)>dT(lc*, c2),gLC(c2)>dT(lc*, c1)
	},
	eLO: function(c1, o1, c2, o2){//erase Link Object eLO(c1,o1,c2,o2):dT(lc1, o1,c2,o2),dT(lc2, o2,c1,o1)
	},
	
	
	
};

var SQL = {
	sql	: function(query){ 
		var res = sql(query);//func sql from uService.js
		//console.log(query + " ["+res.data[0][0]+"]");
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
	cc  : "class",//view return table classes
	ol  : "objectlink",//view return table all objects join with links

	o1o2 : function(o1, o2){
		return " and ((o1="+o1+" and o2="+o2+") or (o1="+o2+" and o2="+o1+"));";
	},

	sql : SQL,
	
	cD	: function(){
		this.cT(this.oo, "id bigint not null auto_increment, n char(255), d timestamp, /*x float, y float, z float, */primary key(id), index(n);");
		this.cT(this.ll, "id bigint not null auto_increment, o1 bigint, o2 bigint, c bigint, primary key(id), index(o1), index(o2), index(c)");
		this.cV(this.cc, 
			"select o1.id, ifnull(link.o2, '#') parent, o1.n text from (select * from object where id in (select o1 from link where o2 is null))o1 "+
			"left join link on o2 is not null and o1 = o1.id"
		);
		this.cV(this.ol, 
			"select o.id, o.n, link.o2 from (select * from object) o "+
			"left join link on o2 is not null and o1 = o.id "
		);
		
		var o = this.cO("Classes");
		this.cL(o, "null");
		return {q:"init database", result: "true"};
	},
	
	cO : function(n){//return created object.id
		this.sql.iT(this.oo, "(n)", "select "+n);
		return this.sql.sT(table, "max(id)").result.data[0][0];
	},
	cL : function(o1, o2){//return created/count of updated link.id
		var count = this.sql.sT(table, "count(*)", this.o1o2)
		count = count.result.data[0][0];
		if (count > 0) {
			return this.sql.uT(this.ll, "c = c+1", this.o1o2);
		} else {
			return this.sql.iT(this.ll, "(o1, o2, c)", "values ("+o1+", "+o2+", 1)");
		}
	},
	gO : function(n){//return object.id from name
		return this.sql.sT(this.oo, "id", " and n='"+n+"'", "", "limit 1").result.data[0][0];
	},
	gN : function(id){//return object.n
		return this.sql.sT(this.oo, "n", " and id="+id).result.data[0][0];
	},
	gL : function(o1, o2){//return link.id
		return this.sql.sT(this.ll, "id", this.o1o2).result.data[0][0];
	},
	uO : function(id, n){//return count of updated objects
		var result = this.sql.uT(this.oo, "n = '"+n+"'", "and id = "+id);
		//chRs(id);
		return result;

	},

	gC : function(){//return view `class`
		return this.sql.sT(this.cc, "*");
		
	},
	gOR : function(arr){//return objects linked with any object from list arr //analog OR logic
		var cond = " and o2 in ("+arr.join(",")+")" || " and 1=2 ";
		return this.sql.sT(this.ol, "*", cond);
		
	},
	gAND : function(arr){//return objects linked with every object from list arr //analog AND logic
		var cond = " and o2 in ("+arr.join(",")+") group by id having count(id) = "+arr.length || " and 1=2 ";
		return this.sql.sT(this.ol, "*", cond);
	},
	cR : function(ruleName, executor, condObjectFrom, condObjTo, subjectFrom, subjectTo){//return created object-rule
		var result = this.cO(ruleName);
		
		var cond = gO(condObjTo);
		cond = cond || cO(condObjTo);
		
		var subject = gO(subjectTo);
		subject = subject || cO(subjectTo);
		
		cL(subjectFrom, subject);
		cL(condObjectFrom, cond);
		
		cL(result, executor);
		cL(result, condObjectFrom);
		cL(result, cond);
		cL(result, subjectFrom);
		cL(result, subject);
		
		return result;
	},
	chRs : function(id, currentUser){//checked rules with condition=id
		if (id) {
			var isRuleCond = gL(id, gO("правило условие"));
			if (isRuleCond) {
				var rules = gAND([id, gO("правило")/*, gO("справочник")*/]).result.data;
				for (var i=0; i < rules.length-1; i++) {
					chR(rules[i], currentUser);
				}
			}
		}
		
	},
	chR : function(rule, currentUser){//checked or execute rule, return rule execution result or undefined
		var result;
		var executor = gAND([rule, gO("правило исполнитель")]).result.data[0][0];
		if (executor == currentUser) {
			result = gO("правило исполнено");
			if (!result) {
				result = cO("правило исполнено")
			};
			
			if (!isObjectHasLink(result)) {
				var condition = gAND([rule, gO("правило условие")]).result.data[0][0];
				var conditionCopy = gAND([rule, gO("правило условие сравнение")]).result.data[0][0];
				if (condition == conditionCopy) {
					if (gAND([executor, gO("система")]).result.data[0][0]) {
						var subject = gAND([rule, gO("правило субъект")]).result.data[0][0];
						var subjectCopy = gAND([rule, gO("правило субъект состояние")]).result.data[0][0];
						if (subject) {
							uO(subject, gN(subjectCopy));
							chRs(subject, currentUser);
						} else {
							//cO(subjectCopy);
						}
						cL(rule, result);
						
					} else {
						result = gO("правило не исполнено") || cO("правило не исполнено");
						if (!result) {
							result = cO("правило не исполнено");
						};
						cL(rule, result);
					}
				}
			}
		}
		return result;
	}
}