export const dashboard = () => ({
  editMode: false,
  activeLang: false,
  activeTab: "profile",
  showSettings: false,
  showTemplates: false,
  activeFilter: "all",
  saveState: function () {
    localStorage.setItem(
      "alpineState",
      JSON.stringify({
        editMode: this.editMode,
        activeLang: this.activeLang,
        activeTab: this.activeTab,
        showSettings: this.showSettings,
        showTemplates: this.showTemplates,
        activeFilter: this.activeFilter,
      })
    );
  },
});
