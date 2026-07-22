/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        fitbooking: {
          brand: '#E8C061',
          input: '#D9D9D9',
          dark: '#000000',
        }
      },
      boxShadow: {
        'inner-dark': 'inset 0 2px 4px rgba(0,0,0,0.25)',
      }
    },
  },
  plugins: [],
}
