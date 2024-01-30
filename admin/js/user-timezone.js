jQuery(document).ready(function ($) {
  // Select the div element with the class 'dashboard-layout'
  var dashboardDiv = document.querySelector(".dashboard-layout");
  // Get the value of the 'data-user-id' attribute
  var userId = dashboardDiv.getAttribute("data-user-id");
  var userTimeZone = dashboardDiv.getAttribute("data-user-timezone");

  console.log(userId); // This will log the user ID to the console
  console.log(userTimeZone); // This will log the user ID to the console

  // Check if cookie is not set
  if (userId && !timeZone) {
    var timeZone = geoplugin_timezone();

    // $.ajax({
    //   type: "POST",
    //   url: UserTimeZone.ajax_url,
    //   data: {
    //     action: "handle_record_time_zone",
    //     user_id: userId,
    //     time_zone: timeZone,
    //   },
    // });
  }
});
