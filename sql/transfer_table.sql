insert into link (o1, o2)
select id, (select id from object where n = '���') from object where id between 1332 and 1335
union all
select id, (select id from object where n = '����') from object where id between 1336 and 1342
union all
select id, (select id from object where n = '����') from object where id between 1343 and 1349;

insert into link (o1, o2)
select (select id from object where n = '��')f1, (select id from object where n = '��') f2;

insert into link (o1, o2)
select (select id from object where n = '������')f1, (select id from object where n = '��') f2;

insert into link (o1, o2)
select obj.oid, ik.id from (select rowid, tu, ik, manager from explobject)o
left join (
	select o1 id, '���' tu, '���1' ik from link where o1 in (select id from object where n='���1') and o2 = (select id from object where n='���')
	union all
	select o1 id, '���' tu, '���2' ik from link where o1 in (select id from object where n='���2') and o2 = (select id from object where n='���')
	union all
	select o1 id, '���' tu, '���3' ik from link where o1 in (select id from object where n='���3') and o2 = (select id from object where n='���')
	union all
	select o1 id, '���' tu, '���4' ik from link where o1 in (select id from object where n='���4') and o2 = (select id from object where n='���')
	union all
	select o1 id, '����' tu, '��1' ik from link where o1 in (select id from object where n='��1') and o2 = (select id from object where n='����')
	union all
	select o1 id, '����' tu, '��2' ik from link where o1 in (select id from object where n='��2') and o2 = (select id from object where n='����')
	union all
	select o1 id, '����' tu, '��3' ik from link where o1 in (select id from object where n='��3') and o2 = (select id from object where n='����')
	union all
	select o1 id, '����' tu, '��4' ik from link where o1 in (select id from object where n='��4') and o2 = (select id from object where n='����')
	union all
	select o1 id, '����' tu, '��5' ik from link where o1 in (select id from object where n='��5') and o2 = (select id from object where n='����')
	union all
	select o1 id, '����' tu, '��6' ik from link where o1 in (select id from object where n='��6') and o2 = (select id from object where n='����')
	union all
	select o1 id, '����' tu, '��7' ik from link where o1 in (select id from object where n='��7') and o2 = (select id from object where n='����')
	union all
	select o1 id, '����' tu, '��1' ik from link where o1 in (select id from object where n='��1') and o2 = (select id from object where n='����')
	union all
	select o1 id, '����' tu, '��2' ik from link where o1 in (select id from object where n='��2') and o2 = (select id from object where n='����')
	union all
	select o1 id, '����' tu, '��3' ik from link where o1 in (select id from object where n='��3') and o2 = (select id from object where n='����')
	union all
	select o1 id, '����' tu, '��4' ik from link where o1 in (select id from object where n='��4') and o2 = (select id from object where n='����')
	union all
	select o1 id, '����' tu, '��5' ik from link where o1 in (select id from object where n='��5') and o2 = (select id from object where n='����')
	union all
	select o1 id, '����' tu, '��6' ik from link where o1 in (select id from object where n='��6') and o2 = (select id from object where n='����')
	union all
	select o1 id, '����' tu, '��7' ik from link where o1 in (select id from object where n='��7') and o2 = (select id from object where n='����')
)ik on ik.tu = o.tu and ik.ik = o.ik
left join (
	select o2 oid, n rowid from (select * from link where o1 in (select o1 from link where o2 = (select id from object where n='�����')) and o2 <> (select id from object where n='�����'))l
	left join object o on o.id = l.o1
)obj on obj.rowid = o.rowid;

insert into link (o1, o2)
select object.id, ik.id from (select name n, tu, ik from explobjectmanager)o
left join (
	select o1 id, '1' tu, '1' ik from link where o1 in (select id from object where n='���1') and o2 = (select id from object where n='���')
	union all
	select o1 id, '1' tu, '2' ik from link where o1 in (select id from object where n='���2') and o2 = (select id from object where n='���')
	union all
	select o1 id, '1' tu, '3' ik from link where o1 in (select id from object where n='���3') and o2 = (select id from object where n='���')
	union all
	select o1 id, '1' tu, '4' ik from link where o1 in (select id from object where n='���4') and o2 = (select id from object where n='���')
	union all
	select o1 id, '2' tu, '1' ik from link where o1 in (select id from object where n='��1') and o2 = (select id from object where n='����')
	union all
	select o1 id, '2' tu, '2' ik from link where o1 in (select id from object where n='��2') and o2 = (select id from object where n='����')
	union all
	select o1 id, '2' tu, '3' ik from link where o1 in (select id from object where n='��3') and o2 = (select id from object where n='����')
	union all
	select o1 id, '2' tu, '4' ik from link where o1 in (select id from object where n='��4') and o2 = (select id from object where n='����')
	union all
	select o1 id, '2' tu, '5' ik from link where o1 in (select id from object where n='��5') and o2 = (select id from object where n='����')
	union all
	select o1 id, '2' tu, '6' ik from link where o1 in (select id from object where n='��6') and o2 = (select id from object where n='����')
	union all
	select o1 id, '2' tu, '7' ik from link where o1 in (select id from object where n='��7') and o2 = (select id from object where n='����')
	union all
	select o1 id, '3' tu, '1' ik from link where o1 in (select id from object where n='��1') and o2 = (select id from object where n='����')
	union all
	select o1 id, '3' tu, '2' ik from link where o1 in (select id from object where n='��2') and o2 = (select id from object where n='����')
	union all
	select o1 id, '3' tu, '3' ik from link where o1 in (select id from object where n='��3') and o2 = (select id from object where n='����')
	union all
	select o1 id, '3' tu, '4' ik from link where o1 in (select id from object where n='��4') and o2 = (select id from object where n='����')
	union all
	select o1 id, '3' tu, '5' ik from link where o1 in (select id from object where n='��5') and o2 = (select id from object where n='����')
	union all
	select o1 id, '3' tu, '6' ik from link where o1 in (select id from object where n='��6') and o2 = (select id from object where n='����')
	union all
	select o1 id, '3' tu, '7' ik from link where o1 in (select id from object where n='��7') and o2 = (select id from object where n='����')
)ik on ik.tu = o.tu and ik.ik = o.ik
left join object on object.n = o.n;