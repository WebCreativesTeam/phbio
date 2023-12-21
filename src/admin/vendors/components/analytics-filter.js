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

  // Function to check if the current page has one of the specific query parameters
  disableLocalStorage() {
    const urlSearchParams = new URLSearchParams(window.location.search);
    const pageParam = urlSearchParams.get("page");
    return (
      pageParam === "presskit-analytics" || pageParam === "linkin-bio-analytics"
    );
  },

  setDateRange(range, submitForm = false) {
    // Check if the current range is 'Custom' and the new range is not 'Custom'
    if (
      this.selectedRange === "custom" &&
      range !== "custom" &&
      this.endToShow !== ""
    ) {
      return; // Exit the function without changing the range or doing anything else
    }
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
        // Check if we have the dates in local storage
        const storedDateFrom = localStorage.getItem("customDateFrom");
        const storedDateTo = localStorage.getItem("customDateTo");

        if (storedDateFrom && storedDateTo) {
          // We have dates in local storage, use them
          this.dateFrom = new Date(storedDateFrom);
          this.dateTo = new Date(storedDateTo);
        } else {
          this.dateFrom = new Date(
            today.getFullYear(),
            today.getMonth(),
            today.getDate() - 6
          );
          this.dateTo = today;
        }
        break;
    }

    this.outputDateValues();

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
    if (performance.navigation.type === 1) {
      localStorage.removeItem("selectedRange");
      this.setDateRange("Today");
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

    const savedRange = localStorage.getItem("selectedRange");
    if (savedRange) {
      this.setDateRange(savedRange);
    } else if (!this.selectedRange || this.selectedRange === "") {
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
    if (!this.selecting) {
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
        this.closeDatepicker();
        // If the range is custom, save the dates in local storage
        if (this.selectedRange === "custom") {
          localStorage.setItem("customDateFrom", this.dateFrom.toISOString());
          localStorage.setItem("customDateTo", this.dateTo.toISOString());
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
});
