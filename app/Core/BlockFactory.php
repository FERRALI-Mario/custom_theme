<?php

namespace App\Core;

use Timber\Timber;

/**
 * Base class for all ACF Blocks.
 */
abstract class BlockFactory
{
    protected string $slug;

    public function __construct(string $slug)
    {
        $this->slug = $slug;

        // Chargement automatique des champs ACF si fichier présent
        $fieldsPath = get_template_directory() . "/acf-blocks/{$slug}/fields.php";
        if (file_exists($fieldsPath)) {
            require_once $fieldsPath;
        }

        // Enregistrement automatique du bloc
        add_action('acf/init', [$this, 'register']);
    }

    /**
     * Enregistre le bloc ACF.
     */
    public function register(): void
    {
        if (!function_exists('acf_register_block_type')) {
            return;
        }

        acf_register_block_type([
            'name'            => $this->getSlug(),
            'title'           => $this->getTitle(),
            'description'     => $this->getDescription(),
            'category'        => $this->getCategory(),
            'icon'            => $this->getIcon(),
            'keywords'        => $this->getKeywords(),
            'mode'            => 'preview',
            'align'           => 'full',
            'supports'        => $this->getSupports(),
            'render_callback' => [$this, 'render'],
        ]);
    }

    /**
     * Callback de rendu appelé automatiquement par ACF.
     */
    public function render(array $block): void
    {
        $context = Timber::context();
        $context['block'] = $block;
        $context['fields'] = $this->getFields();

        Timber::render($this->getTemplatePath(), $context);
    }

    /**
     * Retourne les champs ACF (ou [] si vide).
     */
    protected function getFields(): array
    {
        return function_exists('get_fields') ? get_fields() ?: [] : [];
    }

    /**
     * Récupère une image via Timber (helper).
     */
    protected function getImage(string $field): ?\Timber\Image
    {
        $image = get_field($field);
        return $image ? new \Timber\Image($image) : null;
    }

    /**
     * Permet à chaque bloc de spécifier son propre template si besoin.
     */
    protected function getTemplatePath(): string
    {
        return "acf-blocks/{$this->slug}/template.twig";
    }

    public function getTitle(): string
    {
        return ucfirst($this->slug);
    }

    public function getDescription(): string
    {
        return 'Bloc ' . ucfirst($this->slug);
    }

    public function getCategory(): string
    {
        return 'layout';
    }

    public function getIcon(): string
    {
        return 'block-default';
    }

    public function getKeywords(): array
    {
        return [$this->slug];
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getSupports(): array
    {
        return [
            'anchor' => true,
            'align'  => ['wide', 'full'],
            'jsx'    => false,
        ];
    }
}
