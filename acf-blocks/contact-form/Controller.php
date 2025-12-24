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

    protected function enqueueAssets(): void
    {
        $handle = 'contact-form-js';
        $src = get_template_directory_uri() . '/assets/js/contact.js';

        if (file_exists(get_template_directory() . '/assets/js/contact.js')) {
            wp_enqueue_script($handle, $src, [], filemtime(get_template_directory() . '/assets/js/contact.js'), true);

            wp_localize_script($handle, 'CONTACT_FORM', [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('contact_form_action') // Doit correspondre à AjaxHandler check
            ]);
        }
    }
}
