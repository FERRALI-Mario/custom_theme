<?php

namespace App\Core;

/**
 * Classe de base pour les fournisseurs de services.
 * Les fournisseurs sont utilisés pour enregistrer des fonctionnalités,
 * des hooks, des routes, etc.
 */
abstract class ServiceProvider
{
    /**
     * Enregistre les liaisons dans le conteneur de services.
     * Exécuté tôt dans le cycle de vie.
     */
    public function register(): void {}

    /**
     * "Botte" le service provider, après que tous les providers ont été enregistrés.
     * Idéal pour ajouter des hooks.
     */
    public function boot(): void {}

    /**
     * Définit les routes personnalisées pour le provider.
     * @return array
     */
    public function routes(): array
    {
        return [];
    }
}
