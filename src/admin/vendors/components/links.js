export default ({ initLinks = [], initMax }) => ({
  isDebugMode: false,
  debugTime: new Date(), // initialize with the current time
  maxLinks: initMax,
  maxLinksError: "You have reached the maximum limit.",
  inputAddLinkValue: "",
  inputEditLinkValue: "",
  inputEditTitleValue: "", // Add this line
  linkError: "",
  showAddNewLinkForm: false,
  newLink: {
    title: "",
    text: "", // The URL
    isHidden: false, // Visibility state
    highlight: false, // Highlight state
    isEditing: false, // Edit state
    isScheduled: false, // Scheduling state
    start_time: null, // Start time for scheduling
    end_time: null, // End time for scheduling
  },
  links: initLinks.map((link) => ({
    id: link.id || Date.now(),
    title: link.title || "", // This is the added title
    text: link.text || "",
    isHidden: link.isHidden || false,
    highlight: link.highlight || false,
    start_time: link.start_time || null,
    end_time: link.end_time || null,
    isScheduled: link.isScheduled || false,
  })),

  draggingLinkId: null,
  draggedOverLinkId: null,
  isInputFocused: false,
  addLink() {
    console.log(this.links.length, this.maxLinks);
    // Check if the link limit is reached
    if (this.links.length >= this.maxLinks) {
      this.linkError = `You can only add up to ${this.maxLinks} links.`;
      return;
    }

    if (this.linkExists(this.inputAddLinkValue)) {
      this.linkError = "Link already exists.";
      return;
    }

    if (
      this.inputAddLinkValue.length &&
      this.validateURL(this.inputAddLinkValue)
    ) {
      this.links.push({
        id: Date.now(),
        text: this.inputAddLinkValue,
        title: this.newLink.title,
        isHidden: false, // Initially, we're setting the link as visible
        highlight: this.newLink.highlight,
        isEditing: false, // The link is not being edited right after adding
        isScheduled: this.newLink.isScheduled,
        start_time: this.newLink.start_time,
        end_time: this.newLink.end_time,
      });

      this.inputAddLinkValue = "";
      this.newLink.title = ""; // Reset the title
      this.linkError = "";
      this.showAddNewLinkForm = false; // Hide the form
      console.log(this.links);
    } else {
      this.linkError = "Please enter a valid URL.";
    }

    console.log("Updated links after adding:", this.links);
  },
  toggleHighlightLink(id) {
    let isCurrentLinkHighlighted = this.links.find(
      (link) => link.id === id
    ).highlight;

    this.links.forEach((link) => {
      if (link.id === id) {
        link.highlight = !isCurrentLinkHighlighted;
      } else {
        link.highlight = false;
      }
    });
    console.log("Updated links after toggling highlight:", this.links);
  },
  closeAllEditing() {
    this.links.forEach((link) => (link.isEditing = false));
  },

  showAddNewLink() {
    this.showAddNewLinkForm = true;
    this.closeAllEditing();
  },

  toggleHideLink(id) {
    const link = this.links.find((link) => link.id === id);
    if (link) {
      link.isHidden = !link.isHidden;
    }
    console.log("Updated links after toggling hidden state:", this.links);
  },
  linkExists(link, excludingId = null) {
    return this.links.some(
      (item) => item.text === link && item.id !== excludingId
    );
  },

  linksJson() {
    let json = JSON.stringify(this.links);
    return encodeURIComponent(json);
  },
  showEditLinkForm(id) {
    this.showAddNewLinkForm = false; // Hide the "Add New" form

    this.links = this.links.map((item) => {
      if (item.id === id) {
        this.inputEditLinkValue = item.text;
        this.inputEditTitleValue = item.title; // Update the editing title value
      }
      return {
        ...item,
        isEditing: item.id === id,
      };
    });
  },
  editLink(id) {
    if (
      this.inputEditLinkValue.length &&
      this.validateURL(this.inputEditLinkValue) &&
      !this.linkExists(this.inputEditLinkValue, id)
    ) {
      this.links = this.links.map((item) => ({
        ...item,
        text: item.id === id ? this.inputEditLinkValue : item.text,
        title: item.id === id ? this.inputEditTitleValue : item.title, // Update the title in the link list
        isEditing: false,
      }));
      this.linkError = "";
      console.log(this.links);
    } else if (this.linkExists(this.inputEditLinkValue, id)) {
      this.linkError = "Link already exists.";
    } else {
      this.linkError = "Please enter a valid URL.";
    }
    console.log("Updated links after editing:", this.links);
  },

  cancelEditLink() {
    this.links = this.links.map((item) => ({
      ...item,
      isEditing: false,
    }));
  },
  removeLink(id) {
    this.links = this.links.filter((item) => item.id !== id);
    console.log("Updated links after removing:", this.links);
  },
  validateURL(url) {
    const pattern = new RegExp(
      "^(https?:\\/\\/)?" +
        "((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|" +
        "((\\d{1,3}\\.){3}\\d{1,3}))" +
        "(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*" +
        "(\\?[;&a-z\\d%_.~+=-]*)?" +
        "(\\#[-a-z\\d_]*)?$",
      "i"
    );
    return !!pattern.test(url);
  },
  handleDragStart(event, id) {
    event.dataTransfer.setData("text/plain", id);
    this.draggingLinkId = id;
  },
  handleDrop(event, id) {
    event.preventDefault();
    this.draggedOverLinkId = id;
    if (this.draggingLinkId !== this.draggedOverLinkId) {
      let draggingLink = this.links.find(
        (link) => link.id == this.draggingLinkId
      );
      let draggedOverLink = this.links.find(
        (link) => link.id == this.draggedOverLinkId
      );
      let draggingLinkIndex = this.links.indexOf(draggingLink);
      let draggedOverLinkIndex = this.links.indexOf(draggedOverLink);

      // Swapping the links
      this.links[draggingLinkIndex] = draggedOverLink;
      this.links[draggedOverLinkIndex] = draggingLink;

      // Reset IDs
      this.draggingLinkId = null;
      this.draggedOverLinkId = null;
    }
    console.log("Updated links after drag and drop:", this.links);
  },
  handleDragOver(event) {
    event.preventDefault();
  },
  applyScheduling() {
    const currentTime = this.isDebugMode
      ? this.debugTime
      : new Date().toISOString(); // Use debug time if in debug mode

    this.links.forEach((link) => {
      if (link.isScheduled) {
        // Convert string times to Date objects for easier comparison
        const startTime = new Date(link.start_time);
        const endTime = new Date(link.end_time);

        // Check if current time is outside the scheduled window
        if (
          currentTime < startTime.toISOString() ||
          currentTime > endTime.toISOString()
        ) {
          link.isHidden = true; // Hide the link
        } else {
          link.isHidden = false; // Show the link
        }
      }
    });
  },
});
