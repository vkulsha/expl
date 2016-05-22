#iii.php
select * from ( 
	select distinct link.o1, object.n, link.o2, case when class.o2 is not null then 'Класс' end c, link.t from ( 
	select o1, o2, 'child' t from link union all select o2, o1, 'parent' from link 
	)link 
	join object on object.id = link.o1 
	left join link class on class.o1 = link.o1 and class.o2 in (select id from object where n='Класс') 
)xxx where 1=1 and (o1 <> o2 or (o1 = o2 and t='parent')) 

#class tree group_concat
select o.id, o.n, group_concat(distinct l1.o1) child, count(distinct l1.o1) c_child, group_concat(distinct l2.o2) parent, count(distinct l2.o2) c_parent from (
	select id, n from object where id in (select o1 from link where o2 = (select id from object where n='Класс' limit 1))
)o
left join link l1 on l1.o2 = o.id and l1.o1 in (select id from object where id in (select o1 from link where o2 = (select id from object where n='Класс' limit 1)))
left join link l2 on l2.o1 = o.id and l2.o2 in (select id from object where id in (select o1 from link where o2 = (select id from object where n='Класс' limit 1)))
group by o.id

#class tree all
select o.id, o.n, l1.o1, o1.n, l1.t from (
	select id, n from object where id in (select o1 from link where o2 = (select id from object where n='Класс' limit 1))
)o
left join (
	select o1, o2, 'child' t from link
	union all
	select o2, o1, 'parent' from link
)l1 on l1.o2 = o.id and l1.o1 in (select o1 from link where o2 = (select id from object where n='Класс' limit 1))
left join object o1 on o1.id = l1.o1
where l1.t = 'child' and l1.o1 <> o.id
order by o.id, l1.t, l1.o1