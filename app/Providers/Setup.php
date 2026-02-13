<?php

namespace App\Providers;

use App\Core\ServiceProvider;
use App\Core\Context;
use Timber\Timber;

class Setup extends ServiceProvider
{
    public function register(): void
    {
        add_filter('timber/context', [Context::class, 'extend']);
        $this->bootTimber();

        add_action('after_setup_theme', [$this, 'setup']);
        add_filter('block_categories_all', [$this, 'registerBlockCategories'], 10, 2);
    }

    private function bootTimber(): void
    {
        if (!class_exists(Timber::class)) {
            return;
        }

        Timber::$dirname = ['views', 'views/components', 'views/partials', 'views/layouts'];
    }

    public function setup(): void
    {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('menus');
        add_theme_support('align-wide');
        add_theme_support('html5', ['comment-list', 'comment-form', 'search-form', 'gallery', 'caption']);

        register_nav_menus([
            'primary' => 'Menu principal',
            'footer'  => 'Menu pied de page',
        ]);
    }

    public function registerBlockCategories(array $categories, $editor_context): array
    {
        $new_categories = [
            ['slug' => 'contenu',       'title' => '🎨 Contenu'],
            ['slug' => 'maison',        'title' => '🏡 Maison'],
            ['slug' => 'woocommerce',   'title' => '🛒 WooCommerce'],
            ['slug' => 'mise-en-avant', 'title' => '🧩 Mise en avant'],
            ['slug' => 'contact',       'title' => '📇 Contact'],
            ['slug' => 'relations',     'title' => '👥 Équipe & Témoignages'],
        ];

        return array_merge($new_categories, $categories);
    }
}
