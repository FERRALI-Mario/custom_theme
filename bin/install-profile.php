#!/usr/bin/env php
<?php
/**
 * Fallback CLI hors-WordPress.
 * Usage:
 *   echo "ecommerce" | php bin/install-profile.php
 *   # ou: php bin/install-profile.php ecommerce
 *
 * - Lit le profil demandé.
 * - Charge config/presets.php.
 * - Écrit:
 *   - .active-blocks.json (pour Tailwind)
 *   - config/active-preset.json (lu par le thème si aucune option WP)
 * - Lance "npm run build" si package.json est présent.
 */

$root = dirname(__DIR__);
$themeDir = $root; // script placé dans theme/bin

$argvChoice = $argv[1] ?? null;
if (!$argvChoice) {
    $stdin = stream_get_contents(STDIN);
    $argvChoice = trim($stdin);
    if ($argvChoice === '') {
        fwrite(STDERR, "Profil requis (vitrine|ecommerce|blog).\n");
        exit(1);
    }
}

$presetsFile = $themeDir . '/config/presets.php';
if (!file_exists($presetsFile)) {
    fwrite(STDERR, "Fichier manquant: config/presets.php\n");
    exit(1);
}

$presets = include $presetsFile;
if (!isset($presets[$argvChoice])) {
    fwrite(STDERR, "Profil inconnu: {$argvChoice}\n");
    exit(1);
}

$preset = $presets[$argvChoice];

// Écrit .active-blocks.json (purge Tailwind)
file_put_contents($themeDir . '/.active-blocks.json', json_encode($preset['blocks'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

// Écrit config/active-preset.json (lu par le thème si options WP absentes)
file_put_contents($themeDir . '/config/active-preset.json', json_encode([
    'preset' => $argvChoice,
    'packs'  => $preset['packs'],
    'blocks' => $preset['blocks'],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

// Build front si package.json existe
$pkg = $themeDir . '/package.json';
if (file_exists($pkg)) {
    $cmd = 'npm run build';
    // Utilise pnpm / yarn si tu préfères (détecte auto si besoin)
    passthru($cmd, $status);
    if ($status !== 0) {
        fwrite(STDERR, "Attention: échec de build front (code {$status}).\n");
    }
}

fwrite(STDOUT, "Profil '{$argvChoice}' appliqué (fallback).\n");
exit(0);
