#!/bin/bash

word="$1"
amount="$2"
pointer="$3" 
sentences=()
sources=()
sourceFiles=()
numberOfSentencesFound=0

if [[ "$pointer" == "DONE" ]]; then
	echo "No more files to search from!!"
	exit 1
fi

verbose=no

verbose () {	
if [ $verbose = yes ]; then
	echo "$1"
fi
}

compareToPointer(){
	#Empty pointer or illegal file : no constraints
	if [[ "$1" == "" ]] || [[ "$1" == "xml.test" ]]; then
		return 0
	fi
#first word of pointer must be lower or equal: so that we can enter directories
#if pointer is substring of directory we let it pass
if [ -d "$1" ]; then
	if [[ "$pointer" == *"$1"* ]] ; then
			#TODOO: work more on this for time saving: here we read all files!!
			return 0
		fi
	else 		
		if [[ "$1" > "$pointer" ]]; then
			return 0
		else
			return 1
		fi
	fi
}

#getting it from stdin
getWords() {	
	#remove strange artifacts and then extract words, finally display it all in one line
	sed  's:&amp;quot;::g' | sed  's/_/ /g' | sed -n 's:.*<w.*>\(.*\)</w>.*:\1:p' 
}

getSourceInfo() {
	xml_grep 'sourceDesc' --text_only
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

occurances=`grep -n "$2" "$1"`
echo "$occurances"
}

findAllSentencesInFile() {
	file="$1"

	relevantLines=$(findOccurances $file "lemma=\"$word\"" )

#Get all occurences of words related to this lema in this document
allwords=$(echo "$relevantLines" | getWords)
documentText=$(cat "$file" | getWords)

test1=$( echo -n `cat "$file"` |  getSourceInfo )

for word in $allwords; do
	((numberOfSentencesFound++))
	verbose "$word:"
	newSentence=$(printDocument | getSentence "$word")

	if [ -z "$newSentence" ]; then
		verbose "$word appeared multiple times in the same sentence! Ignoring it"
	else
		sentences+=("$newSentence")
	sources+=( "$test1" )
	sourceFiles+=( `readlink -e "$file"`)
	documentText=`printDocument | sed "s/$newSentence//"`
fi
done

#printDocument
}

printDocument () {
	echo $documentText | sed 's/\n/ /'
}

getNextFile () {
	verbose "Entering getNextFile in folder $PWD"

	for file in *; do
		if [[ $numberOfSentencesFound -ge $amount ]];  then
			break
		fi
		if ! compareToPointer `readlink -e "$file"` ; then
			verbose "Ignoring $file"
		elif [ -d "$file" ]; then
			verbose "$file is a directory"
			cd "$file"

			getNextFile

			cd ..

		else
			if [ ${file: -4} == ".xml" ]; then
				verbose "Doing stuff with file: $file"
				findAllSentencesInFile "$file"

				pointer=`readlink -e "$file"`
			else
				verbose "file: $file is not an xml file, i don t care."
			fi
		fi
	done

	if [[ $numberOfSentencesFound -lt $amount ]];  then
		pointer="DONE"
	fi
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
echo "BEGINNN"
cd '/appl/kielipankki/hcs/'
#cd 'shellParser'
#next generalize shell script to also find data in books folder

getNextFile


echo "<SENTENCES>"
printArray sentences[@]
echo "</SENTENCES>"
echo "<SOURCESTEXT>"
printArray sources[@]
echo "</SOURCESTEXT>"
echo "<SOURCEFILE>"
printArray sourceFiles[@]
echo "</SOURCEFILE>"

verbose "Found That many sentences: $numberOfSentencesFound"
echo "NEXTPOINTER:$pointer"


