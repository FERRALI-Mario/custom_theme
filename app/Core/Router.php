<?php

namespace App\Core;

use Timber\Timber;
use Timber\Post;
use Timber\PostQuery;

use App\Paiement\PaymentController;

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

        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        if (strpos($uri, '/paiement') !== false || is_page('paiement')) {
            if (class_exists(PaymentController::class)) {
                PaymentController::viewPayment();
                exit;
            }
        }

        if (strpos($uri, '/success') !== false || is_page('success')) {
            if (class_exists(PaymentController::class)) {
                PaymentController::viewSuccess();
                exit;
            }
        }

        // 2. Contexte global Timber
        $context = Timber::context();
        $context['post']  = Timber::get_post();

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
