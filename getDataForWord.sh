#!/bin/bash

word="$1"
sentences=()
numberOfSentencesFound=0
blacklist=()

verbose=no
verbose2=yes
verbose () {	
	if [ $verbose = yes ]; then
		echo "$1"
	fi
}

verbose2 () {	
	if [ $verbose2 = yes ]; then
		echo "$1"
	fi
}


#prints the word to stdout, returns the line number
#getting it from stdin
getWords() {	
	#remove strange artifacts and then extract words, finally display it all in one line
	sed  's:&amp;quot;::g' | sed -n 's:.*<w.*>\(.*\)</w>.*:\1:p'
}

#Returns the sentence in which the given word occurs
#getting it from stdin
#A Sentence is defined by everything between two points, ? or !.
#Failures will occur with things like "This is Dr.Who"
getSentence() {
#	sed "s/.*\.\([^.]*$1[^.]*\.\).*/\1/"
sed -n "s/.*[.?!]\([^.]*$1[^.?!]*[.?!]\).*/BEGINSENTENCE\1ENDSENTENCE/p"
}

findOccurances() {
#Usage : findOccurances file word numberOfOccurances

occurances=`grep -n -m $3 "$2" "$1"`
echo "$occurances"
}

findAllSentencesInFile() {
file="$1"

relevantLines=$(findOccurances $file "lemma=\"$word\"" 5)

#Get all occurences of words related to this lema in this document
allwords=$(echo "$relevantLines" | getWords)

#1) Allwyas getting same sentence 2)Coutnign 4 words getting 3 sentences
documentText=$(cat $file | getWords)
documentText= $(echo "$documentText" | sed 's/\n/ /g')

for word in $allwords; do
	((numberOfSentencesFound++))
	verbose2 "$word:"
	newSentence=$(echo "$documentText" | getSentence "$word")
	sentences+=("$newSentence") #FIRST SENTENCE EMPTY; WHY?
	verbose2 "NEWSENTENCE$newSentence"
	$documentText=`echo "$documentText" | sed -r "s/$newSentence//"`
	sentences+=("<DELIMITER>")

done

#echo $sentences
 
#verbose2 "Whole Text: $documentText"	

verbose "Found That many sentences: $numberOfSentencesFound"
#if length of sentences > 5 break else continue in loop

}

#http://stackoverflow.com/questions/1063347/passing-arrays-as-parameters-in-bash
containsElement () { 
    declare -a arrayIn=("${!1}")
    local seeking=$2
    local in=1
    for element in "${arrayIn[@]}"; do
        if [[ "$element" == "$seeking" ]]; then
            in=0
            break
        fi
    done
    return $in
}

getNextFile () {
	verbose2 "Entering getNextFile in folder $PWD"
	blacklist=("${!1}")

	for file in *; do
		if [ $numberOfSentencesFound -gt 2 ]; then
				break
		fi
		if containsElement blacklist[@] $file ; then
			verbose "Ignoring $file"
		elif [ -d "$file" ]; then
			verbose "$file is a directory"
			cd "$file"

			#Adding a folder to blacklist if we exit it without having finished

			getNextFile blacklist[@]
			blacklist+=($file)

			cd ..

		else
			verbose "Doing stuff with file: $file"
			findAllSentencesInFile "$file"

			blacklist+=($file)
 		fi
	done
	verbose "Everything on blacklist End:"
	verbose "${blacklist[*]}"
}

printArray() {
	array=("${!1}")
	for element in "${array[@]} "; do
		echo "$element"  
		echo "DELIMITER"
	done
}

printArray2() {
	array=("${!1}")
	for element in "${array[*]} "; do
		echo "$element"  
		echo "DELIMITER"
	done
}

cd '/appl/kielipankki/hcs/articles/'

getNextFile array[@]
echo "<SENTENCES>"
printArray2 sentences[@]
echo
echo $"<BLACKLIST>"
printArray blacklist[@]

verbose2 "$documentText"
