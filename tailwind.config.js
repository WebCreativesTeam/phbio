/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./admin/class-plugin-name-admin.php'],
  theme: {
    extend: {},
  },
  corePlugins: {
    preflight: false,
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}

