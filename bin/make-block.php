#!/usr/bin/env php
<?php
// bin/make-block.php

if (PHP_SAPI !== 'cli') {
    exit("This script must be run from the command line.\n");
}

if ($argc < 2) {
    exit("Usage: php make-block.php <block-name>\n");
}

$block = trim($argv[1]);
$slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $block));
$ucName = str_replace(' ', '', ucwords(str_replace('-', ' ', $slug)));

$themeDir = realpath(__DIR__ . '/..');
$blocksDir = "$themeDir/acf-blocks/$slug";
$acfJsonDir = "$themeDir/acf-json";

// 1. Create block directory
if (!is_dir($blocksDir)) {
    mkdir($blocksDir, 0755, true);
}

// 2. block.json
$blockJson = [
    "apiVersion"     => 2,
    "name"           => "wp-theme-boilerplate/{$slug}",
    "title"          => ucfirst($block),
    "category"       => "common",
    "icon"           => "admin-comments",
    "description"    => ucfirst($block) . " block",
    "supports"       => [
        "anchor" => true,
        "align"  => ["wide", "full"]
    ],
    "textdomain"     => "wp-theme-boilerplate",
    "renderCallback" => "App\\\\Blocks\\\\{$ucName}\\\\Controller::render"
];
file_put_contents(
    "$blocksDir/block.json",
    json_encode($blockJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
);

// 3. Controller.php
$controllerPhp = <<<PHP
<?php
namespace App\\Blocks\\$ucName;

use App\\Core\\BlockFactory;
use Timber\\Timber;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('$slug');
    }

    public static function render(array \$block): void
    {
        \$context = Timber::context();
        \$context['fields'] = get_fields();
        \$context['block']  = \$block;
        Timber::render("acf-blocks/$slug/template.twig", \$context);
    }
}
PHP;
file_put_contents("$blocksDir/Controller.php", $controllerPhp);

// 4. template.twig
$template = <<<TWIG
{# acf-blocks/$slug/template.twig #}
<div class="$slug-block">
  {# TODO: Add your markup and use {{ fields.your_field }} #}
</div>
TWIG;
file_put_contents("$blocksDir/template.twig", $template);

// 5. style.scss
$style = <<<SCSS
@tailwind base;
@tailwind components;
@tailwind utilities;

@layer components {
  .$slug-block {
    @apply py-8 px-4;
  }
}
SCSS;
file_put_contents("$blocksDir/style.scss", $style);

// 6. Create empty ACF JSON group
if (!is_dir($acfJsonDir)) {
    mkdir($acfJsonDir, 0755, true);
}
file_put_contents("$acfJsonDir/group_{$slug}.json", "{}");

echo "Bloc '{$slug}' généré avec succès !\n";
