<?php

namespace AcfBlocks\Testimonials;

use App\Core\BlockFactory;
use Timber\Timber;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('testimonials');
    }

    /**
     * Injection de la durée du carousel en fonction du nombre d'avis
     */
    public function render(array $block): void
    {
        $context = Timber::context();
        $context['block'] = $block;
        $context['fields'] = $this->getFields();
        $context['testimonials'] = $context['fields']['testimonials'] ?? [];

        $count = count($context['testimonials']);
        $duration = max(8, ceil(($count / 5) * 13)); // min 8s pour éviter que ça freeze
        $context['marquee_duration'] = $duration;

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
        return 'Témoignages clients';
    }

    public function getDescription(): string
    {
        return 'Montre les retours ou avis de clients pour renforcer la confiance.';
    }

    public function getCategory(): string
    {
        return 'relations';
    }

    public function getKeywords(): array
    {
        return ['testimonials', 'avis', 'clients'];
    }

    public function getIcon(): string
    {
        return 'star-filled';
    }
}
