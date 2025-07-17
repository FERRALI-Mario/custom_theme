<?php

namespace AcfBlocks\ContactInfos;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('contact-infos');
    }

    public function getTitle(): string
    {
        return 'Contact Infos';
    }

    public function getDescription(): string
    {
        return 'Affiche les informations de contact (adresse, email, téléphone, horaire)';
    }

    public function getKeywords(): array
    {
        return ['contact', 'infos', 'adresse', 'email', 'téléphone'];
    }

    public function getIcon(): string
    {
        return 'email';
    }
}
