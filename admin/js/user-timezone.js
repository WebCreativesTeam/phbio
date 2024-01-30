jQuery(document).ready(function ($) {
  // Select the div element with the class 'dashboard-layout'
  var dashboardDiv = document.querySelector(".dashboard-layout");
  // Get the value of the 'data-user-id' attribute
  var userId = dashboardDiv.getAttribute("data-user-id");
  var userTimeZone = dashboardDiv.getAttribute("data-user-timezone");

  // Check if cookie is not set
  if (userId && !userTimeZone) {
    var timeZone = geoplugin_timezone();
    console.log(timeZone);

    $.ajax({
      type: "POST",
      url: timezonePlugin.ajax_url,
      data: {
        action: "handle_record_time_zone",
        nonce: timezonePlugin.nonce,
        user_id: userId,
        time_zone: timeZone,
      },
    });
  }
});
