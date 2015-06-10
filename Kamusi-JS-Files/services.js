/**
 * Main Services
  PHP: use cURL instead of ajax
  If kamusi up, send right away
  If not up, do cronjob and try to send during night.
  IF  it fails, em ail to martin.

 */

function getToken(session_name, session_id, base_url) {
  return $.ajax({
    url: base_url + "/facebook_game_v1/user/token.json",
    type:"post",
    dataType: "json",
    cache: false,
    timeout: 30000,
    headers: { 'Cookie': session_name + "=" + session_id,},
    xhrFields: {
      withCredentials: true
    },
    beforeSend: function (request) {
      if ((session_name != '') && (session_id != '')){
        request.setRequestHeader("Cookie", session_name + "=" + session_id);
      }
    },
    error:function (jqXHR, textStatus, errorThrown) {
      errorAlert('failed to get token');
      errorAlert('base_url='+base_url+'/facebook_game_v1/user/token.json');
      errorAlert('error='+JSON.stringify(jqXHR) + " " + textStatus + " " + errorThrown);
    },
    success: function (result) {
      kamusiUser.csrf_token = result.token;
    }
  });
}

function connect(session_name, session_id, csrf_token, base_url) {
  var connectHeaders = {};
//  var connectXHRFields = {};
debugAlert('connect, token sent:'+csrf_token);
  if (csrf_token !== '' || csrf_token !== 'undefined') {
    connectHeaders = {
      'X-CSRF-Token' : csrf_token
    };
//    connectXHRFields = {
//      withCredentials: true
//    };
  }
  debugAlert('headers for connect:'+JSON.stringify(connectHeaders));
  return $.ajax({
    url: base_url + "/facebook_game_v1/system/connect.json",
    type:"post",
    dataType: "json",
    //async: false,
    cache: false,
    timeout: 30000,
    headers: connectHeaders,
    error:function (jqXHR, textStatus, errorThrown) {
      errorAlert('failed to connect');
      errorAlert('error='+JSON.stringify(jqXHR) + " " + textStatus + " " + errorThrown);
    },
    success: function (result) {
      debugAlert('Successfully connected.');
      storeUserData(base_url, result.user.uid, result.session_name, result.sessid, csrf_token);
    },
  });
}


function login(sess_user, sess_pass, base_url) {
  return $.ajax({
    url: base_url + "/facebook_game_v1/user/login.json",
    type:"post",
    dataType:"json",
    //async: false,
    cache: false,
    timeout: 30000,
    data : {
      name: sess_user,
      username: sess_user,
      pass: sess_pass,
      password: sess_pass
    },
   // xhrFields: {
   //   withCredentials: true
   // },
    error:function (jqXHR, textStatus, errorThrown) {
      errorAlert('Login Failed');
      errorAlert('error='+JSON.stringify(jqXHR) + " " + textStatus + " " + errorThrown);
    },
    success: function (result) {
      debugAlert('Successfully logged in.');
      debugAlert(JSON.stringify(result));
      storeUserData(base_url, result.user.uid, result.session_name, result.sessid, result.token);
    }
  });
}

function logout() {
    // User logout
  $.ajax({
    url: kamusiUser.base_url + "/facebook_game_v1/user/logout.json",
    type:"post",
    dataType:"json",
    headers: { 'Cookie': kamusiUser.session_name + "=" + kamusiUser.session_id,
               'X-CSRF-Token' : kamusiUser.csrf_token},
    xhrFields: {
      withCredentials: true
    },
    beforeSend: function (request) {
      request.setRequestHeader("Cookie", kamusiUser.session_name + "=" + kamusiUser.session_id);
    },
    error:function () {
      alert('Logout Failed');
        debugAlert('base_url='+kamusiUser.base_url+'/facebook_game_v1/user/logout.json');
        debugAlert('error='+JSON.stringify(jqXHR) + " " + textStatus + " " + errorThrown);
    },
    success: function (data) {
      // Clear all vaiables
    //  kamusiUser = {};
    //  clearData('all');
      alert('You logged out');
    //  $.mobile.changePage("index.html#page_my_account", "slideup");
    }
  });
}

function getLanguages() {
  languages = getData('kamusiLanguages', 0, 0);
  debugAlert('languages from data:'+languages);
  if (languages == 'undefined' || languages === null || languages === undefined) {
    console.debug(kamusiUser.base_url + "/facebook_game_v1/request-languages.json");
    return $.ajax({
      url: kamusiUser.base_url + "/facebook_game_v1/request-languages.json",
      type:"get",
      dataType: "json",
      error:function (jqXHR, text_status, errorThrown) {
        $("#languages").html("Error loading languages. Please try again by refreshing, or contact a system administrator. "+errorThrown+"<br/>");
        errorAlert('error='+JSON.stringify(jqXHR) + " " + textStatus + " " + errorThrown);
      },
      success: function (languages) {
        // alert('Received languages:'+JSON.stringify(languages));
        storeData('kamusiWords', languages);
      }
    });
  }
  else {
    debugAlert('Reports from storage');

    var storageResult = $.Deferred();
    storageResult.resolve(languages);
    storageResult.done(function(languages){ return languages; });
    //alert(JSON.stringify(languages));
    return storageResult;
  }
}

function getWords() {
  words = getData('kamusiWords', 0, 0);
  debugAlert('words from data:'+words);
  if (words == 'undefined' || words === null || words === undefined) {
    return $.ajax({
      url: kamusiUser.base_url + "/facebook_game_v1/search-define.json",
      type:"get",
      dataType: "json",
      error:function (jqXHR, text_status, errorThrown) {
        $("#words").html("Error loading words. Please try again by refreshing, or contact a system administrator. "+errorThrown+"<br/>");
        errorAlert('error='+JSON.stringify(jqXHR) + " " + textStatus + " " + errorThrown);
      },
      success: function (words) {
        // alert('Received words:'+JSON.stringify(words));
        storeData('kamusiWords', words);
      }
    });
  }
  else {
    debugAlert('Reports from storage');

    var storageResult = $.Deferred();
    storageResult.resolve(words);
    storageResult.done(function(words){ return words; });
    //alert(JSON.stringify(words));
    return storageResult;
  }
}

function getWord(word_id) {
  return $.ajax({

    url: kamusiUser.base_url + "/facebook_game_v1/node/" + word_id + ".json",
    type: "GET",
    dataType: "json",
    headers: { 'Cookie': kamusiUser.session_name + "=" + kamusiUser.session_id,
               'X-CSRF-Token' : kamusiUser.csrf_token},
    xhrFields: {
      withCredentials: true
    },
    beforeSend: function (request) {
      request.setRequestHeader("Cookie", kamusiUser.session_name + "=" + kamusiUser.session_id);
    },
    error: function (jqXHR, text_status, errorThrown) {
       alert('couldnt get word details:'+text_status+' error:'+errorThrown);
      $("#full_word_details").append("Error: " + errorThrown + "<br/>");
    },
    success: function (data) {
      debugAlert('got data:'+JSON.stringify(data));
      var html = "<div data-inset='true'>" + "Report #" + data.nid + "<h3>Report details:</h3>";
      debugAlert('photos:'+JSON.stringify(data.field_word_photos.und));
       // if (data.field_word_photos.und) {
       // var images = '<div class="word_image_wrapper">';
       //   for (i = 0; i < data.field_word_photos.und.length; i++) {
       //    //alert('photo '+i+' path:'+kamusiUser.base_url);
       //    //alert(kamusiUser.base_url + '/sites/municipality.kamusi.telamenta.com/files/public/');
       //    //alert(data.field_word_photos.und[i].filename);
       //    alert('full path:'+kamusiUser.base_url + '/sites/municipality.kamusi.telamenta.com/files/' + data.field_word_photos.und[i].filename);
       //   // alert('photo '+i+' path:'+kamusiUser.base_url + '/sites/municipality.kamusi.telamenta.com/files/' + data.field_word_photos.und[i].filename);
       //    // images += '<span class="word_image">';
       //    // images += '<img src="' + kamusiUser.base_url + '/sites/municipality.kamusi.telamenta.com/files/' + data.field_word_photos.und[i].filename + '" />';
       //    // images += '</span>';
       //    // images += '<br/>';
       // //alert('received data:'+images);
       //   }
       //   images += '</div>';
       //  // alert('images:'+images);
       // }

      html += "<div class='word_details'><strong>Description:</strong><br/>" + data.field_word_description.und[0].value;
      // html += "
      // <div class='ui-grid-a'>
      //   <div class='ui-block-a'>
      //     <span>Street:</span>
      //   </div>
      //   <div class='ui-block-b'>
      //     <span>" + data.field_word_location.und[0].street + "</span>
      //   </div>
      //   <div class='ui-block-a'>
      //     <span>City:</span>
      //   </div>
      //   <div class='ui-block-b'>
      //     <span>" + data.field_word_location.und[0].city + "</span>
      //   </div>
      //   <div class='ui-block-a'>
      //     <span>Town:</span>
      //   </div>
      //   <div class='ui-block-b'>
      //     <span>" + data.field_word_location.und[0].province_name + "</span>
      //   </div>
      // </div>";
      html += "</div>";
      html += "</div>";
      $("#full_word_details").html(html);
      debugAlert('received data:'+html);
    }
  });
}

function getFields() {
  debugAlert('get fields');

  fields = getData('kamusiFields', 0, 0, 'true');
  if (fields === 'undefined' || fields === null || fields === undefined) {
    alert('Services: Fields from Request');
    alert(JSON.stringify(kamusiUser));
    return $.ajax({
      url: kamusiUser.base_url + "/facebook_game_v1/fields.json?device_filter=mobile",
      type: "get",
      dataType: "json",
      headers: {
        'Cookie': kamusiUser.session_name + "=" + kamusiUser.session_id,
        'X-CSRF-Token' : kamusiUser.csrf_token
      },
      // xhrFields: {
      //   withCredentials: true
      // },
      error: function (jqXHR, textStatus, errorThrown) {
        errorAlert('Failed to retrieve Fields');
      },
      success: function (fields) {
        debugAlert('Services received fields: '+JSON.stringify(fields));
        storeData('kamusiFields', fields);
      }
    });
  }
  else {
    debugAlert('Services: Fields from storage');
    debugAlert(JSON.stringify(fields));

    var storageResult = $.Deferred();
    storageResult.resolve(fields);
    storageResult.done(function(fields){ return fields; });
    //alert(JSON.stringify(fields));
    return storageResult;
  }
}



function addNode(post_data) {

  return $.ajax({
    url: kamusiUser.base_url + "/facebook_game_v1/node/",
    type:"post",
    dataType: "json",
    data : post_data,
    headers: {
      'Cookie': kamusiUser.session_name + "=" + kamusiUser.session_id,
      'X-CSRF-Token' : kamusiUser.csrf_token
    },
    xhrFields: {
      withCredentials: true
    },
    beforeSend: function (request) {
      request.setRequestHeader("Cookie", kamusiUser.session_name + "=" + kamusiUser.session_id);
    },
    error:function (jqXHR, textStatus, errorThrown) {
      errorAlert('Node not created');
      errorAlert(JSON.stringify(jqXHR));
      errorAlert(JSON.stringify(textStatus));
      errorAlert(JSON.stringify(errorThrown));
    },
    success: function (result) {
      var nid = result.nid;
      alert('Thank you. Report: '+nid+' added');

      // Create a formdata object and add the files
      var form_data = new FormData();
      var fileSelect = document.getElementById('file-select1');
      var files = fileSelect.files;

      // Loop through each of the selected files.
      debugAlert('files-stringify:'+JSON.stringify(files));
      debugAlert('length:'+files.length);
      if (files !== 'undefined') {
        for (var i = 0; i < files.length; i++) {

          var file = files[i];
         //  console.log(file);
         debugAlert('increment: '+i);
         debugAlert('file:'+file);

          // Check the file type.
          if (!file.type.match('image.*')) {
            alert('file is not an image');
            continue;
          }

          // Add the file to the request.
          form_data.append('files[]', file, file.name);

          // var formURL = base_url + "/facebook_game_v1/node/" + nid + "/attach_file";
          // var encodedURI = encodeURI(formURL);
          // var file = image_uris[i];
          // var fileURI = file.src;
          // var imgData = readFileAsBinaryString(fileURI);
          // var options = new FileUploadOptions();
          // options.fileKey = "files[]";
          // options.fileName = fileURI.substr(fileURI.lastIndexOf('/') + 1);
          // options.params = { 'apikey': yourApikey, 'file': imgData, 'mode': 'scene_photo' };
          // var ft = new FileTransfer();
          // ft.upload(fileURI, encodedURI, fnSuccess, fnError, options);
        //}
        }
        form_data.append('field_name', 'field_report_photos');
        debugAlert('form data:'+JSON.stringify(form_data));
        addFile(form_data, nid);
      }
    }

  });
}

function addFile(form_data, nid) {
  return $.ajax({
    url: kamusiUser.base_url + "/facebook_game_v1/node/" + nid + "/attach_file",
    type:"post",
    dataType: "json",
    data : form_data,
    processData: false, // Don't process the files
    contentType: false, // Set content type to false as jQuery will tell the server its a query string request
    headers: {
      'Cookie': kamusiUser.session_name + "=" + kamusiUser.session_id,
      'X-CSRF-Token' : kamusiUser.csrf_token
    },
    xhrFields: {
      withCredentials: true
    },
    beforeSend: function (request) {
      request.setRequestHeader("Cookie", kamusiUser.session_name + "=" + kamusiUser.session_id);
    },
    error:function (jqXHR, textStatus, errorThrown) {
      errorAlert('oops: add File');
      errorAlert(textStatus);
      errorAlert(errorThrown);
    },
    success: function (result) {
      debugAlert('attached image');
    }
  });
}
