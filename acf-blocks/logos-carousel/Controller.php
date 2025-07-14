<?php

namespace AcfBlocks\LogosCarousel;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('logos-carousel');
    }

    /**
     * Récupère le titre du bloc
     */
    public function getTitle(): string
    {
        return 'Logos Carousel'; // Titre du bloc
    }

    /**
     * Récupère la description du bloc
     */
    public function getDescription(): string
    {
        return 'Un carrousel horizontal des logos partenaires/clients.';
    }

    /**
     * Récupère les mots-clés du bloc
     */
    public function getKeywords(): array
    {
        return ['logos', 'carrousel', 'clients', 'partenaires'];
    }

    /**
     * Récupère l'icône du bloc
     */
    public function getIcon(): string
    {
        return 'images-alt2'; // Icône pour le bloc
    }
}
