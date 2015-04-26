<!--Load and initialise the Facebook API. xfbml checks for active login-->
userName = "???"
function statusChangeCallback(response) {
    if (response.status === 'connected') {
      	welcome();
          var child = document.getElementById("enterLogin");
  if(child != undefined) {
  var parent = document.getElementById("enterLogin").parentNode.removeChild(child);
 }

    } 
    else if (response.status === 'not_authorized') {
      	document.getElementById('word').innerHTML = 'Please log ' + 'into this app.';
    }
    else {
      	document.getElementById('word').innerHTML = 'Please log ' + 'into Facebook.';
        animate_logo_login();
    }
}

function checkLoginState() {
	FB.getLoginStatus(function(response) {
  		statusChangeCallback(response);
	});
}

function checkLoginStateAfterFirstLogin() {
  FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
  });
  location.reload();
}


//Called when page loads
window.fbAsyncInit = function() {
	//Initialise SDK
	FB.init({
	appId      : '1525612724344445',
	cookie     : true,  // enable cookies to allow the server to access 
    		            // the session
	xfbml      : true,  // parse social plugins on this page
	version    : 'v2.1' // use version 2.1
	});

	//Get login status
	FB.getLoginStatus(function(response) {
		statusChangeCallback(response);
	});
};

// Load the SDK asynchronously
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  	} (document, 'script', 'facebook-jssdk')
	);

	function welcome() {
		document.getElementById("login_button").style.display = "none";
	FB.api('/me', function(response) {
                  check_user();
      userID = response.id;
      userName = response.name;
initialise(userID);


  		get_randomForTweets();
        get_ranked();
  get_ranked_mode_2();

	});
}

function share() {
	FB.ui({
			method: 'share',
			href: 'https://apps.facebook.com/thekamusiapp/',
		}, function(response){}
	);
}

function request() {
	FB.ui({method: 'apprequests',
      message: 'Kamusi is Swahili for "dictionary"'
    }, function(response){
        console.log(response);
    });
}

function publishStory(text) {
//text will be the number of achievements, for now the number of validated tweets.
//We will send out a link containing userID and # of validated tweets

FB.api(
    "/me/games.achieves",
    "POST",
    {
        "achievement": "http://ec2-52-11-133-223.us-west-2.compute.amazonaws.com/shareTest.html"
    },
    function (response) {
      if (response && !response.error) {
        /* handle the result */
      }
    }
);

}
