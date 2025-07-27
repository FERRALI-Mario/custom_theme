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
        return 'Citation';
    }

    public function getDescription(): string
    {
        return 'Met en avant une citation, un avis client ou une phrase inspirante.';
    }

    public function getCategory(): string
    {
        return 'mise-en-avant';
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
