
$(document).on('pageshow','#my-account',function(){
  loggedInTest()
  .done(function(result) {
    debugAlert('loggedInTest result '+result + ' kamusi '+JSON.stringify(kamusiUser));
    if (
      kamusiUser == 'undefined' ||
      kamusiUser.uid === 0 ||
      kamusiUser.uid == 'undefined' ||
      kamusiUser.uid === null
      ) {
      alert('Anonymous access. Please login/register below');

      $("#login-form").show();
      $("#my-account").hide();
    }
    else {
      $("#login").hide();
      getFields()
      .done(function (data) {
        console.debug(data);
       // $("#login_div").hide();
        $("#my-account").show();
        $("#logout").show();

        // $('#my-account').html('');
        var user_fields = data.user.user.fields;
        /*
        $('#my-account').append('<form><pre><fieldset>');
        var dispay_fields = "";
        var user_firstname = '';
        var user_surname = '';
        var user_contact = '';
        var user_account = '';
        var user_name = '';
        var user_email = '';

        jQuery.each(user_fields, function(i, val) {
            // Create the appropriate widget
          if (user_fields[i]["widget"]["type"] == "text_textfield") {

            if(i == 'field_user_first_name'){
              $('#my_account_fields_div').append('<div><label for"' + i + '">'+ user_fields[i]["label"] +'</label><input type="text" id="'+ i +'" value="'+ user_firstname +'"></div>');
            }
            else if(i == 'field_user_surname'){
              $('#my_account_fields_div').append('<div><label for"' + i + '">'+ user_fields[i]["label"] +'</label><input type="text" id="'+ i +'" value="'+ user_surname +'"></div>');
            }
            else {
              $('#my_account_fields_div').append('<div><label for"' + i + '">'+ user_fields[i]["label"] +'</label><input type="text" id="'+ i +'" value=""></div>');
            }
          }

          else if (user_fields[i]["widget"]["type"] == "mail") {
            $('#my_account_fields_div').append('<div><label for"' + i + '">'+ user_fields[i]["label"] +'</label><input type="email" id="'+ i +'" value="'+ user_email +'"></div>');
          }

          else if (user_fields[i]["widget"]["type"] == "phone_number") {
            $('#my_account_fields_div').append('<div><label for"' + i + '">'+ user_fields[i]["label"] +'</label><input type="tel" id="'+ i +'" value="'+ user_contact +'"></div>');
          }
          else if (i == 'field_user_account_number') {
            $('#my_account_fields_div').append('<div><label for"' + i + '">'+ user_fields[i]["label"] +'</label><input type="text" id="'+ i +'" value="'+ user_account +'"></div>');
          }
          else {
            // alert(i + " type:" + report_fields[i]["widget"]["type"]);
          }
        });

        $('#my_account_fields_div').append('<div id="accountinfo_collapsible" data-role="collapsible" data-collapsed="false"><h3>Login Info</h3></div>');
        $('#accountinfo_collapsible').append('<div><label for"username">Username</label><input type="text" id="username" value="'+ user_name +'"></div>');
        $('#accountinfo_collapsible').append('<div><label for"email">Email</label><input type="email" id="email" value="'+ user_email +'"></div>');
        $('#my_account_fields_div').append('<div><button type="button" id="page_update_user_submit">Update details</button></div></pre></fieldset></form>');
        $('#my_account_fields_div').trigger('create');
        */
      })
      .fail(function() {
      });
    }
  })
  .fail(function() {
    alert('Logged in Test failed');
  });
});


/* Update user from my account */
$(document).on('click','#page_update_user_submit',function(){

  var user_firstname = $('#field_user_first_name').val();
  var user_surname = $('#field_user_surname').val();
  var user_contact = $('#field_user_contact_no').val();
  var user_account = $('#field_user_account_number').val();
  var username = $('#username').val();
  var mail = $('#email').val();

  var post_data = {
    "name": username,
    "pass": sess_pass,
    "mail": mail,
    "field_user_account_number": {
      "und": [{
        "value": user_account
      }]
    },
    "field_user_surname": {
      "und": [{
        "value": user_surname
      }]
    },
    "field_user_first_name": {
      "und": [{
        "value": user_firstname
      }]
    },
    "field_user_contact_no": {
      "und": [{
        "number": user_contact,
        "country_codes": "za"
      }]
    }
  };

  $.ajax({
    url: kamusiUser.base_url + "facebook_game_v1/user/" + kamusiUser.uid + ".json",
    type:"put",
    dataType:"json",
    data : post_data,
    headers: { 'Cookie': kamusiUser.session_name + "=" + kamusiUser.session_id,
               'X-CSRF-Token' : kamusiUser.csrf_token},
    xhrFields: {
      withCredentials: true
    },
    beforeSend: function (request) {
      request.setRequestHeader("Cookie", kamusiUser.session_name + "=" + kamusiUser.session_id);
    },
    error:function (jqXHR, textStatus, errorThrown) {
      // alert(jqXHR + " " + textStatus + " " + errorThrown);
    },
    success: function (result) {
      // alert('Your information has been updated');
      $.mobile.changePage("index.html#my-account", "slideup");
    }
  });
});

