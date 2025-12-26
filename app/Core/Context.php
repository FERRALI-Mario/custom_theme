<?php

namespace App\Core;

use Timber\Timber;

class Context
{
    public static function extend(array $context): array
    {
        $context['site'] = new \Timber\Site();

        $context['menu'] = Timber::get_menu('primary');
        $context['menu_footer'] = Timber::get_menu('footer');

        if (function_exists('get_fields')) {
            $context['options'] = get_fields('option') ?: [];

            $header_logo = get_field('header_logo', 'option');
            $footer_logo = get_field('footer_logo', 'option');

            $context['logos'] = [
                'header' => $header_logo,
                'footer' => $footer_logo ?: $header_logo
            ];
        }

        return $context;
    }
}
