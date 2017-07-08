#!/bin/bash

set -ex

ls -phl | awk 'NR>1' | sort -k1 -k2 | awk 'BEGIN{print "<html><head><title>Index of /</title></head><body><pre>"} {printf("<a href=\"%s\">%s</a>\t%s-%s %s %s\n", $9, $9, $7, $6, $8, $5)} END{print "</pre></body></html>"}' >index.html 

cat autoindex.html >>index.html

git add index.html
git commit -m "[skip ci] build new index" -s
git push origin master

