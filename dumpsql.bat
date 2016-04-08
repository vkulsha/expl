mysqldump --skip-comments -u root -p -h localhost expl > sql\temp.sql
findstr -v "50013 DEFINER" sql\temp.sql > sql\dump.sql
del sql\temp.sql