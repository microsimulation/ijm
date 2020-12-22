#!/bin/bash

total_works=$(curl -s https://api.crossref.org/prefixes/10.34196/works | jq -r '.message."total-results"')
items_per_page=$(curl -s https://api.crossref.org/prefixes/10.34196/works | jq -r '.message."items-per-page"')
pages=$(((total_works / items_per_page)))


for i in $(seq 0 ${pages}); do
    for doi in $(curl -s https://api.crossref.org/prefixes/10.34196/works?offset=$((20 * i)) | jq -r '.message.items[].DOI'); do
        curl -s -L -I doi.org/${doi} | grep 'HTTP/2 200' > /dev/null
        if [[ $? != 0 ]]; then
            echo "no 200 response for: ${doi}"
        fi
    done
done
