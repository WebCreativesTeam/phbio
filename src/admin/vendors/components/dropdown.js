export default ({ initIcons = [], selected = "" }) => ({
  isOpen: false,
  search: "",
  selected,
  options: initIcons,
  selectOption(option) {
    this.selected = option;
    this.isOpen = false;
  },
  filteredOptions() {
    if (!this.search) return this.options;
    const regex = new RegExp(this.search, "i");
    return this.options.filter((option) => option.match(regex));
  },
});
