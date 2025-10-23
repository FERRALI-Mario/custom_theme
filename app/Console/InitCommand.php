<?php

namespace App\Console;

class InitCommand
{
    public static function register(): void
    {
        // Pas de référence statique à \WP_CLI ici.
        if (!\defined('WP_CLI') || !\class_exists('\\WP_CLI')) {
            return;
        }
        \call_user_func(['\\WP_CLI', 'add_command'], 'yourtheme init', [static::class, 'handle']);
    }

    public static function handle(array $args = [], array $assoc_args = []): void
    {
        $themeDir = \trailingslashit(\get_stylesheet_directory());
        $presets  = include $themeDir . 'config/presets.php';
        $choices  = \implode('/', \array_keys($presets));

        // idem: pas de référence statique directe
        $prompt = \call_user_func(['\\WP_CLI', 'prompt'], "Profil ({$choices})", 'vitrine');

        if (!isset($presets[$prompt])) {
            \call_user_func(['\\WP_CLI', 'error'], "Profil inconnu: {$prompt}");
            return;
        }

        self::applyPreset($presets[$prompt], $prompt, $themeDir);
        \call_user_func(['\\WP_CLI', 'success'], "Profil '{$prompt}' appliqué.");
    }

    public static function applyPreset(array $preset, string $name, ?string $themeDir = null): void
    {
        $themeDir = $themeDir ?: \trailingslashit(\get_stylesheet_directory());

        \update_option('yourtheme_active_packs', $preset['packs']);
        \update_option('yourtheme_active_blocks', $preset['blocks']);
        \update_option('yourtheme_active_preset', $name);

        $blocksJson = \wp_json_encode($preset['blocks'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        \file_put_contents($themeDir . '.active-blocks.json', $blocksJson);

        $mirror = ['preset' => $name, 'packs' => $preset['packs'], 'blocks' => $preset['blocks']];
        \file_put_contents($themeDir . 'config/active-preset.json', \wp_json_encode($mirror));
    }
}
