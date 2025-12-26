<?php

namespace App\Core;

use Timber\Timber;

class Context
{
    public static function extend(array $context): array
    {
        $context['site'] = new \Timber\Site();

        $context['menu'] = Timber::get_menu('primary');

        if (function_exists('get_fields')) {
            $context['options'] = get_fields('option') ?: [];

            $context['header'] = [
                'logo' => get_field('header_logo', 'option'),
            ];

            $context['footer'] = [
                'logo' => get_field('footer_text', 'option'), // ou footer_logo
            ];
        }

        return $context;
    }
}
