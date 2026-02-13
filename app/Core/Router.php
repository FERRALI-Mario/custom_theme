<?php

namespace App\Core;

use Timber\Timber;

class Router
{
    private static array $routes = [];

    public static function setRoutes(array $routes): void
    {
        self::$routes = $routes;
    }

    /**
     * Point d'entrée principal (hooké sur template_redirect).
     */
    public static function run(): void
    {
        if (self::handleCustomRoutes()) {
            exit;
        }

        $context = Timber::context();

        if (is_front_page()) {
            Timber::render('views/pages/front-page.twig', $context);
            exit;
        }

        if (is_page()) {
            Timber::render('views/pages/page.twig', $context);
            exit;
        }

        if (is_404()) {
            Timber::render('views/pages/404.twig', $context);
            exit;
        }

        Timber::render('views/pages/index.twig', $context);
        exit;
    }

    /**
     * Gère les routes qui dépendent de modules optionnels.
     * Retourne true si une route a été trouvée et affichée.
     */
    private static function handleCustomRoutes(): bool
    {
        if (empty(self::$routes)) {
            return false;
        }

        foreach (self::$routes as $slug => $callback) {
            if (is_page($slug) && is_callable($callback)) {
                call_user_func($callback);
                return true;
            }
        }

        return false;
    }
}
