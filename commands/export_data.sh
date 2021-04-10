#!/bin/bash -v
cutoffdate='2021-04-01'

echo $cutoffdate

mysql -ulno -p"lTrapok)1" lno -e "select created_at from band where created_at > '${cutoffdate}' LIMIT 5"
#mysql -ulno -p"lTrapok)1" lno -e "select * from band where created_at>${cutoffdate} into outfile 'band.csv'"

