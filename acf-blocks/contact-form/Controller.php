<?php

namespace AcfBlocks\ContactForm;

use App\Core\BlockFactory;
use Timber\Timber;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('contact-form');
    }

    public function render(array $block): void
    {
        $context = Timber::context();
        $fields = get_fields();

        $fields['rendered'] = !empty($fields['shortcode'])
            ? do_shortcode($fields['shortcode'])
            : '';

        $context['fields'] = $fields;
        $context['block'] = $block;

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
        return 'Formulaire de contact';
    }

    public function getDescription(): string
    {
        return 'Permet d’ajouter un formulaire personnalisable pour que les visiteurs puissent te contacter facilement.';
    }

    public function getCategory(): string
    {
        return 'contact';
    }

    public function getKeywords(): array
    {
        return ['contact', 'form'];
    }

    public function getIcon(): string
    {
        return 'email';
    }
}
