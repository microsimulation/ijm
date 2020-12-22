#!/bin/bash

dois=(
    10.34196/ijm.00211  # issue 13_1
    10.34196/ijm.00205  # issue 12_3
    10.34196/ijm.00198  # issue 12_2
    10.34196/ijm.00193  # issue 12_1 (map rule)
    10.34196/ijm.00057  # issue 4_3 (map rule with spaces)
    10.34196/ijm.00003  # issue 1_1
)

for doi in "${dois[@]}"; do
    echo ${doi}
    curl -s -L -I doi.org/${doi} | grep HTTP
    echo ""
done
