#!/bin/sh

verbose () {	
	if [ $verbose = yes ]; then
		echo "$1"
	fi
}

alreadyencoutered=''

f1 () {
	verbose "Entering f1"
for file in *; do
 if [ -d $file ]; then
  	f1 "$file"
 else
 	$alreadyencoutered="$alreadyencoutered $file"
 	break;
 	echo $alreadyencoutered

 fi
done	
echo "Encountedred : $alreadyencoutered"

}

f2() {
	verbose "Entering f2"
	grep -m 5 simama $1
}





ssh taito <<'ENDSSH'

alreadyencoutered=''

verbose () {	
	if [ $verbose = yes ]; then
		echo "$1"
	fi
}

alreadyencoutered=''

f1 () {
	verbose "Entering f1"
for file in *; do
 if [ -d $file ]; then
  	f1 "$file"
 else
 	$alreadyencoutered="$alreadyencoutered $file"
 	break;
 	echo $alreadyencoutered

 fi
done	
echo "Encountedred : $alreadyencoutered"

}

f2() {
	verbose "Entering f2"
	grep -m 5 simama $1
}

cd '/appl/kielipankki/hcs/articles/'
f2 `f1`
ENDSSH
