<?php

namespace AcfBlocks\Cta;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('cta'); // Enregistrement du bloc 'cta'
    }

    /**
     * Récupère le titre du bloc
     */
    public function getTitle(): string
    {
        return 'Call to Action'; // Titre du bloc
    }

    /**
     * Récupère la description du bloc
     */
    public function getDescription(): string
    {
        return 'Bloc pour afficher un bouton Call to Action avec un titre et une description.';
    }

    /**
     * Récupère les mots-clés du bloc
     */
    public function getKeywords(): array
    {
        return ['cta', 'call to action', 'bouton'];
    }

    /**
     * Récupère l'icône du bloc
     */
    public function getIcon(): string
    {
        return 'button'; // Icône représentant un bouton
    }
}
