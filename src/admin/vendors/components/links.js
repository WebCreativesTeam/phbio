export default (initLinks = []) => ({
  inputAddLinkValue: "",
  inputEditLinkValue: "",
  linkError: "",
  links: initLinks.map((link) => ({
    id: link.id || Date.now(),
    text: link.text || "",
    isHidden: link.isHidden || false,
  })),
  draggingLinkId: null,
  draggedOverLinkId: null,
  isInputFocused: false,
  addLink() {
    if (
      this.inputAddLinkValue.length &&
      this.validateURL(this.inputAddLinkValue) &&
      !this.linkExists(this.inputAddLinkValue)
    ) {
      this.links.push({
        id: Date.now(),
        text: this.inputAddLinkValue,
        isHidden: false,
      });
      this.inputAddLinkValue = "";
      this.linkError = "";
      console.log(this.links);
    } else if (this.linkExists(this.inputAddLinkValue)) {
      this.linkError = "Link already exists.";
    } else {
      this.linkError = "Please enter a valid URL.";
    }
    console.log("Updated links after adding:", this.links);
  },
  toggleHideLink(id) {
    const link = this.links.find((link) => link.id === id);
    if (link) {
      link.isHidden = !link.isHidden;
    }
    console.log("Updated links after toggling hidden state:", this.links);
  },
  linkExists(link) {
    return this.links.some((item) => item.text === link);
  },
  linksJson() {
    let json = JSON.stringify(this.links);
    return encodeURIComponent(json);
  },
  showEditLinkForm(id) {
    this.links = this.links.map((item) => {
      if (item.id === id) {
        this.inputEditLinkValue = item.text;
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
      !this.linkExists(this.inputEditLinkValue)
    ) {
      this.links = this.links.map((item) => ({
        ...item,
        text: item.id === id ? this.inputEditLinkValue : item.text,
        isEditing: false,
      }));
      this.linkError = "";
      console.log(this.links);
    } else if (this.linkExists(this.inputEditLinkValue)) {
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
});
