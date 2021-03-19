const { colors } = require('tailwindcss/defaultTheme')
module.exports = {
    purge: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
  darkMode: 'class', // or 'media' or 'class'
  theme: {
      colors: {
          black: colors.black,
          white: colors.white,
          gray: colors.gray,
          red: colors.red,
          yellow: colors.yellow,
          green: colors.green,
          blue: colors.blue,
          indigo: colors.indigo,
          purple: colors.purple,
          teal: colors.teal,
          orange: colors.orange,
          pink: colors.pink
      },
    extend: {},
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
