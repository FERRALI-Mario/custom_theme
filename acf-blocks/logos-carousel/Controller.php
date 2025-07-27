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

    public function render(array $block): void
    {
        $context = Timber::context();
        $context['block'] = $block;
        $context['fields'] = $this->getFields();
        $logos = $context['fields']['logos'] ?? [];

        $count = count($logos);
        $context['marquee_duration'] = $duration = max(8, ceil(($count / 5) * 13));

        $previewPath = $this->getPreviewPath();

        if ($this->isPreview($block) && $previewPath) :
            $previewUrl = get_template_directory_uri() . '/' . $previewPath;
            echo '<img src="' . esc_url($previewUrl) . '" style="width:100%;height:auto;" alt="Aperçu du bloc" />';
            return;
        endif;

        Timber::render($this->getTemplatePath(), $context);
    }

    public function getTitle(): string
    {
        return 'Carrousel de logos';
    }

    public function getDescription(): string
    {
        return 'Affiche les logos de partenaires, clients ou certifications sous forme de slider.';
    }

    public function getCategory(): string
    {
        return 'relations';
    }

    public function getKeywords(): array
    {
        return ['logos', 'carrousel', 'clients', 'partenaires'];
    }

    public function getIcon(): string
    {
        return 'slides'; 
    }
}
