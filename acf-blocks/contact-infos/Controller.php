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
        return 'Informations de contact';
    }

    public function getDescription(): string
    {
        return 'Affiche les coordonnées de l’entreprise : adresse, téléphone, email, horaires, etc.';
    }

    public function getCategory(): string
    {
        return 'contact';
    }

    public function getKeywords(): array
    {
        return ['contact', 'infos', 'adresse', 'email', 'téléphone'];
    }

    public function getIcon(): string
    {
        return 'id';
    }
}
