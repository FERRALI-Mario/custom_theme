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
        return 'Social Links';
    }
    
    public function getDescription(): string
    {
        return 'Un bloc affichant des liens vers les réseaux sociaux avec des icônes';
    }

    public function getKeywords(): array
    {
        return ['social', 'links', 'social-media'];
    }
}
