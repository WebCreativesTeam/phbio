jQuery(document).ready(function ($) {
  // Check if cookie is not set
  if (!getCookie("page_viewed")) {
    $.ajax({
      type: "POST",
      url: BioTracker.ajax_url,
      data: {
        action: "handle_record_page_view",
        page_link: window.location.href,
      },
    });
    // Set cookie for 1 day
    setCookie("page_viewed", "true", 1);
  }

  function setCookie(name, value, days) {
    var date = new Date();
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
    var expires = "expires=" + date.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
  }

  function getCookie(name) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");
    if (parts.length == 2) return parts.pop().split(";").shift();
  }
});
