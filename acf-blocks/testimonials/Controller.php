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

    public function render(array $block): void
    {
        $context = Timber::context();
        $context['block'] = $block;
        $context['fields'] = $this->getFields();
        $context['testimonials'] = $context['fields']['testimonials'] ?? [];

        $count = count($context['testimonials']);
        $duration = max(8, ceil(($count / 5) * 13)); // min 8s pour éviter que ça freeze
        $context['marquee_duration'] = $duration;

        Timber::render($this->getTemplatePath(), $context);
    }

    public function getTitle(): string
    {
        return 'Testimonials';
    }

    public function getDescription(): string
    {
        return 'Carrousel horizontal de témoignages clients.';
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
