set cutoffdate 2021-04-01

mysql -e -ulno -p"lTrapok)1" lno "select * from band where created_at>'${cutoffdate}' into outfile 'band.csv'"

