select * from (
select
	o0.n `������`, 
	o1.n `�����`, 
	o2.n `�������`, 
	o3.n `������`, 
	o4.n `�������`, 
	o5.n `��`, 
	o6.n `��`, 
	o7.n `�������������`, 
	o8.n `�����` 
from (
	select * from (
	select * from object where id in (
		select o1 from link where o2 = (select id from object where n='������' limit 1) 
			and o1 not in (select o1 from link where o2 = (select id from object where n='�����' limit 1))
	)
	group by id
	)xx
)o0

left join (
	select * from (
	select o1, o2 from link where o1 in (
		select o1 from link where o2 = (select id from object where n='�����' limit 1) 
			and o1 not in (select o1 from link where o2 = (select id from object where n='�����' limit 1))
	)
	group by o1, o2
	)xx
)l1 on l1.o2 = o0.id left join object o1 on o1.id = l1.o1

left join (
	select * from (
	select o1, o2 from link where o1 in (
		select o1 from link where o2 = (select id from object where n='�������' limit 1) 
			and o1 not in (select o1 from link where o2 = (select id from object where n='�����' limit 1))
	)
	group by o1, o2
	)xx
)l2 on l2.o2 = o0.id left join object o2 on o2.id = l2.o1

left join (
	select * from (
	select o1, o2 from link where o1 in (
		select o1 from link where o2 = (select id from object where n='������' limit 1) 
			and o1 not in (select o1 from link where o2 = (select id from object where n='�����' limit 1))
	)
	group by o1, o2
	)xx
)l3 on l3.o2 = o0.id left join object o3 on o3.id = l3.o1

left join (
	select * from (
	select o1, o2 from link where o1 in (
		select o1 from link where o2 = (select id from object where n='�������' limit 1) 
			and o1 not in (select o1 from link where o2 = (select id from object where n='�����' limit 1))
	)
	group by o1, o2
	)xx
)l4 on l4.o2 = o0.id left join object o4 on o4.id = l4.o1

left join (
	select * from (
	select o1 o2, o2 o1 from link where o2 in (
		select o1 from link where o2 = (select id from object where n='��' limit 1) 
			and o1 not in (select o1 from link where o2 = (select id from object where n='�����' limit 1))
	)
	group by o1, o2
	)xx
)l5 on l5.o2 = o0.id left join object o5 on o5.id = l5.o1

left join (
	select * from (
	select o1 o2, o2 o1 from link where o2 in (
		select o1 from link where o2 = (select id from object where n='��' limit 1) 
			and o1 not in (select o1 from link where o2 = (select id from object where n='�����' limit 1))
	)
	group by o1, o2
	)xx
)l6 on l6.o2 = l5.o1 left join object o6 on o6.id = l6.o1

left join (
	select * from (
	select o1, o2 from link where o1 in (
		select o1 from link where o2 = (select id from object where n='�������������' limit 1) 
			and o1 not in (select o1 from link where o2 = (select id from object where n='�����' limit 1))
	)
	group by o1, o2
	)xx
)l7 on l7.o2 = l5.o1 left join object o7 on o7.id = l7.o1

left join (
	select * from (
	select o1, o2 from link where o1 in (
		select o1 from link where o2 = (select id from object where n='�����' limit 1) 
			and o1 not in (select o1 from link where o2 = (select id from object where n='�����' limit 1))
	)
	group by o1, o2
	)xx
)l8 on l8.o2 = o0.id left join object o8 on o8.id = l8.o1
)xx
where �� in ('����','���')