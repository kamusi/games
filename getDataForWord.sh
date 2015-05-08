#!/bin/bash
echo "BEGINNNING"
word="$1"
amount ="$2"
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
	sed -n "s/\(.*\.\|^\)\([^.]*$1[^.]*\.\).*/\2/p"
#sed -n "s/.*[.?!]\([^.]*$1[^.?!]*[.?!]\).*/\1/p"
#sed  "s/.*[.?!]\([^.]*$1[^.?!]*[.?!]\).*/\1/"
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
documentText=$(cat "$file" | getWords)
#echo "$documentText" | sed 's/\n/ /g'

for word in $allwords; do
	((numberOfSentencesFound++))
	verbose2 "$word:"
	newSentence=$(printDocument | getSentence "$word")
	sentences+=("$newSentence")
	documentText=`printDocument | sed "s/$newSentence//"`
	sentences+=("<DELIMITER>")

done

#echo $sentences
#echo "The document was: " 
#printDocument
}

printDocument () {
	echo $documentText | sed 's/\n/ /'
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
		if [ $numberOfSentencesFound -ge $amount ]; then
				break
		fi
		if containsElement blacklist[@] $file ; then
			verbose "Ignoring $file"
		elif [ -d "$file" ]; then
			verbose "$file is a directory"
			cd "$file"


			getNextFile blacklist[@]
	
			#Adding a folder to blacklist if we exit it without having finished
			if [ $numberOfSentencesFound -lt $amount ]; then
				blacklist+=("$file")
			fi

			cd ..

		else
			verbose "Doing stuff with file: $file"
			findAllSentencesInFile "$file"

			blacklist+=("$file")
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
#cd 'shellParser'

getNextFile blacklist[@]
echo "<SENTENCES>"
printArray2 sentences[@]
echo "</SENTENCES>"
echo
echo "<BLACKLIST>"
printArray blacklist[@]
echo "</BLACKLIST>"
verbose2 "Found That many sentences: $numberOfSentencesFound"



