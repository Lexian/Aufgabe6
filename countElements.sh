#!/bin/bash

HTML=$(ack -h --html '<(\w+).*?>' --output '$1' | sort -uf | wc -l)
CSS=$(ack -h --css  '(-(khtml|moz|ms|o|webkit)-)?([\w-]*)\s*:.*?;' --output '$3' | sort -uf | wc -l)
echo "${PWD##*/} $HTML $CSS"
