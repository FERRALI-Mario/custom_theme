const fs = require("fs");
const path = require("path");

const activeBlocksPath = path.resolve(__dirname, "active-blocks.json");
let activeBlocks = [];

try {
  if (fs.existsSync(activeBlocksPath)) {
    activeBlocks = JSON.parse(fs.readFileSync(activeBlocksPath, "utf8"));
  } else {
    console.warn(
      "⚠️  active-blocks.json non trouvé. Tailwind va scanner tous les blocs."
    );
  }
} catch (e) {
  console.error("Erreur lecture active-blocks.json", e);
}

const blockPaths =
  activeBlocks.length > 0
    ? activeBlocks.map((slug) => `./acf-blocks/${slug}/**/*.twig`)
    : ["./acf-blocks/**/*.twig"];

module.exports = {
  content: [
    "./*.php",
    "./views/**/*.twig",
    "./assets/js/**/*.js",
    ...blockPaths,
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
  safelist: [
    "p",
    "pl",
    "pr",
    "py",
    "pt",
    "pb",
    "px",
    "m",
    "ml",
    "mr",
    "mx",
    "mt",
    "mb",
    "my",
    "sm",
    "md",
    "lg",
    "xl",
    "w-full",
    "max-w",
    "max-h",
    "fixed",
    "absolute",
    "relative",
    "inset-0",
    "bg-cover",
    "bg-center",
    "bg-fixed",
    "overflow-hidden",
    "min-h-screen",
    "flex",
    "flex-wrap",
    "flex-col",
    "flex-row",
    "items-center",
    "justify-center",
    "italic",
    "text-left",
    "text-center",
    "text-right",
    "text-white",
    "text-black",
    "text-gray",
    "text-yellow",
    "z-0",
    "z-10",
    "z-[-10]",
    "bg-black/50",
    "bg-white",
    "bg-gray",
    "py-8",
    "p-10",
    "px-4",
    "pt-12",
    "pb-12",
    "will-change-transform",
    "duration-500",
    "animate-carousel",
    "transition-transform",
    "transition-colors",
    "rotate",
    "rotate-180",
    "hover",
    "shadow",
    "rounded",
    "border",
    "opacity-100",
    "space-y-2",
    "space-y-4",
    "text-sm",
    "font-medium",
    "block",
    "text-red-500",
    "flex",
    "w-full",
    "rounded-lg",
    "border",
    "bg-white/5",
    "border-black/30",
    "px-4",
    "py-3",
    "h-12",
    "text-center",
    "hidden",
    "font-semibold",
    "mr-0",
    "py-2",
    "rounded-full",
    "ml-4",
    "mr-4",
    "mx-2",
    "page-numbers",
    "form-container",
    "wpforms-form",
    "wpforms-field-container",
    "wpforms-submit-container",
    "wpforms-error",
    "wpforms-success",
    "wpforms-confirmation-container",
    "wpforms-hidden",
    "text-green-500",
  ],
  plugins: [],
};
