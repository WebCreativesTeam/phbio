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

  // Execute the function
  logEmptyTables();
  hideEmptyTableWrappers();
  correctEmptyTableWrappers();
  hideEmptyChartContainers();
  correctEmptyChartWrappers();

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
function logEmptyTables() {
  // Get all unique table IDs
  var uniqueTableIDs = getUniqueTableIDsByCheckingEmpty();

  // Loop through each ID
  uniqueTableIDs.forEach(function (tableID) {
    // Check if the current table is empty
    if (checkEmptyTable(tableID)) {
      // If the table is empty, log its ID to the console
      console.log("Table with ID " + tableID + " is empty.");
    }
  });
}

function hideEmptyTableWrappers() {
  // Get all unique table IDs
  var uniqueTableIDs = getUniqueTableIDsByCheckingEmpty();

  // Loop through each ID
  uniqueTableIDs.forEach(function (tableID) {
    // Check if the current table is empty
    if (checkEmptyTable(tableID)) {
      // If the table is empty, find the table's wrapper
      var table = document.querySelector(".wpDataTableID-" + tableID);
      var wrapper = table.closest(".table-wrapper");

      // Set the wrapper's display to 'none' to hide it
      if (wrapper) {
        wrapper.style.display = "none";
      }
    }
  });
}

function correctEmptyTableWrappers() {
  // Get all unique table IDs
  var uniqueTableIDs = getUniqueTableIDsByCheckingEmpty();

  // Loop through each ID
  uniqueTableIDs.forEach(function (tableID) {
    // Check if the current table is empty
    if (checkEmptyTable(tableID)) {
      // If the table is empty, find the table's wrapper with the 'table-is-empty' class
      var selector =
        ".table-wrapper.table-is-empty[data-wptable='" + tableID + "']";
      var emptyWrappers = document.querySelectorAll(selector);

      // Loop through each wrapper and remove the 'table-is-empty' class
      emptyWrappers.forEach(function (wrapper) {
        wrapper.classList.remove("table-is-empty");
      });
    }
  });
}

function correctEmptyChartWrappers() {
  // Get all unique table IDs
  var uniqueTableIDs = getUniqueTableIDsByCheckingEmpty();

  // Loop through each ID
  uniqueTableIDs.forEach(function (tableID) {
    // Check if the current table is empty
    if (checkEmptyTable(tableID)) {
      // If the table is empty, find the table's wrapper with the 'table-is-empty' class and 'data-wpchart' attribute
      var selector =
        ".table-wrapper.table-is-empty[data-wptable='" +
        tableID +
        "'][data-wpchart]";
      var emptyWrappers = document.querySelectorAll(selector);

      // Loop through each wrapper
      emptyWrappers.forEach(function (wrapper) {
        // Remove the 'table-is-empty' class
        wrapper.classList.remove("table-is-empty");
      });
    }
  });
}

function hideEmptyChartContainers() {
  // Get all unique table IDs
  var uniqueTableIDs = getUniqueTableIDsByCheckingEmpty();

  // Loop through each ID
  uniqueTableIDs.forEach(function (tableID) {
    // Check if the current table is empty
    if (checkEmptyTable(tableID)) {
      // If the table is empty, find all wrappers with the 'table-is-empty' class and 'data-wpchart' attribute
      var selector =
        ".table-wrapper.table-is-empty[data-wptable='" +
        tableID +
        "'][data-wpchart]";
      var emptyWrappers = document.querySelectorAll(selector);

      // Loop through each wrapper
      emptyWrappers.forEach(function (wrapper) {
        // Get the chart ID from the 'data-wpchart' attribute
        var chartID = wrapper.getAttribute("data-wpchart");

        // If there's a chart ID, find the corresponding chart container
        if (chartID) {
          var chartContainer = document.querySelector(
            "#chartJSContainer_" + chartID
          );

          console.log(chartContainer, "chartContainer");
          // If the chart container is found, hide it
          if (chartContainer) {
            chartContainer.style.display = "none";
          }
        }
      });
    }
  });
}
