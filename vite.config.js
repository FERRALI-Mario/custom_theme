import { defineConfig } from 'vite'
import path from 'path'
import liveReload from 'vite-plugin-live-reload'

export default defineConfig({
  root: './',
  server: {
    // Assure la détection des changements sur tous les fichiers PHP/Twig
    // `usePolling` est nécessaire dans certains environnements comme Docker sur Windows/macOS
    // où le système de surveillance de fichiers natif ne fonctionne pas correctement.
    // Attention, cela peut consommer plus de CPU.
    watch: {
      usePolling: true,
    },
  },
  plugins: [
    liveReload([
      'views/**/*.twig',
      'acf-blocks/**/*.twig',
      '*.php',
    ]),
  ],
  build: {
    outDir: 'assets',
    emptyOutDir: false,
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'assets/js/main.js'),
        tailwind: path.resolve(__dirname, 'assets/css/tailwind.css'),
      },
      output: {
        entryFileNames: 'js/[name].js',
        assetFileNames: assetInfo => {
          if (assetInfo.name && assetInfo.name.endsWith('.css')) {
            return 'css/[name][extname]'
          }
          return '[name][extname]'
        },
      },
    },
  },
  css: {
    preprocessorOptions: {
      scss: {
        additionalData: '' // utile si tu as des variables globales SCSS
      }
    },
    postcss: {
      plugins: [
        require('tailwindcss'),
        require('autoprefixer'),
      ],
    },
  },
})
