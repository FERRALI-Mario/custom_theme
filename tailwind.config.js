module.exports = {
  content: [
    './*.php',
    './functions.php',
    './app/**/*.php',
    './acf-blocks/**/*.php',
    './acf-blocks/**/*.twig',
    './views/**/*.twig',
  ],
  theme: {
    extend: {
      spacing: {
        '8': '2rem',
        '4': '1rem',  
        '16xl': '4rem',
        '20xl': '5rem',
        '12lg': '3rem',
      },
    },
  },
  plugins: [],
    safelist: [
    'text-left',
    'text-center',
    'text-right',
    'bg-black',
    'bg-white',
    'bg-gray-100',
    'py-8',
    'px-4',
    'pt-12',
    'pb-12',
  ]
}
