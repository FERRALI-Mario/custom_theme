<?php

namespace App\Providers;

use App\Core\ServiceProvider;

class Editor extends ServiceProvider
{
    public function register(): void
    {
        add_filter('allowed_block_types_all', [$this, 'allowOnlyCustomBlocks'], 10, 2);
    }

    /**
     * Autorise uniquement les blocs ACF présents dans /acf-blocks/
     */
    public function allowOnlyCustomBlocks(array|bool $allowed_blocks, \WP_Block_Editor_Context $context): array
    {
        // On scanne les dossiers pour ne pas avoir à maintenir une liste manuelle
        $acf_blocks = glob(get_template_directory() . '/acf-blocks/*', GLOB_ONLYDIR);

        if (!$acf_blocks) {
            return $allowed_blocks ?: [];
        }

        return array_map(function ($path) {
            return 'acf/' . basename($path);
        }, $acf_blocks);
    }
}
