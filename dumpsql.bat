c:\"Program Files"\MySQL\"MySQL Server 5.7"\bin\mysqldump --skip-comments --routines --events -u root -p -h localhost expl > sql\dump.sql

rem c:\"Program Files"\MySQL\"MySQL Server 5.7"\bin\mysqldump --skip-comments --routines --events -u root -p -h localhost expl > sql\temp.sql
rem findstr -v "50013 DEFINER" sql\temp.sql > sql\dump.sql
rem del sql\temp.sql