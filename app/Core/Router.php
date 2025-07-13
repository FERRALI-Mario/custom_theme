<?php

namespace App\Core;

use Timber\Timber;
use Timber\Post;
use Timber\PostQuery;

class Router
{
    public static function run(): void
    {
        $context = Timber::context();
        $context['post']  = Timber::get_post();
        $context['posts'] = Timber::get_posts([
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => 10,
        ]);

        // Gestion minimale : front, page, 404, index
        if (is_front_page()) {
            Timber::render('views/pages/front-page.twig', $context);
            return;
        }

        if (is_page()) {
            Timber::render('views/pages/page.twig', $context);
            return;
        }

        if (is_404()) {
            Timber::render('views/pages/404.twig', $context);
            return;
        }

        // Fallback global
        Timber::render('views/pages/index.twig', $context);
    }
}
