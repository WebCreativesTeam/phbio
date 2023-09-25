export default ({ initLinks = [] }) => ({
  maxLinks: 9999,
  linkError: "",
  componentId: Math.random().toString(36).substring(2, 15),

  showAddNewLinkForm: false,
  inputAddLinkValue: "",
  inputEditLinkValue: "",
  inputEditTitleValue: "",
  newLink: {
    title: "",
    text: "",
  },
  links: initLinks.map((link) => ({
    id: link.id || Date.now(),
    title: link.title || "",
    text: link.text || "",
  })),
  draggingLinkId: null,
  draggedOverLinkId: null,
  isInputFocused: false,

  linksJson() {
    let json = JSON.stringify(this.links);
    return encodeURIComponent(json);
  },
  showAddNewLink() {
    this.showAddNewLinkForm = !this.showAddNewLinkForm;
  },
  linkIsHidden(id) {
    const link = this.links.find((link) => link.id === id);
    if (link) {
      return link.isHidden;
    }
  },
  // Drag and Drop Actions
  handleDragStart(event, id) {
    // Set the data for transfer as "componentId|linkId"
    event.dataTransfer.setData("text/plain", `${this.componentId}|${id}`);
    this.draggingLinkId = id;
  },
  linkIsDragging(id) {
    const link = this.links.find((link) => link.id === id);
    if (link) {
      return link.isDragging;
    }
  },

  handleDrop(event, id) {
    event.preventDefault();

    // Retrieve the transferred data and split it to get the component ID and link ID
    const transferredData = event.dataTransfer.getData("text/plain");
    const [originComponentId, originLinkId] = transferredData.split("|");

    // If the component ID from the dragged item doesn't match the current component, exit the function
    if (originComponentId !== this.componentId) {
      console.log("Tried to drop item from a different component!");
      return;
    }

    this.draggedOverLinkId = id;

    if (originLinkId !== this.draggedOverLinkId) {
      const draggingLinkIndex = this.links.findIndex(
        (link) => link.id == originLinkId
      );
      const draggedOverLinkIndex = this.links.findIndex(
        (link) => link.id == this.draggedOverLinkId
      );

      // Swap the links
      [this.links[draggingLinkIndex], this.links[draggedOverLinkIndex]] = [
        this.links[draggedOverLinkIndex],
        this.links[draggingLinkIndex],
      ];
    }

    // Reset the dragged IDs
    this.draggingLinkId = null;
    this.draggedOverLinkId = null;
  },
  handleDragEnd(event, id) {
    this.links = this.links.map((link) => {
      if (link.id === id) {
        return { ...link, isDragging: false };
      }
      return link;
    });
  },
  handleDragOver(event) {
    event.preventDefault();
  },
  addLink() {
    // Check if title is not defined or is an empty string
    if (!this.newLink.title || this.newLink.title.trim() === "") {
      this.linkError = "Please select an icon";
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
      const newLink = {
        id: Date.now(),
        text: this.inputAddLinkValue,
        title: this.newLink.title,
        isEditing: false,
      };

      console.log(newLink, "newLink");
      this.links.push(newLink);
      console.log("Link added successfully: ", this.links);

      this.inputAddLinkValue = "";
      this.newLink.title = "";
      this.linkError = "";
      this.showAddNewLinkForm = false;
    } else {
      this.linkError = "Please enter a valid URL.";
    }
  },
  showEditLinkForm(id) {
    this.showAddNewLinkForm = false;
    this.links = this.links.map((item) => {
      if (item.id === id) {
        this.inputEditLinkValue = item.text;
        this.inputEditTitleValue = item.title;
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
        title: item.id === id ? this.inputEditTitleValue : item.title,
        isEditing: false,
      }));

      console.log("Link edited successfully: ", this.links);
      this.linkError = "";
    } else if (this.linkExists(this.inputEditLinkValue, id)) {
      this.linkError = "Link already exists.";
    } else {
      this.linkError = "Please enter a valid URL.";
    }
  },
  cancelEditLink() {
    this.links = this.links.map((item) => ({
      ...item,
      isEditing: false,
    }));
  },
  removeLink(id) {
    this.links = this.links.filter((item) => item.id !== id);
  },
  linkExists(link, excludingId = null) {
    return this.links.some(
      (item) => item.text === link && item.id !== excludingId
    );
  },
  validateURL(url) {
    const pattern = new RegExp(
      "^(https?:\\/\\/)?(([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|((\\d{1,3}\\.){3}\\d{1,3})(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*(\\?[;&a-z\\d%_.~+=-]*)?(\\#[-a-z\\d_]*)?$",
      "i"
    );
    return !!pattern.test(url);
  },
});
