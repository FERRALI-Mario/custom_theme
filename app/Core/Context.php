<?php

namespace App\Core;

use Timber\Timber;

class Context
{
    public static function extend(array $context)
    {
        // Site global
        $context['site'] = [
            'name' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'url' => get_bloginfo('url'),
        ];

        // Menus
        $context['menu'] = new \Timber\Menu('main');
        $context['footer_menu'] = new \Timber\Menu('footer');

        // Options via ACF
        if (function_exists('get_fields')) {
            $context['options'] = get_fields('option');
        }

        return $context;
    }
}

add_filter('timber/context', [Context::class, 'extend']);