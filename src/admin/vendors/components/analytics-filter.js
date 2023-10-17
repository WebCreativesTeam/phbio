export const analyticsFilter = () => ({
  MONTH_NAMES: [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ],
  DAYS: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
  showDatepicker: false,
  selectedRange: "Today",
  dateFromYmd: "",
  dateToYmd: "",
  outputDateFromValue: "",
  outputDateToValue: "",
  dateFromValue: "",
  dateToValue: "",
  currentDate: null,
  dateFrom: null,
  dateTo: null,
  endToShow: "",
  selecting: false,
  month: "",
  year: "",
  no_of_days: [],
  blankdays: [],

  setDateRange(range, submitForm = false) {
    this.selectedRange = range;
    // Save the selected range in local storage
    localStorage.setItem("selectedRange", this.selectedRange);

    const today = new Date();
    switch (range) {
      case "Today":
        this.dateFrom = today;
        this.dateTo = today;
        break;
      case "7days":
        this.dateFrom = new Date(
          today.getFullYear(),
          today.getMonth(),
          today.getDate() - 6
        );
        this.dateTo = today;
        break;
      case "30days":
        this.dateFrom = new Date(
          today.getFullYear(),
          today.getMonth(),
          today.getDate() - 29
        );
        this.dateTo = today;
        break;
      case "90days":
        this.dateFrom = new Date(
          today.getFullYear(),
          today.getMonth(),
          today.getDate() - 89
        );
        this.dateTo = today;
        break;
      case "lifetime":
        this.dateFrom = new Date(1970, 0, 1);
        this.dateTo = today;
        break;
      case "custom":
        this.dateFrom = null;
        this.dateTo = null;
        break;
    }

    // Only call outputDateValues if the selected range is not 'custom'.
    if (this.selectedRange !== "custom") {
      this.outputDateValues();
    }

    if (submitForm) {
      setTimeout(function () {
        document.getElementById("analyticsFilterForm").submit();
      }, 500);
    }
  },
  convertFromYmd(dateYmd) {
    const year = Number(dateYmd.substr(0, 4));
    const month = Number(dateYmd.substr(5, 2)) - 1;
    const date = Number(dateYmd.substr(8, 2));

    return new Date(year, month, date);
  },

  convertToYmd(dateObject) {
    const year = dateObject.getFullYear();
    const month = dateObject.getMonth() + 1;
    const date = dateObject.getDate();

    return year + "-" + ("0" + month).slice(-2) + "-" + ("0" + date).slice(-2);
  },

  init() {
    this.onStart();
    if (performance.navigation.type === 1) {
      console.log("This page is reloaded");
      // Remove the selectedRange item from local storage
      localStorage.removeItem("selectedRange");
    }

    this.selecting =
      (this.endToShow === "to" && this.dateTo) ||
      (this.endToShow === "from" && this.dateFrom);
    if (!this.dateFrom) {
      if (this.dateFromYmd) {
        this.dateFrom = this.convertFromYmd(this.dateFromYmd);
      }
    }
    if (!this.dateTo) {
      if (this.dateToYmd) {
        this.dateTo = this.convertFromYmd(this.dateToYmd);
      }
    }
    if (!this.dateFrom) {
      this.dateFrom = this.dateTo;
    }
    if (!this.dateTo) {
      this.dateTo = this.dateFrom;
    }
    if (this.endToShow === "from" && this.dateFrom) {
      this.currentDate = this.dateFrom;
    } else if (this.endToShow === "to" && this.dateTo) {
      this.currentDate = this.dateTo;
    } else {
      this.currentDate = new Date();
    }
    currentMonth = this.currentDate.getMonth();
    currentYear = this.currentDate.getFullYear();
    if (this.month !== currentMonth || this.year !== currentYear) {
      this.month = currentMonth;
      this.year = currentYear;
      this.getNoOfDays();
    }
    // Retrieve the selected range from local storage
    const savedRange = localStorage.getItem("selectedRange");

    // If there's a saved range, set it
    if (savedRange) {
      this.setDateRange(savedRange);
    } else if (!this.selectedRange || this.selectedRange === "") {
      // If there's no saved range in local storage, default to 'Today'
      this.setDateRange("Today");
    }

    this.setDateValues();
  },

  isToday(date) {
    const today = new Date();
    const d = new Date(this.year, this.month, date);

    return today.toDateString() === d.toDateString();
  },

  isDateFrom(date) {
    const d = new Date(this.year, this.month, date);

    if (!this.dateFrom) {
      return false;
    }

    return d.getTime() === this.dateFrom.getTime();
  },

  isDateTo(date) {
    const d = new Date(this.year, this.month, date);

    if (!this.dateTo) {
      return false;
    }

    return d.getTime() === this.dateTo.getTime();
  },

  isInRange(date) {
    const d = new Date(this.year, this.month, date);

    return d > this.dateFrom && d < this.dateTo;
  },

  outputDateValues() {
    if (this.dateFrom) {
      this.outputDateFromValue = this.dateFrom.toDateString();
      this.dateFromYmd = this.convertToYmd(this.dateFrom);
    }
    if (this.dateTo) {
      this.outputDateToValue = this.dateTo.toDateString();
      this.dateToYmd = this.convertToYmd(this.dateTo);
    }
  },

  setDateValues() {
    if (this.dateFrom) {
      this.dateFromValue = this.dateFrom.toDateString();
    }
    if (this.dateTo) {
      this.dateToValue = this.dateTo.toDateString();
    }
  },

  getDateValue(date, temp) {
    // if we are in mouse over mode but have not started selecting a range, there is nothing more to do.
    if (temp && !this.selecting) {
      return;
    }
    let selectedDate = new Date(this.year, this.month, date);
    if (this.selectedRange === "custom" && !this.selecting) {
      return;
    }

    if (this.endToShow === "from") {
      this.dateFrom = selectedDate;
      if (!this.dateTo) {
        this.dateTo = selectedDate;
      } else if (selectedDate > this.dateTo) {
        this.endToShow = "to";
        this.dateFrom = this.dateTo;
        this.dateTo = selectedDate;
      }
    } else if (this.endToShow === "to") {
      this.dateTo = selectedDate;
      if (!this.dateFrom) {
        this.dateFrom = selectedDate;
      } else if (selectedDate < this.dateFrom) {
        this.endToShow = "from";
        this.dateTo = this.dateFrom;
        this.dateFrom = selectedDate;
      }
    }
    this.setDateValues();

    if (!temp) {
      if (this.selecting) {
        this.outputDateValues();
        if (this.selectedRange === "custom") {
          // Do something if needed when the custom range is selected
        } else {
          this.closeDatepicker();
        }
      }
      this.selecting = !this.selecting;
    }
  },

  getNoOfDays() {
    let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();

    // find where to start calendar day of week
    let dayOfWeek = new Date(this.year, this.month).getDay();
    let blankdaysArray = [];
    for (var i = 1; i <= dayOfWeek; i++) {
      blankdaysArray.push(i);
    }

    let daysArray = [];
    for (var i = 1; i <= daysInMonth; i++) {
      daysArray.push(i);
    }

    this.blankdays = blankdaysArray;
    this.no_of_days = daysArray;
  },

  closeDatepicker() {
    this.endToShow = "";
    this.showDatepicker = false;
  },

  onStart() {
    // Execute the function
    const emptyTableIDs = getEmptyTableIDs();
    hideEmptyTableWrappers(emptyTableIDs);
    correctEmptyTableWrappers(emptyTableIDs);
    hideEmptyChartContainers(emptyTableIDs);

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
  },
});
