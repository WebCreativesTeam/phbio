jQuery(document).ready(function ($) {
  var country = geoplugin_countryName();
  // var country = getRandomCountry();
  console.log(country);
  var postId = document.querySelector("article").id.split("-")[1];
  var cookieName = "page_viewed_" + postId;
  // Check if cookie is not set
  if (!getCookie(cookieName)) {
    $.ajax({
      type: "POST",
      url: BioTracker.ajax_url,
      data: {
        action: "handle_record_page_view",
        post_id: postId,
        country: country,
      },
    });
    // Set cookie for 1 day
    setCookie(cookieName, "true", 1);
  }

  function getRandomCountry() {
    // Define an array of 20 countries
    var countries = [
      "United States",
      "Canada",
      "Australia",
      "United Kingdom",
      "Germany",
      "France",
      "Spain",
      "Italy",
      "Brazil",
      "Mexico",
      "Russia",
      "India",
      "China",
      "Japan",
      "South Korea",
      "South Africa",
      "Nigeria",
      "Egypt",
      "Argentina",
      "Chile",
    ];

    // Get a random index from array (0 to array length minus one)
    var randomIndex = Math.floor(Math.random() * countries.length);

    // Return the country that corresponds to the random index
    return countries[randomIndex];
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
