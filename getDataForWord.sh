#!/bin/bash
echo
word="$1"
amount="$2"
pointer="$3" 
sentences=()
numberOfSentencesFound=0


verbose=yes
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

readBlacklist() {
	if [ -e "blacklists/$word.ignore" ]; then
		readarray blacklist < "blacklists/$word.ignore"
	fi
}

compareToPointer(){
	#Empty pointer : no constraints
	if [[ $1 -eq "" ]]; then
		return 0
	fi
		
		#first word of pointer must be lower or equal: so that we can enter directories
	if [ -d "$1" ]; then
		res=`echo "$pointer" | sed -n 's/\([^_]\)_.*/\1/p'`
		echo $res
		if [[ "$1" > "$res" ]] || [[ "$1" -eq "$res" ]]; then
			return 0
		else
			return 1
		fi
	else
		if [[ "$1" > "$pointer" ]]; then
			return 0
		else
			return 1
		fi

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
#	sentences+=("<DELIMITER>")

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
    	echo "Comparing : $element WITH $seeking"
        if [[ "$element" == "$seeking" ]]; then
            in=0
            break
        fi
    done
    return $in
}

getNextFile () {
	verbose2 "Entering getNextFile in folder $PWD"

	for file in *; do
		if [[ $numberOfSentencesFound -ge $amount ]];  then
				break
		fi
		if ! compareToPointer $file ; then
			verbose "Ignoring $file"
		elif [ -d "$file" ]; then
			verbose "$file is a directory"
			cd "$file"


			getNextFile
	
			#Adding a folder to blacklist if we exit it without having finished
			#if [ $numberOfSentencesFound -lt $amount ]; then
			#	blacklist+=("$file")
			#fi

			cd ..

		else
			verbose "Doing stuff with file: $file"
			findAllSentencesInFile "$file"

			#blacklist+=("$file")
			pointer="$file"
 		fi
	done
	verbose "Everything on blacklist End:"
	verbose "${blacklist[*]}"
}

printArray() {
	array=("${!1}")
	for element in "${array[@]} "; do
		echo "$element"  
		#echo "DELIMITER"
	done
}

printArrayToFile() {
	array=("${!1}")
	rm -f "$word".ignore
	for element in "${array[@]} "; do
		echo "$element"  >"$word".ignore
		#echo "DELIMITER"
	done
}

printArray2() {
	array=("${!1}")
	for element in "${array[*]} "; do
		echo "$element"  
		#echo "DELIMITER"
	done
}
#implement better blackist : just rememebr the pointer and compare to previosu position



#readBlacklist
#echo "debut"
#printArray blacklist[@]
#echo "endDebut"

cd '/appl/kielipankki/hcs/articles/'
#cd 'shellParser'

getNextFile #blacklist[@]
echo "<SENTENCES>"
printArray sentences[@]
echo "</SENTENCES>"
echo
echo "<BLACKLIST>"
#printArray blacklist[@]
echo "</BLACKLIST>"
verbose2 "Found That many sentences: $numberOfSentencesFound"

#pointer="alasiri_2000-02-07-a.xml"
#compareToPointer "" && echo "yes" || echo "no"

#saving blacklist
cd '/homeappl/home/babst/blacklists'
#printArrayToFile blacklist[@]
echo "$pointer"


