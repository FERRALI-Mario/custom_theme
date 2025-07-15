<?php

namespace AcfBlocks\LogosCarousel;

use App\Core\BlockFactory;
use Timber\Timber;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('logos-carousel');
    }

    /**
     * Injection de la durée du carousel en fonction du nombre de logos
     */
    public function render(array $block): void
    {
        $context = Timber::context();
        $context['block'] = $block;
        $context['fields'] = $this->getFields();
        $logos = $context['fields']['logos'] ?? [];

        $count = count($logos);
        $context['marquee_duration'] = $duration = max(8, ceil(($count / 5) * 13));

        Timber::render($this->getTemplatePath(), $context);
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
