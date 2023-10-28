/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./admin/class-plugin-name-admin.php",
    "./includes/class-plugin-name-builder.php",
    "./includes/extension/class-plugin-name-builder.php",
    "./includes/class-plugin-name-dashboard.php",
    "./includes/extension/class-plugin-name-dashboard.php",
    "./includes/class-plugin-name-settings.php",
    "./includes/class-plugin-name-utils.php",
    "./includes/extension/class-plugin-name-settings.php",
    "./includes/class-plugin-name-footer.php",
    "./includes/class-plugin-name-header.php",
    "./src/admin/vendors/wpdataTables.js",
  ],
  safelist: [
    "setting-page-wrap",
    "wpbody-content",
    "wpcontent",
    "ph_logo_hidden",
    "warning-message",
    "warning-icon",
    "borah_dynamic_grid",
  ],

  theme: {
    extend: {
      fontFamily: {
        poppins: ["Poppins", "sans-serif"],
      },
    },
  },
  corePlugins: {
    preflight: false,
  },
  plugins: [require("@tailwindcss/forms")],
};
