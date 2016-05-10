#select * from `explsObject` where tu in ('СЗТП','УЭИ')
select * from `explsObject` where 1=1 and name in (
	select n from object where id in (
		select o1 from (
			select l.o1 from link l where l.o2 in (

				select l.o1 from link l where l.o2 in (
					select o.id from object o where o.n in ('СЗТП','УЭИ')
				) 
				and o1 in (select l.o1 from link l where l.o2 = (select o.id from object o where o.n='ик' limit 1))

			)
			and l.o1 in (select l.o1 from link l where l.o2 = (select o.id from object o where o.n='объект' limit 1))
			group by o1
		)xx
	)

)

#select distinct `rowid` from `explsObject` where tu in ('СЗТП','УЭИ')

select n from object where id in (

	select o1 from link where o2 in (

		select o1 from link where o2 in (

			select o1 from link where o2 in (
				select id from object where n in ('СЗТП','УЭИ')
			) 
			and o1 in (select o1 from link where o2 = (select id from object where n='ик' limit 1))

		)
		and o1 in (select o1 from link where o2 = (select id from object where n='объект' limit 1))

	)
	and o1 in (select o1 from link where o2 = (select id from object where n='номер' limit 1))

)

#select distinct `rowid` from `explsObject` where ik in ('ПЭИ1','ПЭИ2','ИК1','ИК5') order by `rowid`

select n from object where id in (

	select o1 from link where o2 in (

		select o1 from link where o2 in (
			select id from object where n in ('ПЭИ1','ПЭИ2','ИК1','ИК5')
		) 
		and o1 in (select o1 from link where o2 = (select id from object where n='объект' limit 1))

	)
	and o1 in (select o1 from link where o2 = (select id from object where n='номер' limit 1))

)


