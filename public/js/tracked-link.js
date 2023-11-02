if (window.top === window.self) {
  jQuery(document).ready(function ($) {
    $(".tracked-social-link").on("click", "a", function (e) {
      // Modified Selector
      e.preventDefault();
      var link = $(this).attr("href");
      var userId = $(this).closest(".tracked-social-link").data("user-id"); // Modified Selector

      console.log(link, userId);
      $.post(
        LinkTracker.ajax_url,
        {
          action: "handle_social_link_click",
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

  jQuery(document).ready(function ($) {
    $(".tracked-link").on("click", function (e) {
      e.preventDefault();

      var link = $(this).data("column-clickable"); // Getting link from the data-column-clickable attribute
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
            //
          } else {
            alert("Error tracking click");
          }
        }
      );
    });
  });
}
