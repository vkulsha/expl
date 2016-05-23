select  level1.o1 o1, obj1.n n1, level1.t t1, level2.o1 o2, obj2.n n2, level2.t t2, level3.o1 o3, obj3.n n3, level3.t t3 
from (select o1, o2, 'child' t from link union all select o2, o1, 'parent' from link)level1 
left join (select o1, o2, 'child' t from link union all select o2, o1, 'parent' from link)level2 on level2.o2 = level1.o1
left join (select o1, o2, 'child' t from link union all select o2, o1, 'parent' from link)level3 on level3.o2 = level2.o1

left join object obj1 on obj1.id = level1.o1 
left join object obj2 on obj2.id = level2.o1 
left join object obj3 on obj3.id = level3.o1 
where 1=1 
and level1.o1 in (select o1 from link where o2 = (select id from object where n='класс' limit 1) and o1 <> (select id from object where n='класс' limit 1)) 
and level2.o1 in (select o1 from link where o2 = (select id from object where n='класс' limit 1) and o1 <> (select id from object where n='класс' limit 1)) 
and level3.o1 in (select o1 from link where o2 = (select id from object where n='класс' limit 1) and o1 <> (select id from object where n='класс' limit 1)) 
and (level1.o1 = 1251 or level2.o1 = 1251 or level3.o1 = 1251) 
and level1.o2 = (select id from object where n='Объект') 
 order by case  when level1.o1 = 1251 then 1  when level2.o1 = 1251 then 2  when level3.o1 = 1251 then 3  else 1000 end