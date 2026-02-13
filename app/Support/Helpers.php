<?php

namespace App\Support;

class Helpers
{
    /**
     * Cache statique pour éviter de relire les fichiers SVG à chaque appel.
     */
    private static array $svgCache = [];

    /**
     * Dump variable in console (browser).
     * Sécurisé pour ne s'afficher que si le debug est actif (optionnel).
     * Utilise le hook 'wp_footer' pour éviter les erreurs "headers already sent".
     */
    public static function consoleLog(mixed $data): void
    {
        if (!defined('WP_DEBUG') || !WP_DEBUG) {
            return;
        }

        add_action('wp_footer', function () use ($data) {
            if (is_array($data) || is_object($data)) {
                $output = json_encode($data);
            } else {
                $output = '"' . addslashes((string)$data) . '"';
            }
            echo "<script>console.log('WP_LOG:', {$output});</script>";
        });

        add_action('admin_footer', function () use ($data) {
            if (is_array($data) || is_object($data)) {
                $output = json_encode($data);
            } else {
                $output = '"' . addslashes((string)$data) . '"';
            }
            echo "<script>console.log('WP_ADMIN_LOG:', {$output});</script>";
        });
    }

    /**
     * Get SVG icon by slug with memory caching.
     */
    public static function getSvgIcon(string $slug): string
    {
        // 1. Vérifier si déjà en mémoire
        if (isset(self::$svgCache[$slug])) {
            return self::$svgCache[$slug];
        }

        $path = get_template_directory() . "/assets/images/icons/{$slug}.svg";

        // 2. Lire et stocker en mémoire
        if (file_exists($path)) {
            $content = file_get_contents($path);
            self::$svgCache[$slug] = $content;
            return $content;
        }

        // 3. Stocker une chaîne vide pour éviter de re-vérifier un fichier inexistant
        self::$svgCache[$slug] = '';

        return '';
    }
}
