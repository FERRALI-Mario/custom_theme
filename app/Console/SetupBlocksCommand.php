<?php

namespace App\Console;

use WP_CLI;
use WP_CLI_Command;

class SetupBlocksCommand extends WP_CLI_Command
{
    /**
     * Configure les blocs actifs du thème.
     *
     * ## EXEMPLES
     *
     * wp theme setup:blocks
     *
     * @when after_wp_load
     */
    public function __invoke($args, $assoc_args)
    {
        $configPath = get_template_directory() . '/config/blocks.php';
        $jsonPath = get_template_directory() . '/active-blocks.json';

        if (!file_exists($configPath)) {
            WP_CLI::error("Le fichier de configuration config/blocks.php est introuvable.");
        }

        $config = include $configPath;
        $activeBlocks = $config['core'];

        WP_CLI::line("📦  Installation des blocs CORE (Contenu, Contact, Mise en avant)...");
        // On ajoute les blocs Core automatiquement

        // --- Mode Interactif ---
        WP_CLI::line("\n🔧  Configuration des modules optionnels :");

        foreach ($config['optional'] as $key => $pack) {
            WP_CLI::line("------------------------------------------------");
            WP_CLI::line("Module : " . $pack['label']);
            $blocksList = implode(', ', $pack['blocks']);
            WP_CLI::line("Blocs inclus : " . WP_CLI::colorize("%w$blocksList%n"));

            // Question à l'utilisateur
            fwrite(STDOUT, "Voulez-vous activer ce module ? [y/n] ");
            $answer = trim(fgets(STDIN));

            if (strtolower($answer) === 'y') {
                $activeBlocks = array_merge($activeBlocks, $pack['blocks']);
                WP_CLI::success("✅ Module activé.");
            } else {
                WP_CLI::log("❌ Module ignoré.");
            }
        }

        // --- Sauvegarde ---
        $activeBlocks = array_unique($activeBlocks);
        sort($activeBlocks);

        if (file_put_contents($jsonPath, json_encode($activeBlocks, JSON_PRETTY_PRINT))) {
            WP_CLI::success("\n🎉 Configuration enregistrée ! " . count($activeBlocks) . " blocs actifs.");
            WP_CLI::line("Fichier généré : " . $jsonPath);

            // Lancer la compilation CSS si possible
            WP_CLI::line("\n🎨 Mise à jour des assets (Tailwind)...");
            $themePath = get_template_directory();
            // On tente de lancer le build npm en tâche de fond ou direct
            // Attention : exec peut être bloqué sur certains serveurs
            // exec("cd $themePath && npm run build"); 
            WP_CLI::warning("N'oubliez pas de lancer 'npm run build' pour purger le CSS inutilisé !");
        } else {
            WP_CLI::error("Impossible d'écrire le fichier active-blocks.json");
        }
    }
}
