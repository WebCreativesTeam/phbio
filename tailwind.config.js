/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./admin/class-plugin-name-admin.php",
    "./includes/class-plugin-name-builder.php",
    "./includes/class-plugin-name-dashboard.php",
    "./includes/class-plugin-name-settings.php",
  ],
  safelist: ["wpcontent", "ph_logo_hidden"],

  theme: {
    extend: {},
  },
  corePlugins: {
    preflight: false,
  },
  plugins: [require("@tailwindcss/forms")],
};
