<?php

namespace App\Core;

use Timber\Menu;

class Context
{
    public static function extend(array $context): array
    {
        // Infos globales du site
        $context['site'] = [
            'name'        => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'url'         => get_bloginfo('url'),
        ];

        $context['menus'] = [];

        $primaryMenu = wp_get_nav_menu_object('primary');
        if ($primaryMenu) :
            $context['menus']['primary'] = Menu::build($primaryMenu);
        endif;

        $footerMenu = wp_get_nav_menu_object('footer');
        if ($footerMenu) :
            $context['menus']['footer'] = Menu::build($footerMenu);
        endif;

        if (function_exists('get_fields')) :
            $context['options'] = get_fields('option') ?: [];

            $context['header'] = [
                'logo' => get_field('header_logo', 'option'),
            ];

            $context['footer'] = [
                'logo' => get_field('footer_text', 'option'),
            ];
        endif;

        return $context;
    }
}
