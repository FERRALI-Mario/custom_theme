<?php

namespace App\Core;

use Timber\Menu;
use Timber\Site;

class Context
{
    public static function extend(array $context): array
    {
        $context['site'] = [
            'name'        => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'url'         => get_bloginfo('url'),
        ];

        $context['menu']        = has_nav_menu('main')   ? new Menu('main')   : null;
        $context['footer_menu'] = has_nav_menu('footer') ? new Menu('footer') : null;

        if (function_exists('get_fields')) {
            $context['options'] = get_fields('option') ?: [];
        }

        return $context;
    }
}
