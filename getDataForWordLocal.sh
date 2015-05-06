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

			currentFile=$(getNextFile no.txt)
			relevantLines=$(findOccurances $currentFile "lemma=\"$word\"" 5)

			#Get all occurences of words related to this lema in this document
			allwords=$(echo "$relevantLines" | getWords)

			#echo "$currentFile"

			documentText=$(cat $currentFile | getWords)

			for word in $allwords; do
				echo "$word: "
				sentences+=$(echo $documentText | getSentence $word)
			done

			echo $sentences
			echo 
			echo "Whole Text: "$documentText 	

			echo "$file"
			#TODO if not found enough sentences go to next one
			break;

 		fi
	done	

}

findOccurances() {
#Usage : findOccurances file word numberOfOccurances

#	debug "Input: sss$1 $2 $3"
#	verbose "Entering f2"
occurances=`grep -n -m $3 "$2" "$1"`
echo "$occurances"
}

#prints the word to stdout, returns the line number
#getting it from stdin
getWords() {	
	#remove strange artifacts and then extract words
	sed  's:&amp;quot;::g' | sed -n 's:.*<w.*>\(.*\)</w>.*:\1:p'
}

#Returns the sentence in which the given word occurs
#getting it from stdin
#A Sentence is defined by everything between two points, ? or !.
#Failures will occur with things like "This is Dr.Who"
getSentence() {
#	sed "s/.*\.\([^.]*$1[^.]*\.\).*/\1/"
sed "s/.*[.?!]\([^.]*$1[^.?!]*[.?!]\).*/\1/"
}


cd shellParser/

#getWord la.xml 








