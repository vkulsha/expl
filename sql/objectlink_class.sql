select
	rnk,
	o0.n,
	o0.id
from (
	select o1, 0 rnk from link where o2 = (select id from object where n='класс' limit 1) 
		and o1 in (select o2 from link where o1 = (select id from object where n='объект' limit 1))
)l left join object o0 on o0.id = l.o1
union all
select 1, 'объект', (select id from object where n='объект' limit 1)
union all
select
	rnk,
	o0.n,
	o0.id
from (
	select o1, 2 rnk from link where o2 = (select id from object where n='класс' limit 1) 
		and o1 in (select o1 from link where o2 = (select id from object where n='объект' limit 1))
)l left join object o0 on o0.id = l.o1
