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
        return 'Image Gallery';
    }

    public function getDescription(): string
    {
        return 'Affiche une galerie d’images responsive.';
    }

    public function getKeywords(): array
    {
        return ['gallery', 'images', 'photos'];
    }

    public function getIcon(): string
    {
        return 'format-gallery';
    }
}
