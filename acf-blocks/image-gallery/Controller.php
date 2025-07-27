<?php

namespace AcfBlocks\ImageGallery;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('image-gallery');
    }

    public function getTitle(): string
    {
        return 'Galerie \'images';
    }

    public function getDescription(): string
    {
        return 'Affiche une grille ou un carrousel d\’images pour mettre en valeur des photos de produits, de projets ou d\'événements.';
    }

    public function getKeywords(): array
    {
        return ['gallery', 'images', 'photos'];
    }
    
    public function getCategory(): string
    {
        return 'contenu';
    }

    public function getIcon(): string
    {
        return 'format-gallery';
    }
}
