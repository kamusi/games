
var userID; // = "858265020879794";
var wordID;
var word;
var definitionID;
var groupID;
var amountOfTweets;

var translationID;

var last20Tweets = {}

//TODO: GENERALISE SERVER REQUESTS

function get_random() {
    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
console.log("getting the data : " + xmlhttp.responseText)
            obj = JSON.parse(xmlhttp.responseText);
            document.getElementById("word").innerHTML = obj.Word;
            document.getElementById("definition").innerHTML = obj.Definition;
        }
    }
    xmlhttp.open("GET","php/get_random.php", true);
    xmlhttp.send();
}

function get_randomForTweets() {
    var returnVal;
    console.log("In random for tweets function")
    //remove previous tweet entries
    document.getElementById("twitterWords").innerHTML = '';

    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            console.log("JSON DATA LOOKS LIKE : " +xmlhttp.responseText)
            obj = JSON.parse(xmlhttp.responseText);
            console.log("response was : " + xmlhttp.responseText);
console.log("getting the data in random for tweets : " + obj[0].Word);
            groupID = obj[0].GroupID;
            wordID = obj[0].WordID;
            console.log("word id BeCaAAAME : " + wordID)
            document.getElementById("word3").innerHTML = obj[0].Word;
            returnVal = obj[0].Word
            word = obj[0].Word;
            document.getElementById("def3").innerHTML = obj[0].Definition;
            document.getElementById("pos3").innerHTML = obj[0].PartOfSpeech;
            console.log("getting the data in EXECUTEDSFWEC : " + obj[0].Word);
            console.log("we will return " + returnVal);
            //get_tweets(returnVal, 20);
            fetchTweetsFromDB(20);
        }
    }

    xmlhttp.open("GET","php/get_ranked_timo.php?userID=" + userID, true);
    xmlhttp.send();
}

function get_tweets(alreadyDisplayed) {
    console.log("inside the tweets function: ")

    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
        xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            console.log("RESPONSE TEXT : " + xmlhttp.responseText + " END RESPONSE TEXT")
            var results_array = JSON.parse(xmlhttp.responseText);
            console.log("Arrived at the response id" + results_array[0].id);

                var listOfAll = results_array.filter(function(elem, pos) {
                    return results_array.indexOf(elem) == pos;
                  });
            listOfAll.forEach(function(elem, index){
                realIndex = alreadyDisplayed + index;
                last20Tweets[realIndex] = elem;
                 var tweetDisplay = document.createElement("P");
                    tweetDisplay.id = "tweetDisplay" + realIndex;
                    tweetDisplay.name = "elem" ;
                    tweetDisplay.type = "text";

                var newInput = document.createElement("INPUT");
                    newInput.id = "checkbox" + realIndex;
                    newInput.name = "checkbox" ;
                    newInput.type = "checkbox";
                    var t = document.createTextNode(elem.Text);
                    tweetDisplay.appendChild(newInput);
                    tweetDisplay.appendChild(t);
                document.getElementById("twitterWords").appendChild(tweetDisplay);
            })
        }
    }
    console.log(alreadyDisplayed)
    console.log("HAHA : GET","php/get_tweets.php?keyword=" + word + "&amount=" + (amountOfTweets - alreadyDisplayed))
    xmlhttp.open("GET","php/get_tweets.php?keyword=" + word + "&amount=" + (amountOfTweets - alreadyDisplayed));

    xmlhttp.send();
}

function fetchTweetsFromDB(amount) {
    amountOfTweets = amount;
    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
        xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {


            console.log("REsPONSE : " + xmlhttp.responseText + "End response");

            var results_array = JSON.parse(xmlhttp.responseText);
            var i = 0
            for( i = 0; i<amount && typeof results_array[i] !== 'undefined'; i++) {
                last20Tweets[i] = results_array[i];
                 var tweetDisplay = document.createElement("P");
                    tweetDisplay.id = "tweetDisplay" + i;
                    tweetDisplay.name = "elem" ;
                    tweetDisplay.type = "text";

                var newInput = document.createElement("INPUT");
                    newInput.id = "checkbox" + i;
                    newInput.name = "checkbox" ;
                    newInput.type = "checkbox";
                    var t = document.createTextNode(results_array[i].Text);
                    tweetDisplay.appendChild(newInput);
                    tweetDisplay.appendChild(t);
                document.getElementById("twitterWords").appendChild(tweetDisplay);
            
               
            }
            console.log("this was i " + i + ", this is amount : " + amount);
            if(i < amountOfTweets) {
               get_tweets( i);
            }

        }
    }
    console.log("WORD ID IS : "+ wordID)

    xmlhttp.open("GET","php/fetch_tweet_db.php?wordID=" + wordID + "&amount=" + amount);

    xmlhttp.send();


}

function submitTweets() {
        var listToSubmit = [];
    for(var i= 0; i < amountOfTweets; i++) {
        if(document.getElementById("checkbox"+i).checked) {
            sendGoodExampleToDB(last20Tweets[i])
            console.log("about to send this to php " + last20Tweets[i].TweetID )
        }
        else {
            sendBadExampleToDB(last20Tweets[i]);
        }
    }

}

function sendBadExampleToDB(tweet){
  var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
        console.log("DB esponse was:  : " + xmlhttp.responseText)
    }
    }
        var json_data= {"wordID": wordID, "tweetID":tweet.TweetID, "tweetText":tweet.Text, "userID":userID, "tweetAuthor":tweet.Author, "good" : -1    }

    $.ajax({
        type: 'POST',
        url: 'php/submit_tweet.php',
        data: {json: JSON.stringify(json_data)},
        dataType: 'json'
    })
    .done( function( data ) {
        console.log('done');
        console.log(data);
    })
    .fail( function( data ) {
        console.log('fail');
        console.log(data);
    });

}

function sendGoodExampleToDB(tweet){
  var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
         if (xmlhttp.readyState==4 && xmlhttp.status==200) {
        console.log("DB esponse was:  : " + xmlhttp.responseText)
    }
    }

    var json_data= {"wordID": wordID, "tweetID":tweet.TweetID, "tweetText":tweet.Text, "userID":userID, "tweetAuthor":tweet.Author, "good" : 1    }

    $.ajax({
        type: 'POST',
        url: 'php/submit_tweet.php',
        data: {json: JSON.stringify(json_data)},
        dataType: 'json'
    })
    .done( function( data ) {
        console.log('done');
        console.log(data);
    })
    .fail( function( data ) {
        console.log('fail');
        console.log(data);
    });

    console.log("Just sent request to the db" + tweet.Author )
}

function updateScores(){
  var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
         if (xmlhttp.readyState==4 && xmlhttp.status==200) {
        console.log("DB esponse was:  : " + xmlhttp.responseText)
    }
    }



 //  xmlhttp.open("GET","php/get_tweets.php?keyword=" + tweet);
    xmlhttp.send();
    console.log("Just sent request to the db" + tweet.Author )

}



function get_ranked() {

    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		console.log("LALALAL : " + xmlhttp.responseText)
            var results_array = JSON.parse(xmlhttp.responseText);

            clear_definitions();
            wordID = results_array[0].WordID;
            groupID = results_array[0].GroupID;
            // if(results_array[0].Consensus == 1) {
            //     set_consensus_word(results_array[0].Word, results_array[0].PartOfSpeech, results_array[0].Definition);
            //     add_definition(results_array[0].DefinitionID, '✓ That is a good definition');
            //     for(var i = 1; i < results_array.length; i++) {
            //         if(results_array[i].Definition != undefined) {
            //             add_definition(results_array[i].DefinitionID, results_array[i].Definition);
            //         }
            //     }
            // }
            // else {
            set_word(results_array[0].Word, results_array[0].PartOfSpeech);
            add_definition(-1, "? I can't say - skip this one...", 'definitions');

            document.getElementById("consensus").innerHTML = "General Sense:";

            for(var i = 0; i < results_array.length; i++) {
                if(results_array[i].Author == 'wordnet') {
                    set_consensus(results_array[i].Definition);
                    add_definition(results_array[i].DefinitionID, "▶ Keep the General Sense. It's a good definition as is!");
                }
                else if(results_array[i].Definition != undefined) {
                    add_definition(results_array[i].DefinitionID, "▶ " + results_array[i].Definition);
                }
            }
            // }
            definitionID = -1;
        }
    }
    // alert(token);
    // document.getElementById("token").innerHTML = token;
    
    xmlhttp.open("GET","php/get_ranked.php?userID=" + userID + "&token=" + token, true);
    xmlhttp.send();
}

function submit_definition(definition) {
    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.open("GET","php/submit_definition.php?wordID=" + wordID + "&groupID=" + groupID  + "&definition=" + definition + "&userID=" + userID, true);
    xmlhttp.send();
}


function check_user(response) {

    console.log("CHECK USER")
    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            obj = JSON.parse(xmlhttp.responseText);
            if(obj.CheckResult == 1) {
                // document.getElementById('greeting').innerHTML = 'Welcome back, ' + response.name + '!';
                // document.getElementById('profile_name').innerHTML = response.name;
            }
            else {
                // document.getElementById('greeting').innerHTML = 'Welcome, ' + response.name + '!';
                // document.getElementById('profile_name').innerHTML = response.name;
            }
            set_greeting(response.name);
            userID = response.id;
            initialise(userID);
        }
    }
    var noCache = new Date().getTime();
    xmlhttp.open("GET","php/check_user.php?userID=" + response.id + "&noCache=" + noCache, true);
    xmlhttp.send();
}

function get_user_stats() {
    console.log(  "GET USER STATS")
    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            // alert(xmlhttp.responseText);
            var obj = JSON.parse(xmlhttp.responseText);
            console.log("Pending Points : " +  obj.PendingPoints)
            set_profile_data(obj.UserID, obj.Points, obj.PendingPoints, obj.Position, obj.Notify);
        }
    }
    // document.getElementById("token").innerHTML = token;
    xmlhttp.open("GET","php/get_profile.php?userID=" + userID + "&token=" + token, true);
    xmlhttp.send();
}

function get_user_trophies() {
    /*var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            var results_array = JSON.parse(xmlhttp.responseText);
            if(results_array != undefined) {
                for(var i = 0; i < results_array.length; i++) {
                    if(results_array[i].Definition != undefined) {
                        add_trophy(results_array[i].Word, results_array[i].Definition);
                    }
                }
            }
        }
    }
    xmlhttp.open("GET","php/get_trophies.php?userID=" + userID, true);
    xmlhttp.send();*/
}

function submit_vote(definition_id, vote) {
    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.open("GET","php/submit_vote.php?wordID=" + wordID + "&definitionID=" + definition_id + "&vote=" + vote, true);
    xmlhttp.send();
}

function report_spam() {
    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.open("GET","php/report_spam.php?wordID=" + wordID + "&definitionID=" + definitionID + "&userID=" + userID, true);
    xmlhttp.send();
}

function complete_notification() {
    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.open("GET","php/complete_notification.php?userID=" + userID, true);
    xmlhttp.send();
}

function get_ranked_mode_2() {
    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            obj = JSON.parse(xmlhttp.responseText);
            document.getElementById("translation_word").innerHTML = obj.Word;
            document.getElementById("translation_pos").innerHTML = obj.PartOfSpeech;
            document.getElementById("translation_definition").innerHTML = "General Sense: " + obj.Definition;

            var underscored_word = obj.Word.replace(" /g", "_");

            document.getElementById("wiktionary").href = "https://en.wiktionary.org/wiki/" + underscored_word;
            document.getElementById("dictionary").href = "http://dictionary.reference.com/browse/" + underscored_word;
            document.getElementById("wordnik").href = "https://www.wordnik.com/words/" + underscored_word;
            translationID = obj.ID;
            // var newBottom = document.getElementById("translation_entry").getBoundingClientRect().bottom;
            // var intString = (newBottom + 100).toString() + "px";
            // document.getElementById("translation_input_tool_box").style.top="100px";
        }
    }
    xmlhttp.open("GET","php/get_ranked_mode_2.php?userID=" + userID, true);
    xmlhttp.send();
}

function submit_translation(translation) {
    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    var noCache = new Date().getTime();
    xmlhttp.open("GET","php/submit_translation.php?translation=" + translation + "&wordID=" + translationID + "&userID=" + userID + "&noCache=" + noCache, true);
    xmlhttp.send();
}
