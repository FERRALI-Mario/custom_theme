<?php

namespace AcfBlocks\ContactForm;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('contact-form');

        $fieldsFile = __DIR__ . '/fields.php';
        if (file_exists($fieldsFile)) {
            require_once $fieldsFile;
        }
    }

    public function getTitle(): string
    {
        return 'Formulaire de contact';
    }

    public function getDescription(): string
    {
        return 'Formulaire de contact statique avec champs stylés (pas de traitement).';
    }

    public function getIcon(): string
    {
        return 'email-alt';
    }

    public function getKeywords(): array
    {
        return ['contact', 'formulaire', 'email'];
    }
}
