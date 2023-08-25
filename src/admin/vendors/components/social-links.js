export default ({ initLinks = [] }) => {
  return {
    maxLinks: 100, // An arbitrary number; you can adjust this as needed
    linkError: "",
    showAddNewLinkForm: false,
    inputAddLinkValue: "",
    inputEditLinkValue: "",
    newLink: {
      icon: "", // The icon for the social media
      url: "", // The URL of the social media profile/page
    },
    links: initLinks.map((link) => ({
      id: link.id || Date.now(),
      icon: link.icon || "",
      url: link.url || "",
    })),
    draggingLinkId: null,
    draggedOverLinkId: null,
    isAnyLinkBeingEdited() {
      return this.links.some((link) => link.isEditing);
    },
    // Utility Functions
    linkExists(url, excludingId = null) {
      return this.links.some(
        (item) => item.url === url && item.id !== excludingId
      );
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

    // Drag and Drop Actions
    handleDragStart(event, id) {
      event.dataTransfer.setData("text/plain", id);
      this.draggingLinkId = id;
    },

    handleDrop(event, id) {
      event.preventDefault();
      this.draggedOverLinkId = id;

      if (this.draggingLinkId !== this.draggedOverLinkId) {
        const draggingLinkIndex = this.links.findIndex(
          (link) => link.id == this.draggingLinkId
        );
        const draggedOverLinkIndex = this.links.findIndex(
          (link) => link.id == this.draggedOverLinkId
        );

        // Swap the links
        [this.links[draggingLinkIndex], this.links[draggedOverLinkIndex]] = [
          this.links[draggedOverLinkIndex],
          this.links[draggingLinkIndex],
        ];

        // Reset the dragged IDs
        this.draggingLinkId = null;
        this.draggedOverLinkId = null;
      }
    },

    handleDragOver(event) {
      event.preventDefault();
    },

    // Edit and Delete Actions
    showEditLinkForm(id) {
      const link = this.links.find((item) => item.id === id);
      this.inputEditLinkValue = link.url;
      this.showAddNewLinkForm = false;

      this.links = this.links.map((item) => ({
        ...item,
        isEditing: item.id === id,
      }));
    },

    editLink(id) {
      if (
        this.inputEditLinkValue.length &&
        this.validateURL(this.inputEditLinkValue) &&
        !this.linkExists(this.inputEditLinkValue, id)
      ) {
        this.links = this.links.map((item) => {
          if (item.id === id) {
            return {
              ...item,
              url: this.inputEditLinkValue,
              isEditing: false,
            };
          }
          return item;
        });
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

    // Add New Link
    addLink() {
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
          icon: this.newLink.icon,
          url: this.inputAddLinkValue,
        });

        this.inputAddLinkValue = "";
        this.newLink.icon = "";
        this.linkError = "";
        this.showAddNewLinkForm = false;
      } else {
        this.linkError = "Please enter a valid URL.";
      }
    },
  };
};
