<?php

namespace AcfBlocks\SocialLinks;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('social-links');
    }

    public function getTitle(): string
    {
        return 'Liens sociaux';
    }
    
    public function getDescription(): string
    {
        return 'Affiche les icônes/liens vers tes réseaux sociaux.';
    }

    public function getCategory(): string
    {
        return 'contact';
    }

    public function getKeywords(): array
    {
        return ['social', 'links', 'social-media'];
    }

    public function getIcon(): string
    {
        return 'share';
    }
}
