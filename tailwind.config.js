/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './public/**/*.{html,js,php}',
    './resources/**/*.{html,js,php}',
  ],
  theme: {
    extend: {
      colors: {
        'primary': '#154a58',
        'secondary': '#5c9b7c',
        'tertiary': '#ca240a',
        'background': '#ffe399',
        'background-dark': '#c2a831',
        'text': '#f8fafc'
      },
    },
  },
  plugins: [],
}