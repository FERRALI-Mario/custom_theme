<?php
// acf-blocks/hero/Controller.php

namespace App\Blocks\Hero;

use App\Core\BlockFactory;
use Timber\Timber;

class Controller extends BlockFactory
{
    public function __construct()
    {
        // 'hero' correspond au dossier acf-blocks/hero
        parent::__construct('hero');
    }

    /**
     * Callback de rendu du bloc.
     *
     * @param array $block Données du bloc (attributs, innerHTML, etc.)
     */
    public static function render(array $block): void
    {
        // Contexte Timber global
        $context         = Timber::context();
        // Champs ACF affectés à ce bloc
        $context['fields'] = get_fields();
        // On peut aussi passer les attributs du bloc
        $context['block']  = $block;
        // Rendu du template spécifique
        Timber::render('acf-blocks/hero/template.twig', $context);
    }
}
