/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./admin/class-plugin-name-admin.php",
    "./includes/class-plugin-name-builder.php",
  ],
  safelist: ["wpcontent"],

  theme: {
    extend: {},
  },
  corePlugins: {
    preflight: false,
  },
  plugins: [require("@tailwindcss/forms")],
};
