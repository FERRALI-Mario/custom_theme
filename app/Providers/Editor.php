<?php

namespace App\Providers;

use App\Core\BlockFactory;

class Editor
{
    public static function register(): void
    {
        add_filter('allowed_block_types_all', [self::class, 'allowOnlyCustomBlocks'], 10, 2);
    }

    /**
     * Autorise uniquement les blocs ACF présents dans /acf-blocks/
     */
    public static function allowOnlyCustomBlocks(array|bool $allowed_blocks, \WP_Block_Editor_Context $context): array
    {
        $acf_blocks = glob(get_template_directory() . '/acf-blocks/*', GLOB_ONLYDIR);

        return array_map(function ($path) {
            return 'acf/' . basename($path);
        }, $acf_blocks);
    }

}
