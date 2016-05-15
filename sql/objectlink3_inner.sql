#drop view explsobject;
#create view explsobject as 
select o8.n rowid, o6.n tu, o5.n ik, o7.n manager, o0.n name, o1.n address, o2.n cadastr, o3.n lat, o4.n lon from link l0 
inner join object o0 on o0.id = l0.o1
	and o2 = (select id from object where n='объект' limit 1) 
	and o1 not in (select o1 from link where o2 = (select id from object where n='класс' limit 1))

inner join link l8 on l8.o1 in (
		select o1 from link where o2 = (select id from object where n='номер' limit 1) 
			and o1 not in (select o1 from link where o2 = (select id from object where n='класс' limit 1))
	) and l8.o2 = o0.id left join object o8 on o8.id = l8.o1

inner join link l1 on l1.o1 in (
		select o1 from link where o2 = (select id from object where n='адрес' limit 1) 
			and o1 not in (select o1 from link where o2 = (select id from object where n='класс' limit 1))
	) and l1.o2 = o0.id left join object o1 on o1.id = l1.o1

left join link l2 on l2.o1 in (
		select o1 from link where o2 = (select id from object where n='кадастр' limit 1) 
			and o1 not in (select o1 from link where o2 = (select id from object where n='класс' limit 1))
	) and l2.o2 = o0.id left join object o2 on o2.id = l2.o1

left join link l3 on l3.o1 in (
		select o1 from link where o2 = (select id from object where n='широта' limit 1) 
			and o1 not in (select o1 from link where o2 = (select id from object where n='класс' limit 1))
	) and l3.o2 = o0.id left join object o3 on o3.id = l3.o1

left join link l4 on l4.o1 in (
		select o1 from link where o2 = (select id from object where n='долгота' limit 1) 
			and o1 not in (select o1 from link where o2 = (select id from object where n='класс' limit 1))
	) and l4.o2 = o0.id left join object o4 on o4.id = l4.o1

left join link l5 on l5.o2 in (
		select o1 from link where o2 = (select id from object where n='ик' limit 1) 
			and o1 not in (select o1 from link where o2 = (select id from object where n='класс' limit 1))
	) and l5.o1 = o0.id left join object o5 on o5.id = l5.o2

left join link l6 on l6.o2 in (
		select o1 from link where o2 = (select id from object where n='ту' limit 1) 
			and o1 not in (select o1 from link where o2 = (select id from object where n='класс' limit 1))
	) and l6.o1 = l5.o2 left join object o6 on o6.id = l6.o2

left join link l7 on l7.o1 in (
		select o1 from link where o2 = (select id from object where n='ответственный' limit 1) 
			and o1 not in (select o1 from link where o2 = (select id from object where n='класс' limit 1))
	) and l7.o2 = l5.o2 left join object o7 on o7.id = l7.o1

