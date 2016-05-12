/*
select * from object where id in (
	select o1 from link where o2 = 430
)
*/

select * from object where id in (
	select o1 from link where o2 in (
		select o2 from link where o1 in (
			select o1 from link where o1 in (select id from object where n = '77') and o2 in (select id from object where n='номер')
		) and o2 not in (select id from object where n='номер')
	)
)
