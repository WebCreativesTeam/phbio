window.onload = function () {
  // Select the TH element
  var th_views = document.querySelector("th.column-total_views");
  var th_clicks = document.querySelector("th.column-total_clicks");
  var th_ctr = document.querySelector("th.column-ctr");

  // Check if the TH element exists
  if (th_views) {
    // Your SVG code as a string
    var svgString =
      '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" class="ctr-table-icon" fill="currentColor"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-352a96 96 0 1 1 0 192 96 96 0 1 1 0-192z"></path></svg>';

    // Append the SVG string to the TH innerHTML
    th_views.innerHTML = svgString + th_views.innerHTML;
  }
  if (th_clicks) {
    // Your SVG code as a string
    var svgString =
      '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" class="ctr-table-icon" fill="currentColor"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-352a96 96 0 1 1 0 192 96 96 0 1 1 0-192z"></path></svg>';

    // Append the SVG string to the TH innerHTML
    th_clicks.innerHTML = svgString + th_clicks.innerHTML;
  }
  if (th_ctr) {
    // Your SVG code as a string
    var svgString =
      '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" class="ctr-table-icon" fill="currentColor"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-352a96 96 0 1 1 0 192 96 96 0 1 1 0-192z"></path></svg>';

    // Append the SVG string to the TH innerHTML
    th_ctr.innerHTML = svgString + th_ctr.innerHTML;
  }
};

// JavaScript function to check for an element with a specific class within a table
function checkEmptyTable(tableID) {
  // Construct the selector for the specific table
  var selector = ".wpDataTableID-" + tableID + " .dataTables_empty";

  // Check if the element exists
  var isEmpty = document.querySelector(selector) !== null;

  // If the table is empty
  if (isEmpty) {
    // You can hide the table wrapper or perform other actions here
    var tableWrapper = document.querySelector(".table-wrapper");
    if (tableWrapper) {
      tableWrapper.style.display = "none"; // hide the table wrapper
    }

    // For the purpose of this example, we're returning true if the table is empty
    return true;
  }

  // Return false if the table isn't empty
  return false;
}
