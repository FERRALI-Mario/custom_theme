<?php

namespace AcfBlocks\Hero;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('hero');

        // Charger les champs ACF si présents
        $fieldsFile = __DIR__ . '/fields.php';
        if (file_exists($fieldsFile)) {
            require_once $fieldsFile;
        }
    }

    /**
     * (Optionnel) Redéfinit le titre affiché dans l’éditeur Gutenberg.
     */
    public function getTitle(): string
    {
        return 'Hero';
    }

    /**
     * (Optionnel) Redéfinit la description.
     */
    public function getDescription(): string
    {
        return 'Bloc Hero avec image de fond, titre et sous-titre';
    }

    /**
     * (Optionnel) Redéfinit les mots-clés du bloc.
     */
    public function getKeywords(): array
    {
        return ['hero', 'bannière', 'accueil'];
    }

    /**
     * (Optionnel) Redéfinit l’icône WordPress du bloc.
     */
    public function getIcon(): string
    {
        return 'cover-image';
    }

    public function register(): void
    {
        acf_register_block_type([
            'name'            => $this->getSlug(), // 'hero'
            'title'           => $this->getTitle(),
            'description'     => $this->getDescription(),
            'icon'            => $this->getIcon(),
            'keywords'        => $this->getKeywords(),
            'category'        => 'layout',
            'mode'            => 'preview',
            'align'           => 'full',
            'supports'        => [
                'align'  => ['wide', 'full'],
                'anchor' => true,
                'jsx'    => false,
            ],
            'render_callback' => [static::class, 'render']
        ]);
    }

    public static function render(array $block): void
    {
        $context = \Timber\Timber::context();
        $context['fields'] = get_fields();
        $context['block'] = $block;

        \Timber\Timber::render('acf-blocks/hero/template.twig', $context);
    }


}
