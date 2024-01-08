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
      '<a target="_blank" href="/upgrade"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512" class="ctr-table-icon" fill="currentColor"><path d="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z"/></svg></a>';
    // Append the SVG string to the TH innerHTML
    th_empty_clicks.innerHTML = svgString + th_empty_clicks.innerHTML;
  }

  // Execute the function
  const param = getQueryParam("page");
  console.log(param);
  if (param === "edit-presskit") {
    reloadPageIfSuccess();
    initializeAcfDrags();
    applyAcfDrags();
  }
  executeStart();
  initializeIframeLoading(".iframe-container");
};

function getQueryParam(param) {
  // Create a URLSearchParams object from the current URL
  const params = new URLSearchParams(window.location.search);

  // Get the value of the specified query parameter
  return params.get(param);
}
function executeStart() {
  var emptyTableIDs = [];
  setTimeout(function () {
    emptyTableIDs = getEmptyTableIDs();
    console.log(emptyTableIDs, "emptyTableIDs");
    hideEmptyTableWrappers(emptyTableIDs);
    correctEmptyTableWrappers(emptyTableIDs);
    hideEmptyChartContainers(emptyTableIDs);
  }, 3000);
  setTimeout(function () {
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
  }, 3500);
}

// Function to check if the table is empty
function checkEmptyTable(tableID) {
  // Select the specific table by its unique class. Replace wpDataTableID with your actual unique identifier
  var table = document.querySelector(".wpDataTableID-" + tableID);

  // Check if the "dataTables_empty" element exists within this table
  var isEmpty = table && table.querySelector(".dataTables_empty");

  // Additionally, check if the tbody tag exists and if it has no child nodes
  var tbody = table && table.querySelector("tbody");
  if (tbody && tbody.children.length === 0) {
    isEmpty = true;
  }

  return isEmpty;
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

  console.log(uniqueTableIDs, "uniqueTableIDs");
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

// For Preview Mode
function initializeIframeLoading(selector) {
  var iframe = document.querySelector(selector + " iframe");
  var loader = document.querySelector(selector + " .loaad");

  if (!iframe || !loader) {
    console.error("Iframe or loader not found!");
    return;
  }

  var reloadInterval;

  iframe.onload = function () {
    var iframeDocument =
      iframe.contentDocument || iframe.contentWindow.document;
    var html = iframeDocument.documentElement;

    html.style.overflow = "hidden";

    var header = iframeDocument.getElementById("site-header");

    if (header) {
      header.style.display = "none";
    }
    iframe.style.display = "block";
    loader.style.display = "none";
    iframe.style.height =
      iframe.contentWindow.document.body.scrollHeight + 40 + "px";
    // Clear the interval once the iframe is loaded
    clearInterval(reloadInterval);
  };

  // Reload iframe every 20 seconds
  reloadInterval = setInterval(function () {
    iframe.src = iframe.src;
  }, 20000);
}

function initializeAcfDrags() {
  // Adding Drag Handles
  var draggables = document.querySelectorAll(
    "#AcfFormsArea .fields-block-item:not(.fields-block-condition)"
  );

  // Iterate over each item and set the draggable attribute to true
  draggables.forEach(function (item) {
    item.setAttribute("draggable", "true");
  });

  // Select all .acf-input elements within #AcfFormsArea
  var acfInputs = document.querySelectorAll(
    "#AcfFormsArea .fields-block-item:not(.fields-block-condition) .acf-input"
  );
  var acfLabels = document.querySelectorAll(
    "#AcfFormsArea .fields-block-item:not(.fields-block-condition) .acf-label"
  );

  // Define a common style object for .acf-input
  var inputStyle = {
    display: "flex",
    flexDirection: "row",
    alignItems: "flex-start",
    gap: "1rem",
  };

  var wrapStyle = {
    width: "100%",
  };

  var labelStyle = {
    paddingLeft: "3rem",
  };
  // Loop through each .acf-input element
  acfInputs.forEach(function (acfInput) {
    // Check if the handle already exists
    if (!acfInput.querySelector(".drag-handle")) {
      // Create the handle div
      console.log("Creating handle");
      var handleDiv = document.createElement("div");
      handleDiv.className = "drag-handle";
      handleDiv.textContent = "☰";

      // Insert the handle at the beginning of the .acf-input element
      acfInput.insertBefore(handleDiv, acfInput.firstChild);
    }
    // Apply styles to acfInput
    Object.assign(acfInput.style, inputStyle);

    // Apply styles to .acf-input-wrap inside acfInput
    var acfInputWraps = acfInput.querySelectorAll(".acf-input-wrap");
    acfInputWraps.forEach(function (wrap) {
      Object.assign(wrap.style, wrapStyle);
    });

    acfLabels.forEach(function (wrap) {
      Object.assign(wrap.style, labelStyle);
    });
  });
}

function applyAcfDrags() {
  // Select all forms on the page. Replace this with the actual selector for your forms.
  const forms = document.querySelectorAll("form.acf-form");

  forms.forEach((form) => {
    const formId = form.getAttribute("id");

    const FieldBlocks = Array.from(form.querySelectorAll(".fields-block"));

    const CreateFieldOrder = () => {
      const FieldOrder = {};
      FieldBlocks.forEach((fieldBlock) => {
        const dataName = fieldBlock.attributes["data-name"].value;
        const fieldBox = fieldBlock.lastElementChild.lastElementChild;
        // Filter out elements with 'acf-hidden' class for condtional fields to exclude if condition does not match
        const fieldBlockInputs = Array.from(
          fieldBox.getElementsByClassName("acf-field")
        ).filter(
          (input) =>
            !input.classList.contains("acf-hidden") &&
            !input.classList.contains("fields-block-condition")
        );

        const FieldNames = fieldBlockInputs.map(
          (input) => input.attributes["data-name"].value
        );
        FieldOrder[dataName] = FieldNames;
      });
      return FieldOrder;
    };

    const existingOrder = localStorage.getItem("fieldOrder");
    const fieldOrder = existingOrder
      ? JSON.parse(existingOrder)[formId] || CreateFieldOrder()
      : CreateFieldOrder();

    const FieldOrderInput = form.querySelector(".fields-blocks-order input");
    FieldOrderInput.value = JSON.stringify(fieldOrder, null, 4);

    let FieldBlockMap = {};

    const RearrangeFields = (e, dataName) => {
      e.preventDefault();
      const draggingItem = FieldBlockMap[dataName].querySelector(".dragging");
      let siblings = [
        ...FieldBlockMap[dataName].querySelectorAll(
          ".acf-field:not(.dragging)"
        ),
      ];

      let nonDraggablePositions = [];
      siblings.forEach((sibling, index) => {
        if (sibling.classList.contains("fields-block-condition")) {
          nonDraggablePositions.push(index);
        }
      });

      let nextSiblingIndex = siblings.findIndex((sibling) => {
        const { top, height } = sibling.getBoundingClientRect();
        return e.clientY <= top + height / 2;
      });

      // Prevent placing the draggable item before non-draggable items
      if (nonDraggablePositions.includes(nextSiblingIndex)) {
        return;
      }

      let nextSibling = siblings[nextSiblingIndex];

      FieldBlockMap[dataName].insertBefore(draggingItem, nextSibling);

      // Update field order, ensuring non-draggable items remain in place
      fieldOrder[dataName] = siblings.map(
        (sibling) => sibling.attributes["data-name"].value
      );

      if (!nextSibling) {
        fieldOrder[dataName] = fieldOrder[dataName].filter(
          (field) => field !== draggingItem.attributes["data-name"].value
        );
        fieldOrder[dataName].push(draggingItem.attributes["data-name"].value);
      } else {
        fieldOrder[dataName] = fieldOrder[dataName].reduce((list, field) => {
          if (field === nextSibling.attributes["data-name"].value) {
            list.push(draggingItem.attributes["data-name"].value);
          }
          if (field !== draggingItem.attributes["data-name"].value) {
            list.push(field);
          }
          return list;
        }, []);
      }

      let data = JSON.parse(localStorage.getItem("fieldOrder")) || {};
      data[formId] = fieldOrder;
      localStorage.setItem("fieldOrder", JSON.stringify(data));
      FieldOrderInput.value = JSON.stringify(fieldOrder, null, 4);
    };

    FieldBlocks.forEach((fieldBlock) => {
      const dataName = fieldBlock.attributes["data-name"].value;
      const fieldBox = fieldBlock.lastElementChild.lastElementChild;
      FieldBlockMap[dataName] = fieldBox;

      const fieldBlockInputs = Array.from(
        fieldBox.getElementsByClassName("acf-field")
      );
      const TempFieldMap = {};

      fieldBlockInputs.forEach((fieldBlockInput) => {
        if (!fieldBlockInput.classList.contains("acf-hidden")) {
          const fieldName = fieldBlockInput.attributes["data-name"].value;
          TempFieldMap[fieldName] = fieldBlockInput;
          fieldBox.removeChild(fieldBlockInput);
        }
      });

      fieldOrder[dataName].forEach((fieldName) => {
        fieldBox.appendChild(TempFieldMap[fieldName]);
        const InputBox = TempFieldMap[fieldName];

        if (!InputBox.classList.contains("fields-block-condition")) {
          InputBox.addEventListener("dragstart", (e) => {
            const handle = e.target.querySelector(".drag-handle"); // Replace .drag-handle with your actual handle selector
            const { left, right, top, bottom } = handle.getBoundingClientRect();
            if (
              !(
                left <= e.clientX &&
                e.clientX <= right &&
                top <= e.clientY &&
                e.clientY <= bottom
              )
            ) {
              e.preventDefault();
            } else {
              setTimeout(() => InputBox.classList.add("dragging"), 0);
            }
          });

          InputBox.addEventListener("dragend", () => {
            InputBox.classList.remove("dragging");
          });
        }
      });

      fieldBox.addEventListener("dragover", (e) =>
        RearrangeFields(e, dataName)
      );
      fieldBox.addEventListener("dragenter", (e) => e.preventDefault());
    });
  });
}

function reloadPageIfSuccess() {
  // Check for the presence of an element with the class 'af-success'
  var successElement = document.querySelector(".af-success");

  // If the element is found, reload the page
  if (successElement) {
    console.log("Success element found. Reloading page...");
    window.location.reload();
  } else {
    console.log("Success element not found. No reload.");
  }
}
