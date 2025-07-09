<?php

namespace App\Providers;

use App\Core\BlockFactory;
use DirectoryIterator;

class Blocks
{
    public static function registerAll(): void
    {
        $blocksDir = get_template_directory() . '/acf-blocks';

        foreach (new DirectoryIterator($blocksDir) as $fileInfo) {
            if ($fileInfo->isDot() || !$fileInfo->isDir()) {
                continue;
            }

            $blockName = $fileInfo->getFilename();
            $className = '\\App\\Blocks\\' . ucfirst($blockName) . '\\Controller';

            if (class_exists($className)) {
                new $className($blockName);
            } else {
                // Fallback metadata register
                register_block_type_from_metadata($fileInfo->getPathname());
            }
        }
    }
}

add_action('init', ['App\\Providers\\Blocks', 'registerAll']);
