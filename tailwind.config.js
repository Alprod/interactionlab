/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
      'templates/**/*.html.twig',
      'assets/js/**/*.js'
  ],
  theme: {
    extend: {},
  },
  plugins: [
      require('@tailwindcss/forms'),
      require('@tailwindcss/aspect-ratio'),
      require('@tailwindcss/typography')
  ],
}
