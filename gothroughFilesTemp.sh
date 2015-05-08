#!/bin/bash

#We will fetch everything for one file/word combination
#Since the file sizes of the documents are not larger than 2MB, we can do the following :
#
#

word="$1"

verbose=yes


verbose () {	
if [ $verbose = yes ]; then
	echo "$1"
fi
}

debug=yes

debug () {	
if [ $debug = yes ]; then
	echo "$1"
fi
}

sentences=()
alreadyencoutered=()

getNextFile () {

	for file in *; do

		if [$file in $1]; then
			verbose "Ignoring $file"
		elif [ -d $file ]; then
			cd "$file"
			getNextFile $1
			$1+=$file
		else
			#Do stuff
			#Find the line where lemma occurs


 		fi
	done	

}
