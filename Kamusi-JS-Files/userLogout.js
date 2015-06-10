
$(document).on('click','#logout',function(){
  logout()
  .done(function(data) {
      // Clear all vaiables
      kamusiUser = {};
      clearData('all');
      $.mobile.changePage("index.html#page_my_account", "slideup");
    })
  .fail(function() {
    alert('Logout failed');
  });
});
