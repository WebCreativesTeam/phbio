window.onload = function () {
  // Select the TH element
  var th = document.querySelector("th.column-total_views");

  // Check if the TH element exists
  if (th) {
    // Your SVG code as a string
    var svgString =
      '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" class="text-red text-14" fill="currentColor"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-352a96 96 0 1 1 0 192 96 96 0 1 1 0-192z"></path></svg>';

    // Append the SVG string to the TH innerHTML
    th.innerHTML += svgString;
  }
};
