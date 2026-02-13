<?php

namespace App\Core;

use App\Providers\Setup;
use App\Providers\Assets;
use App\Providers\Admin;
use App\Providers\Cleanup;
use App\Providers\Blocks;
use App\Providers\Editor;
use App\Providers\PostTypes;
use App\Core\Router;
use App\Providers\PaiementServiceProvider;

class Theme
{
    protected static array $providers = [
        Setup::class,       // Config Timber, Menus, Supports
        Cleanup::class,     // Nettoyage HEAD
        Assets::class,      // Scripts & Styles
        Admin::class,       // Options ACF
        PostTypes::class,   // CPT (Booking)
        Blocks::class,      // Découverte des blocs ACF
        Editor::class,      // Restrictions Gutenberg
        //PaiementServiceProvider::class, // Décommenter pour activer le module de paiement
    ];

    public static function init(): void
    {
        $routes = [];

        // 1. Boot des providers
        foreach (self::$providers as $provider) {
            $instance = new $provider();
            if (method_exists($instance, 'register')) {
                $instance->register();
            }
            if (method_exists($instance, 'boot')) {
                $instance->boot();
            }
            if (method_exists($instance, 'routes')) {
                $routes = array_merge($routes, $instance->routes());
            }
        }

        Router::setRoutes($routes);
        add_action('template_redirect', [Router::class, 'run']);

        // Enregistrement des commandes WP-CLI
        if (defined('WP_CLI') && WP_CLI) {
            if (class_exists(\App\Console\InitCommand::class)) {
                \App\Console\InitCommand::register();
            }
        }
    }
}
