export default ({ selected = "", allowMultiple = false }) => ({
  isOpen: false,
  allowMultiple,
  selected: selected.split(","),
  options: {
    en: "English",
    it: "Italian",
    es: "Spanish",
    de: "German",
    fr: "French",
    pt: "Portuguese",
  },
  canOpenDropdown() {
    return (
      (this.allowMultiple && this.selected.length < 2) ||
      this.selected.length === 0
    );
  },
  selectOption(option) {
    if (this.allowMultiple) {
      if (!this.selected.includes(option) && this.selected.length < 2) {
        this.selected.push(option);
      } else {
        this.selected = this.selected.filter((sel) => sel !== option);
      }
    } else {
      this.selected = [option];
    }
    this.isOpen = false;
  },
  removeOption(option) {
    this.selected = this.selected.filter((sel) => sel !== option);
  },
  isSelected(option) {
    return this.selected.includes(option);
  },
  selectedAsString() {
    return this.selected.join(",");
  },
});
