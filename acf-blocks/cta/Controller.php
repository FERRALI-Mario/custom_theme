<?php

namespace AcfBlocks\Cta;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('cta');
    }

    public function getTitle(): string
    {
        return 'Appel à l’action (CTA)';
    }

    public function getDescription(): string
    {
        return 'Met en avant un message ou une action importante comme « Demander un devis » ou « S’inscrire ».';
    }

    public function getCategory(): string
    {
        return 'mise-en-avant';
    }

    public function getKeywords(): array
    {
        return ['cta', 'call to action', 'bouton'];
    }

    public function getIcon(): string
    {
        return 'megaphone';
    }
}
