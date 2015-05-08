#!/bin/bash

#We will fetch everything for one file/word combination
#Since the file sizes of the documents are not larger than 2MB, we can do the following :
#
#

verbose=yes
verbose2=yes
verbose () {	
	if [ $verbose = yes ]; then
		echo "$1"
	fi
}

debug () {	
	if [ $verbose2 = yes ]; then
		echo "$1"
	fi
}



findOccurances() {
#Usage : findOccurances file word numberOfOccurances

	debug "Input: sss$1 $2 $3"
	verbose "Entering f2"
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
sed -n "s/.*[.?!]\([^.]*$1[^.?!]*[.?!]\).*/\1/p"
}

numberOfSentencesFound=0
word="$1"
sentences=()




findAllSentencesInFile() {
file="$1"

relevantLines=$(findOccurances $file "lemma=\"$word\"" 5)

#Get all occurences of words related to this lema in this document
allwords=$(echo "$relevantLines" | getWords)

#echo "$currentFile"

documentText=$(cat $file | getWords)

for word in $allwords; do
	((numberOfSentencesFound++))
	echo "$word: "
	sentences+=$(echo $documentText | getSentence $word)
done

echo $sentences
 
verbose "Whole Text: $documentText"	

verbose "Found That many sentences: $numberOfSentencesFound"
#if length of sentences > 5 break else continue in loop

}


cd shellParser
echo start
findAllSentencesInFile la.xml





