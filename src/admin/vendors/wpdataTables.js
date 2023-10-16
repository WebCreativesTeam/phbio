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

// Function to check if the table is empty
function checkEmptyTable(tableID) {
  // Select the specific table by its unique class. Replace wpDataTableID with your actual unique identifier
  var table = document.querySelector(".wpDataTableID-" + tableID);

  // Check if the "dataTables_empty" element exists within this table
  return table && table.querySelector(".dataTables_empty");
}

// Function to process an array of table IDs
function processTables() {
  // Select all elements with the "empty-analytic" class
  var emptyAnalytics = document.querySelectorAll(".empty-analytic");

  // Iterate over the emptyAnalytics elements
  emptyAnalytics.forEach(function (emptyAnalytic) {
    // Get the data-wptable attribute, which contains the table IDs
    var tableIDs = emptyAnalytic.getAttribute("data-wptable");

    // Split the table IDs into an array
    tableIDs = tableIDs.split(",");

    // Check each table ID
    tableIDs.forEach(function (tableID) {
      var isEmpty = checkEmptyTable(tableID.trim());

      if (isEmpty) {
        // If the table is empty, show the empty analytic element
        emptyAnalytic.style.display = "block";
      } else {
        // If the table is not empty, you might want to keep the notice hidden
        // emptyAnalytic.style.display = "none"; // Uncomment if you need this
      }
    });
  });
}

// Window onload event
window.onload = function () {
  // Process the tables
  processTables();
};
