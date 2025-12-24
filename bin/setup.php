<?php

// 1. Définition des chemins
$themeDir = dirname(__DIR__); // Remonte d'un cran (sort du dossier bin)
$configPath = $themeDir . '/config/blocks.php';
$jsonPath = $themeDir . '/active-blocks.json';

echo "------------------------------------------------\n";
echo "🔧  CONFIGURATION DES BLOCS (Mode Standalone)\n";
echo "------------------------------------------------\n";

// 2. Chargement de la config
if (!file_exists($configPath)) {
    die("❌ Erreur : Le fichier config/blocks.php est introuvable.\n");
}

$config = include $configPath;
$activeBlocks = $config['core'];

echo "📦  Les blocs CORE sont activés par défaut.\n";

// 3. Boucle sur les modules optionnels
if (isset($config['optional']) && is_array($config['optional'])) {
    foreach ($config['optional'] as $key => $pack) {
        echo "\n------------------------------------------------\n";
        echo "Module : " . $pack['label'] . "\n";
        echo "Blocs  : " . implode(', ', $pack['blocks']) . "\n";

        echo "👉 Voulez-vous activer ce module ? [y/N] ";

        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        $answer = trim($line);
        fclose($handle);

        if (strtolower($answer) === 'y') {
            $activeBlocks = array_merge($activeBlocks, $pack['blocks']);
            echo "✅ Module activé.\n";
        } else {
            echo "Skipped.\n";
        }
    }
}

// 4. Sauvegarde
$activeBlocks = array_unique($activeBlocks);
sort($activeBlocks);

$jsonContent = json_encode($activeBlocks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

if (file_put_contents($jsonPath, $jsonContent)) {
    echo "\n------------------------------------------------\n";
    echo "🎉 SUCCÈS ! Configuration enregistrée.\n";
    echo "📄 Fichier généré : active-blocks.json\n";
    echo "📊 Total blocs actifs : " . count($activeBlocks) . "\n";
    echo "------------------------------------------------\n";
    echo "🚀 Vous pouvez maintenant lancer 'npm run build' !\n";
} else {
    echo "❌ Erreur lors de l'écriture du fichier JSON.\n";
}
