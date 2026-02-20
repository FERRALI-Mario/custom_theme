const path = require("path");
const fs = require("fs");

const activeBlocksPath = path.resolve(__dirname, "active-blocks.json");
let activeBlocks = [];

try {
  if (fs.existsSync(activeBlocksPath)) {
    const fileContent = fs.readFileSync(activeBlocksPath, "utf8");
    if (fileContent) {
      activeBlocks = JSON.parse(fileContent);
    }
  }
} catch (e) {
  console.error(
    "⚠️ Erreur lors de la lecture de active-blocks.json. Tailwind scannera tous les blocs par défaut.",
    e
  );
  activeBlocks = [];
}

const blockContentPaths =
  activeBlocks && activeBlocks.length > 0
    ? activeBlocks.map((slug) => `./acf-blocks/${slug}/**/*.{php,twig,js}`)
    : ["./acf-blocks/**/*.{php,twig,js}"];

module.exports = {
  content: [
    "./*.php",
    "./app/**/*.php",
    "./views/**/*.twig",
    "./assets/js/**/*.js",
    ...blockContentPaths, // Blocs ACF (dynamique selon active-blocks.json)
  ],
  theme: {
    extend: {
      keyframes: {
        marquee: {
          "0%": { transform: "translateX(0%)" },
          "100%": { transform: "translateX(-50%)" },
        },
      },
      animation: {
        marquee: "marquee linear infinite",
      },
    },
  },
  plugins: [],
};