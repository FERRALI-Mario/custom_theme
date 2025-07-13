<?php

namespace App\Core;

use Timber\Timber;

/**
 * Classe de base pour tout bloc ACF orienté objet.
 */
abstract class BlockFactory
{
    protected string $slug;

    /**
     * Constructeur à appeler dans chaque bloc : parent::__construct('slug')
     */
    public function __construct(string $slug)
    {
        $this->slug = $slug;

        // Registre le bloc ACF au bon moment
        add_action('acf/init', [$this, 'register']);
    }

    /**
     * Enregistrement du bloc ACF (appelé automatiquement).
     */
    public function register(): void
    {
        $settings = [
            'name'            => $this->slug,
            'title'           => $this->getTitle(),
            'description'     => $this->getDescription(),
            'category'        => $this->getCategory(),
            'icon'            => $this->getIcon(),
            'keywords'        => $this->getKeywords(),
            'mode'            => 'preview',
            'align'           => 'full',
            'supports'        => [
                'anchor' => true,
                'align'  => ['wide', 'full'],
                'jsx'    => false,
            ],
            'render_callback' => $this->getRenderCallback(),
        ];

        if (function_exists('acf_register_block_type')) {
            acf_register_block_type($settings);
        }
    }

    /**
     * Rendu par défaut d’un bloc ACF avec Timber.
     */
    public static function render(array $block): void
    {
        $context = Timber::context();
        $context['fields'] = function_exists('get_fields') ? get_fields() : [];
        $context['block']  = $block;

        $slug = $block['name'] ?? 'unknown';
        $slug = str_replace('acf/', '', $slug);

        $templatePath = "acf-blocks/{$slug}/template.twig";

        if (file_exists(get_template_directory() . "/{$templatePath}")) {
            Timber::render($templatePath, $context);
        } else {
            echo "<p style='color: red;'>Template introuvable : {$templatePath}</p>";
        }
    }

    // ─────────────────────────────────────────────────────────────
    // Méthodes personnalisables dans chaque bloc si besoin
    // ─────────────────────────────────────────────────────────────

    public function getRenderCallback(): callable
    {
        return [static::class, 'render'];
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

    public function getName(): string
    {
        return 'acf/' . $this->slug;
    }
}
