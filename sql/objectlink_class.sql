select 0 rnk, id, n from object where id in (
	select o1 from link where o2 = (select id from object where n='класс' limit 1) 
		and o1 in (select o2 from link where o1 = (select id from object where n='объект' limit 1))
)
union all
select 1, (select id from object where n='объект' limit 1), 'объект'
union all
select 2, id, n from object where id in (
	select o1 from link where o2 = (select id from object where n='класс' limit 1) 
		and o1 in (select o1 from link where o2 = (select id from object where n='объект' limit 1))
)
