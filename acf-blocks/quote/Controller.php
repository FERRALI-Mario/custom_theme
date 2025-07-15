<?php

namespace AcfBlocks\Quote;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('quote');
    }

    public function getTitle(): string
    {
        return 'Quote';
    }

    public function getDescription(): string
    {
        return 'Affiche une citation avec l\'auteur.';
    }

    public function getKeywords(): array
    {
        return ['quote', 'testimonial', 'author'];
    }

    public function getIcon(): string
    {
        return 'format-quote';
    }
}
