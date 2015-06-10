var LANGUAGE_NONE = "und";
var kamusiMainSiteURL = "http://dev.kamusi.org:8282"; // When the App goes live, this should be: https://kamusi.org

//The user
var kamusiUser = {
  base_url: kamusiMainSiteURL,
};
var municipalities = 'undefined';
var operators = 'undefined';


var icon_not_done = '<span class="ui-icon-delete ui-btn-icon-notext ui-btn-inline ui-btn-icon-left" style="position:relative !important;"></span>';
var icon_done     = '<span class="ui-icon-check  ui-btn-icon-notext ui-btn-inline ui-btn-icon-left" style="position:relative !important;"></span>';
var icon_problem  = '<span class="ui-icon-alert  ui-btn-icon-notext ui-btn-inline ui-btn-icon-left" style="position:relative !important;"></span>';
var icon_partial  = '<span class="ui-icon-alert  ui-btn-icon-notext ui-btn-inline ui-btn-icon-left" style="position:relative !important;"></span>';


$(document).ajaxStart(function() {
  $.mobile.loading('show');
});

$(document).ajaxStop(function() {
  $.mobile.loading('hide');
});


//clearData('all');
loggedInTest();

//setInterval(meterReadingsSync, (1000 * 60 * 15));

function loggedInTest() {
//  alert('performing logged in test');
  $.support.cors = true;
  var empty = 'false';
  kamusiUser = getData('kamusiUser',0,0,'false');
  debugAlert('Logged in kamusiUser:'+JSON.stringify(kamusiUser));
  if (kamusiUser === null || kamusiUser === undefined) {
    empty = 'true';
  }
  else if (
    ((kamusiUser.base_url === "") || (kamusiUser.base_url === undefined)) ||
    (kamusiUser.uid === 'undefined')
    ) {
    empty = 'true';
  }
  debugAlert('empty?'+empty);
  if (empty === 'false') {
    kamusiMainSiteURL = kamusiUser.base_url;
  }
  else {
    kamusiUser = {
      base_url: kamusiMainSiteURL,
  //    municipality_id: '',
  //    operator_id: '',
      uid: 0,
      session_name: '',
      session_id: '',
      csrf_token: '',
    };
  }
  var returnResult = $.Deferred();
  returnResult.resolve(empty);
  returnResult.done(function(empty){ return  empty; });
  return returnResult;
}

function debugAlert(alertText) {
  //alert(alertText);
}

function errorAlert(alertText) {
  alert('ERROR: '+alertText);
}

// Extend jQuery to return the Query parameters in a URL
$.extend({
  getUrlVars: function(){
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
      hash = hashes[i].split('=');
      vars.push(hash[0]);
      vars[hash[0]] = hash[1];
    }
    return vars;
  },
  getUrlVar: function(name){
    return $.getUrlVars()[name];
  }
});

function calculateLength(object, name) {
  var length = 0;
  for(var i in object) {
    if (object.hasOwnProperty(i) && (object[i][name] || name === undefined)) {
    //  alert('found:'+i);
      length ++;
    }
  }
  return length;
}


function redirectPage(element) {
  dataUrl = element.attr('data-url');
  $.mobile.changePage(dataUrl, {
    dataUrl: dataUrl,
    transition: "slideup",
    allowSamePageTransition: true
  });
}

//    alert('ALERT!!!!! CLEARING ALL DATA');
//    localStorage.removeItem('kamusiUser');
//    localStorage.removeItem('kamusiReports');
//    localStorage.removeItem('kamusiFields');
//    localStorage.removeItem('kamusiDepartments');
//    localStorage.removeItem('kamusiMunicipalities');
//    localStorage.removeItem('kamusiReportTypes');
