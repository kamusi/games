function storeData(name, data, origin) {
  dataStringStore = '';
  saved = new Date();
  if (data.constructor == Object) {
    data.savedDate = saved;
    data.origin = origin;
  }
  else if (data.constructor == Array) {
    data.push({'savedDate':saved});
    data.push({'origin':origin});
  }
  else {
    alert(name+' construtor UNKNOWN: '+data.constructor);
  }
  dataStringStore = JSON.stringify(data);
  if (dataStringStore !== '') {
    window.localStorage.setItem(name, dataStringStore);
  }
}

function getData(name, expiryDays, expiryHours, expire) {
  if (expiryDays === '' || expiryDays == 'undefined') {
    expiryDays = 0;
  }
  if (expiryHours === '' || expiryHours == 'undefined') {
    expiryHours = 1;
  }
  storage = window.localStorage.getItem(name);
  if (storage === null) {
    return undefined;
  }
  data = $.parseJSON(window.localStorage.getItem(name));
  if (name !== 'kamusiUser' && data.origin !== kamusiMainSiteURL) {
    alert('wrong origin, removing data for: '+name+' data.origin:'+data.origin+' base: '+kamusiMainSiteURL);
    return undefined;
  }
  if (data.Error) {
    alert('Error found:'+name+' '+JSON.stringify(data.Error));
    return undefined;
  }
  alert(name+' = data received:'+JSON.stringify(data));
  var invalidateCache = false;
//  invalidateCache = true;
  if (expire !== 'false') {
    if (data !== 'undefined' && data !== null) {
      if (data.constructor == Object) {
        savedDateString = data.savedDate;
      }
      else if (data.constructor == Array) {
        lastElement = data.pop();
        savedDateString = lastElement.savedDate;
      }
      else {
        alert(name+' construtor UNKNOWN: '+storageItem.constructor);
      }
      var currentDate = Date.today();
      //var savedDate = Date.parse(savedDateString);
      var expiryDate = Date.parse(savedDateString).add({ days: expiryDays, hours: expiryHours });
      var expiryComparison = Date.compare( expiryDate, currentDate );
//      expiryComparison = -1;
      if (expiryComparison === -1) {
        invalidateCache = true;
      }
      //alert(name+' Original: '+savedDateString+'  Current Date: '+currentDate+' SavedDate: '+savedDate+' ExpiryDate: '+expiryDate+' Comparison: '+expiryComparison);
    }
  }

  if (invalidateCache === true) {
    alert(name+' > invalidateCache > '+invalidateCache);
  //  clearData(name);
    return undefined;
  }
  return data;
}

function clearData(name) {
  if (name === 'all') {
    alert('ALERT!!!!! CLEARING ALL DATA');
    localStorage.removeItem('kamusiUser');
  }
  else {
    alert('CLEARING: '+name);
    localStorage.removeItem(name);
  }
}

function storeUserData(base_url, uid, session_name, session_id, csrf_token) {
  var kamusiUser = {
    base_url: base_url,
    uid: uid,
    session_name: session_name,
    session_id: session_id,
    csrf_token: csrf_token,
  };
  alert('STORE USER DATA'+JSON.stringify(kamusiUser));
  storeData("kamusiUser",kamusiUser);
}
