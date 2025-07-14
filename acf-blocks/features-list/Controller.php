<?php

namespace AcfBlocks\FeaturesList;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('features-list'); // Enregistrement du bloc 'features-list'
    }

    /**
     * Récupère le titre du bloc
     */
    public function getTitle(): string
    {
        return 'Liste de Fonctionnalités'; // Nom du bloc
    }

    /**
     * Récupère la description du bloc
     */
    public function getDescription(): string
    {
        return 'Bloc pour afficher une liste de fonctionnalités ou d\'avantages';
    }

    /**
     * Récupère les mots-clés du bloc
     */
    public function getKeywords(): array
    {
        return ['fonctionnalités', 'avantages', 'liste'];
    }

    /**
     * Récupère l'icône du bloc
     */
    public function getIcon(): string
    {
        return 'list-alt'; // Icône de liste
    }
}
