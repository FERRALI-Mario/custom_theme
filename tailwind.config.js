const path = require("path");
const fs = require("fs");

const activeBlocksPath = path.resolve(__dirname, "active-blocks.json");
let activeBlocks = [];

// Logique sécurisée pour lire les blocs actifs.
// Si le fichier n'existe pas ou est invalide, on scannera tous les blocs par défaut.
try {
  if (fs.existsSync(activeBlocksPath)) {
    const fileContent = fs.readFileSync(activeBlocksPath, "utf8");
    // S'assurer que le contenu n'est pas vide avant de parser
    if (fileContent) {
      activeBlocks = JSON.parse(fileContent);
    }
  }
} catch (e) {
  console.error(
    "⚠️ Erreur lors de la lecture de active-blocks.json. Tailwind scannera tous les blocs par défaut.",
    e
  );
  activeBlocks = []; // Réinitialise pour utiliser le fallback
}

// Si la liste des blocs est vide (soit par choix, soit par erreur), on scanne tout.
const blockContentPaths =
  activeBlocks && activeBlocks.length > 0
    ? activeBlocks.map((slug) => `./acf-blocks/${slug}/**/*.{php,twig,js}`)
    : ["./acf-blocks/**/*.{php,twig,js}"];

module.exports = {
  content: [
    "./*.php", // Fichiers PHP à la racine
    "./app/**/*.php", // Tous les fichiers PHP du dossier app/ (Providers, Core, etc.)
    "./views/**/*.twig", // Tous les templates Twig
    "./assets/js/**/*.js", // Scripts globaux
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
  // La safelist a été supprimée pour que PurgeCSS fonctionne de manière optimale.
  // Les classes utilisées dynamiquement doivent être présentes dans les fichiers scannés
  // ou ajoutées ici avec parcimonie si nécessaire.
  plugins: [],
};