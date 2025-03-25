/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './public/**/*.{html,js,php}',
    './resources/**/*.{html,js,php}',
  ],
  theme: {
    extend: {
      colors: {
        'background': '#154a58',
        'card': '#2A5E47',
        'accent1': '#C2A831',
        'accent2': '#C5260C',
        'accent-light': '#FFE399',
        'text': '#f8fafc'
      },
    },
  },
  plugins: [],
}