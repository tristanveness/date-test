#!/bin/bash

function fail() {
	exit 1;
}

printf "\nSolution A - use a date library\n\n"
[ "$(./daysBetween '03/01/1989' '03/01/1989')" -eq 0 ] || fail
[ "$(./daysBetween '02/06/1983' '22/06/1983')" -eq 19 ] || fail
[ "$(./daysBetween '04/07/1984' '25/12/1984')" -eq 173 ] || fail
[ "$(./daysBetween '03/01/1989' '03/08/1983')" -eq 1979 ] || fail
[ "$(./daysBetween '01/01/1901' '31/12/2999')" -eq 401400 ] || fail
[ "$(./daysBetween '01/02/2000' '01/03/2000')" -eq 28 ] || fail
[ "$(./daysBetween '01/02/2400' '01/03/2400')" -eq 28 ] || fail
[ "$(./daysBetween '01/02/2100' '01/03/2100')" -eq 27 ] || fail
[ "$(./daysBetween '01/02/2300' '01/03/2300')" -eq 27 ] || fail
