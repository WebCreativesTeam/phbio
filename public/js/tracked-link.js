jQuery(document).ready(function ($) {
  $(".tracked-link").on("click", function (e) {
    e.preventDefault();
    var link = $(this).attr("href");
    var userId = $(this).data("user-id");

    $.post(
      LinkTracker.ajax_url,
      {
        action: "handle_link_click",
        link: link,
        user_id: userId,
      },
      function (response) {
        if (response.success) {
          window.location.href = link;
        } else {
          alert("Error tracking click");
        }
      }
    );
  });
});
