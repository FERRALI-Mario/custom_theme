module.exports = {
  content: [
    './views/**/*.twig',
    './acf-blocks/**/*.twig',
    './app/**/*.php',
    './*.php'
  ],
  theme: {
    extend: {
      spacing: {
        '16xl': '4rem',
        '20xl': '5rem',
        '12lg': '3rem',
      },
    },
  },
  plugins: [],
}
