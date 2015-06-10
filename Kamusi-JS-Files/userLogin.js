/*
1) Connect, see if you have a session_id and session_name
2) If Anonymous == uid->0, you need to login
3) Get Token, anonymous token
4) Do Login
5) Get new Token, Session_id and Session_name from either response of login request, or do a new Get Token
6) Test Connect again, an if UID > 0, you are logged in
*/

$(document).on('click','#login-form #submit',function(){

   alert('login:'+JSON.stringify(kamusiUser));

   //if the user is not known. Id smaller than 0 means initial authentification returned 0
  if (kamusiUser.uid <= 0 || kamusiUser == 'undefined' || kamusiUser.uid == 'undefined') {
    var name = $('#login-form #name').val();
    var pass = $('#login-form #pass').val();

    sess_user = name;
    sess_pass = pass;
    //kamusiUser.base_url = municipality;

    debugAlert('sending token now');
    debugAlert('before token='+JSON.stringify(kamusiUser.csrf_token));

    getToken(kamusiUser.session_name, kamusiUser.session_id, kamusiUser.base_url)
    .done(function(tokenResult) {
      // code depending on result
      debugAlert('token='+JSON.stringify(kamusiUser.csrf_token) + ' returned: ' + JSON.stringify(tokenResult.token));
      debugAlert('sending connect now');

      kamusiUser.csrf_token = tokenResult.token;
      connect(kamusiUser.session_name, kamusiUser.session_id, tokenResult.token, kamusiUser.base_url)
      .done(function(connectResult) {
        // code depending on result
        debugAlert("connected:"+JSON.stringify(connectResult));
        if (kamusiUser.uid === 0 || kamusiUser.uid === 'undefined') {
          debugAlert('sending login now='+sess_user+" "+sess_pass+" "+kamusiUser.base_url);
          login(sess_user, sess_pass, kamusiUser.base_url)
          .done(function(loginResult) {
            // code depending on result
            debugAlert('logged_in='+JSON.stringify(loginResult));
            debugAlert('logged_in FIELDWORKER USER='+JSON.stringify(kamusiUser));
            $.mobile.changePage("index.html#latest-words", "slideup");
          })
          .fail(function() {
            // an error occurred
            errorAlert('login failed 1');
          });
        }
        else {
          alert('successful authentication 1');
          $.mobile.changePage("index.html#latest-words", "slideup");
        }

        debugAlert('kamusiUser='+JSON.stringify(kamusiUser));
      })
      .fail(function() {
        // an error occurred
        errorAlert('connect failed');
      });
    })
    .fail(function() {
      // an error occurred
      errorAlert('did not receive token');
    });
  }
  else {
    alert('successful authentication 2');
    $.mobile.changePage("index.html#latest-words", "slideup");
  }
});

