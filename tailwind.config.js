/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './src/**/*.{html,js,svelte,ts}',
    './src/app.html',
  ],
  // no theme.colors here—your @theme in app.css is your single source of truth
  theme: {
    extend: { /* any non-color tweaks—spacing, fonts, etc. */ },
  },
  plugins: [],
};
