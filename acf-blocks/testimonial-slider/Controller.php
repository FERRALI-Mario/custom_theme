<?php

namespace AcfBlocks\TestimonialSlider;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('testimonial-slider');

        $fieldsFile = __DIR__ . '/fields.php';
        if (file_exists($fieldsFile)) {
            require_once $fieldsFile;
        }
    }

    public function getTitle(): string
    {
        return 'Témoignages – Slider';
    }

    public function getDescription(): string
    {
        return 'Affiche une série de témoignages clients sous forme de slider.';
    }

    public function getIcon(): string
    {
        return 'format-quote';
    }

    public function getKeywords(): array
    {
        return ['testimonial', 'avis', 'slider'];
    }
}
