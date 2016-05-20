select 
	o0.id id0, o0.n n0 
	,group_concat(distinct o1.n) n1, group_concat(distinct o1.id) id1, count(distinct o1.id) c1 
	,group_concat(distinct o2.n) n2, group_concat(distinct o2.id) id2, count(distinct o2.id) c2 
	,group_concat(distinct o3.n) n3, group_concat(distinct o3.id) id3, count(distinct o3.id) c3 

	from (#main class
		select id, n from object where id in (
			select o1 from link where o2 = (select id from object where n='ту' limit 1) 
				and o1 not in (select o1 from link where o2 = (select id from object where n='класс' limit 1))
		)
		group by id
	)o0

	left join (#child
		select o1, o2 from link where o1 in (
			select o1 from link where o2 = (select id from object where n='ик' limit 1) 
				and o1 not in (select o1 from link where o2 = (select id from object where n='класс' limit 1))
		)
		group by o1, o2
	)l1 on l1.o2 = o0.id left join object o1 on o1.id = l1.o1

	left join (#child prop
		select o1, o2 from link where o1 in (
			select o1 from link where o2 = (select id from object where n='ответственный' limit 1) 
				and o1 not in (select o1 from link where o2 = (select id from object where n='класс' limit 1))
		)
		group by o1, o2
	)l2 on l2.o2 = o1.id left join object o2 on o2.id = l2.o1

	left join (#child child
		select o1, o2 from link where o1 in (
			select o1 from link where o2 = (select id from object where n='объект' limit 1) 
				and o1 not in (select o1 from link where o2 = (select id from object where n='класс' limit 1))
		)
		group by o1, o2
	)l3 on l3.o2 = o1.id left join object o3 on o3.id = l3.o1

group by id0
group by o0.id having n0 is not null 