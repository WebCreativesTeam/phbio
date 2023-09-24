export const dashboard = () => ({
  editMode: false,
  activeTab: "profile",
  showSettings: false,
  showTemplates: false,
  activeFilter: "all",
  saveState: function () {
    localStorage.setItem(
      "alpineState",
      JSON.stringify({
        editMode: this.editMode,
        activeTab: this.activeTab,
        showSettings: this.showSettings,
        showTemplates: this.showTemplates,
        activeFilter: this.activeFilter,
      })
    );
  },
});
