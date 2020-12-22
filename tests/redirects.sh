#!/bin/bash

dois=(
    10.34196/ijm.00211  # issue 13_1
    10.34196/ijm.00205  # issue 12_3
    10.34196/ijm.00198  # issue 12_2
    10.34196/ijm.00193  # issue 12_1 (map rule)
    10.34196/ijm.00056  # issue 4_3 (map rule with spaces)
    10.34196/ijm.00003  # issue 1_1
    10.34196/ijm.00045  # found via all_doi.sh
    10.34196/ijm.00081  # found via all_doi.sh
    10.34196/ijm.00104  # found via all_doi.sh
    10.34196/ijm.00084  # found via all_doi.sh
    10.34196/ijm.00083  # found via all_doi.sh
)

for doi in "${dois[@]}"; do
    echo ${doi}
    curl -s -L -I doi.org/${doi} | grep -E 'HTTP|^[Ll]ocation'
    echo ""
done
