/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./admin/class-plugin-name-admin.php",
    "./includes/class-plugin-name-builder.php",
    "./includes/class-plugin-name-dashboard.php",
    "./includes/class-plugin-name-settings.php",
  ],
  safelist: [
    "setting-page-wrap",
    "wpbody-content",
    "wpcontent",
    "ph_logo_hidden",
    "warning-message",
    "warning-icon",
  ],

  theme: {
    extend: {},
  },
  corePlugins: {
    preflight: false,
  },
  plugins: [require("@tailwindcss/forms")],
};
