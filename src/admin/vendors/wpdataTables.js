window.onload = function () {
  // Select the TH element
  var th_views = document.querySelector("th.column-total_views");
  var th_clicks = document.querySelector("th.column-total_clicks");
  var th_ctr = document.querySelector("th.column-ctr");
  var th_empty_clicks = document.querySelector("th.column-empty_clicks");

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
  if (th_empty_clicks) {
    // Your SVG code as a string
    var svgString =
      '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512" class="ctr-table-icon" fill="currentColor"><path d="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z"/></svg>';
    // Append the SVG string to the TH innerHTML
    th_empty_clicks.innerHTML = svgString + th_empty_clicks.innerHTML;
  }

  // Execute the function
  const emptyTableIDs = getEmptyTableIDs();
  hideEmptyTableWrappers(emptyTableIDs);
  correctEmptyTableWrappers(emptyTableIDs);
  hideEmptyChartContainers(emptyTableIDs);

  // After all operations are complete, hide the spinner and show the content
  var spinner = document.getElementById("analytics-spin");
  var msg = document.getElementById("analytic-loading-msg");
  var content = document.getElementById("analytics-content");
  if (spinner) {
    spinner.style.display = "none"; // Hide the spinner
    msg.style.display = "none";
  }
  if (content) {
    content.classList.remove("hidden"); // Remove the 'hidden' class to show the content
  }
};

// Function to check if the table is empty
function checkEmptyTable(tableID) {
  // Select the specific table by its unique class. Replace wpDataTableID with your actual unique identifier
  var table = document.querySelector(".wpDataTableID-" + tableID);

  // Check if the "dataTables_empty" element exists within this table
  return table && table.querySelector(".dataTables_empty");
}

function getUniqueTableIDsByCheckingEmpty() {
  // Get all elements with the class 'table-is-empty'
  var elements = document.querySelectorAll(".table-is-empty");

  // Set to store unique IDs
  var uniqueIds = new Set();

  // Loop through each element
  elements.forEach(function (element) {
    // Get the 'data-wptable' attribute value
    var ids = element.getAttribute("data-wptable");

    // Split the IDs by comma and loop through each ID
    ids.split(",").forEach(function (id) {
      // Trim the ID to remove any whitespace and add it to the set
      uniqueIds.add(id.trim());
    });
  });

  // Convert the set back to an array
  return Array.from(uniqueIds);
}
function getEmptyTableIDs() {
  // Get all unique table IDs
  var uniqueTableIDs = getUniqueTableIDsByCheckingEmpty();

  // Array to store the IDs of empty tables
  var emptyTableIDs = [];

  // Loop through each ID
  uniqueTableIDs.forEach(function (tableID) {
    // Check if the current table is empty
    if (checkEmptyTable(tableID)) {
      // If the table is empty, add its ID to the array
      emptyTableIDs.push(tableID);
    }
  });

  // Return the array of empty table IDs
  return emptyTableIDs;
}

function hideEmptyTableWrappers(emptyTableIDs) {
  // Loop through each ID
  emptyTableIDs.forEach(function (tableID) {
    // Find the table's wrapper
    var table = document.querySelector(".wpDataTableID-" + tableID);
    var wrapper = table.closest(".table-wrapper");

    // Set the wrapper's display to 'none' to hide it
    if (wrapper) {
      wrapper.style.display = "none";
    }
  });
}

function correctEmptyTableWrappers(emptyTableIDs) {
  // Loop through each ID
  emptyTableIDs.forEach(function (tableID) {
    // Find the table's wrapper with the 'table-is-empty' class
    var selector =
      ".table-wrapper.table-is-empty[data-wptable='" + tableID + "']";
    var emptyWrappers = document.querySelectorAll(selector);

    // Loop through each wrapper and remove the 'table-is-empty' class
    emptyWrappers.forEach(function (wrapper) {
      wrapper.classList.remove("table-is-empty");
    });
  });
}

function hideEmptyChartContainers(emptyTableIDs) {
  emptyTableIDs.forEach(function (tableID) {
    var selector =
      ".table-wrapper[data-wptable='" + tableID + "'][data-wpchart]";

    var emptyWrappers = document.querySelectorAll(selector);

    emptyWrappers.forEach(function (chartWrapper) {
      var chartID = chartWrapper.getAttribute("data-wpchart");

      if (chartID) {
        var chartContainer = document.querySelector(
          "#chartJSContainer_" + chartID
        );

        if (chartContainer) {
          var parentWrapper = chartContainer.closest(".chart-wraper");

          if (parentWrapper) {
            parentWrapper.style.display = "none";
          }
        }
      }
    });
  });
}
