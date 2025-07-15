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

        $fields['contact_form_rendered'] = !empty($fields['contact_form_shortcode'])
            ? do_shortcode($fields['contact_form_shortcode'])
            : '';

        $context['fields'] = $fields;
        $context['block'] = $block;

        Timber::render('acf-blocks/contact-form/template.twig', $context);
    }


    public function getTitle(): string
    {
        return 'Contact Form';
    }

    public function getDescription(): string
    {
        return 'Affiche un formulaire de contact intégré via un shortcode.';
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
