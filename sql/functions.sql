drop procedure cD;
create procedure cD()
begin
	declare oid1 bigint;
	declare oid2 bigint;
	
	start transaction;

	create table if not exists object (
		id bigint not null auto_increment,
		n char(255),
		d timestamp,
		primary key(id),
		index(n)
	);

	create table if not exists link (
		id bigint not null auto_increment,
		o1 bigint,
		o2 bigint,
		c bigint,
		d timestamp,
		primary key(id),
		index(o1),
		index(o2),
		index(c)

	);

	insert into object (n) select 'Система';

	insert into object (n) select 'Время';
	set oid1 = (select LAST_INSERT_ID());
	insert into object (n) select CURRENT_TIMESTAMP();
	set oid2 = (select LAST_INSERT_ID());
	insert into link (o1, o2, c) values (oid1, oid2, 1);

	insert into object (n) select 'Класс';

	insert into object (n) select 'Правило';
	set oid1 = (select LAST_INSERT_ID());
	insert into object (n) select 'Правило Исполнитель';
	insert into link (o1, o2, c) values (oid1, (select LAST_INSERT_ID()), 1);
	insert into object (n) select 'Правило Условие';
	insert into link (o1, o2, c) values (oid1, (select LAST_INSERT_ID()), 1);
	insert into object (n) select 'Правило Условие Сравнение';
	insert into link (o1, o2, c) values (oid1, (select LAST_INSERT_ID()), 1);
	insert into object (n) select 'Правило Субъект';
	insert into link (o1, o2, c) values (oid1, (select LAST_INSERT_ID()), 1);
	insert into object (n) select 'Правило Субъект Сравнение';
	insert into link (o1, o2, c) values (oid1, (select LAST_INSERT_ID()), 1);
	insert into object (n) select 'Правило Статус Исполнено';
	insert into link (o1, o2, c) values (oid1, (select LAST_INSERT_ID()), 1);
	insert into object (n) select 'Правило Статус Неисполнено';
	insert into link (o1, o2, c) values (oid1, (select LAST_INSERT_ID()), 1);

	drop view class;
	create view class as
		select o1.id, ifnull(link.o2, '#') parent, o1.n text from (select * from object where id in (select o1 from link where o2 = (select id from object where n = 'класс')))o1 
		left join link on o1 = o1.id and o2 <> (select id from object where n = 'класс') 

	drop view objectlink;
	create view objectlink as
		select o.id, o.n, link.o2 from (select * from object where id not in (select o1 from link where o2 = (select id from object where n = 'класс'))) o 
		left join link on o1 = o.id and o1 not in (select o1 from link where o2 = (select id from object where n = 'класс'))
	
	commit;
end;

drop function cO;
create function cO (val char(255)) 
returns bigint(20)
begin
	insert into object (n) value(val);
	return (select max(id) from object);

end;

drop procedure cL;
create procedure cL(oid1 bigint, oid2 bigint)
begin
	if ( select count(*) from link where (o1=oid1 and o2=oid2) or (o1=oid2 and o2=oid1) ) > 0 then
		update link set c = c+1 where (o1=oid1 and o2=oid2) or (o1=oid2 and o2=oid1);

	else
		insert into link (o1, o2, c) values (oid1, oid2, 1);

	end if;	
end;
	
drop procedure uO;
create procedure uO (oid bigint, val char(255))
begin
	update object set n = val where id = oid;

end;
	
drop function gN;
create function gN (oid bigint)
returns char(255)
return (select n from object where id = oid limit 1);

drop function gO;
create function gO (n_ char(255))
returns bigint(20)
return (select id from object where n = n_ limit 1);

drop function gL;
create function gL(oid1 bigint, oid2 bigint)
returns bigint(20)
return (select id from link where (o1=oid1 and o2=oid2) or (o1=oid2 and o2=oid1) limit 1);

drop function gAND;
create function gAND(oid1 bigint, oid2 bigint, oid3 bigint)
returns bigint(20)
return (
	select o1 from ( 
		select o1 from link where o2 in (oid1, oid2, oid3) and o1 is not null 
		union all 
		select o2 from link where o1 in (oid1, oid2, oid3) and o2 is not null 
	)o 
	group by o1 
	having count(*) = case when oid2 is null then 1 when oid3 is null then 2 else 3 end
	order by o1
	limit 1
);

drop function gR;
create function gR(oid1 bigint, oid2 bigint)
returns bigint(20)
return (
	select o1 from ( 
		select o1 from link where o2 in (oid1, oid2)
			and o1 not in (select o1 from link where o2 = (select id from object where n='класс' limit 1))
		union all 
		select o2 from link where o1 in (oid1, oid2)
			and o2 not in (select o1 from link where o2 = (select id from object where n='класс' limit 1))
	)o 
	group by o1 
	having count(*) = case when oid2 is null then 1 else 2 end
	order by o1
	limit 1
);

drop procedure chR;
create procedure chR(rule bigint)
begin
	declare executor bigint;
	declare cond bigint;
	declare condCopy bigint;
	declare subj bigint;
	declare subjCopy bigint;
	declare result bigint;
	declare newO bigint;

	set executor = gR(rule, gO('правило исполнитель'));
	set result = gO('правило статус исполнено');
	if result is null then set result = cO('правило статус исполнено'); end if;
	
	if gL(rule, result) is null then
		set cond = gR(rule, gO('правило условие'));
		set condCopy = gR(rule, gO('правило условие сравнение'));
		if gO(cond) = gO(condCopy) then
			if executor = gO('система') then
				set subj = gR(rule, gO('правило субъект'));
				set subjCopy = gR(rule, gO('правило субъект состояние'));
				if subj is not null then
					call uO(subj, gO(subjCopy));
					call chRs(subj);
				else
					set newO = cO(gO(subjCopy));
				end if;
				call cL(rule, result);
				
			else
				set result = gO('правило статус неисполнено');
				if result is null then set result = cO('правило статус неисполнено'); end if;
				call cL(rule, result);
			end if;
		end if;
	end if;
end;

drop procedure chRs;
create procedure chRs(condId bigint)
begin
	declare done int default false;
	declare rule bigint default 0;
	declare cur1 cursor for 
		select o1 from ( 
			select o1 from link where o2 in (gO('правило'), condId)
				and o1 not in (select o1 from link where o2 = (select id from object where n='класс' limit 1))
			union all 
			select o2 from link where o1 in (gO('правило'), condId)
				and o2 not in (select o1 from link where o2 = (select id from object where n='класс' limit 1))
		)o 
		group by o1 
		having count(*) = 2
		order by o1;

	declare continue HANDLER for not found set done = true;

	if gL(condId, gO('правило условие')) is not null then
		open cur1;
		read_loop: loop
			fetch cur1 into rule;

			if done then
				LEAVE read_loop;
			end if;

			call chR(rule);

		end loop;
		close cur1;
	end if;
end;

drop function gT;
create function gT()
returns bigint(20)
return (select o1 from link where o2 = gO('время') limit 1);

drop procedure cT;
create procedure cT()
begin
	update object set n = CURRENT_TIMESTAMP() where id = gT();
	call chRs(gT());
end;

