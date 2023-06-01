#!/bin/bash

# Loop through all .php files excluding those in vendor and node_modules directories
for file in $(find . -name 'vendor' -prune -o -name 'node_modules' -prune -o -name '*.php' -print); do
    # Execute the file
    php "$file"
done
