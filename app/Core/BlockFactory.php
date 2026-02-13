<?php

namespace App\Core;

use Timber\Timber;

abstract class BlockFactory
{
    protected string $slug;

    public function __construct(string $slug)
    {
        $this->slug = $slug;

        add_action('acf/init', [$this, 'registerFields']);

        $this->loadAjaxHandler();

        add_action('acf/init', [$this, 'register']);
    }

    public function registerFields(): void
    {
        $fieldsPath = get_template_directory() . "/acf-blocks/{$this->slug}/fields.php";
        if (file_exists($fieldsPath)) {
            require_once $fieldsPath;
        }
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
            'example'         => [
                'attributes' => [
                    'mode' => 'preview',
                    'data' => [
                        'is_example' => true,
                    ],
                ],
            ],
        ]);
    }

    /**
     * Callback de rendu appelé automatiquement par ACF.
     */
    public function render(array $block): void
    {
        $this->enqueueAssets();

        $context = Timber::context();
        $context['block'] = $block;
        $context['fields'] = $this->getFields();

        $previewPath = $this->getPreviewPath();

        if ($this->isPreview($block) && $previewPath) :
            $previewUrl = get_template_directory_uri() . '/' . $previewPath;
            echo '<img src="' . esc_url($previewUrl) . '" style="width:100%;height:auto;" alt="Aperçu du bloc" />';
            return;
        endif;

        $context = $this->prepareContext($context);

        Timber::render($this->getTemplatePath(), $context);
    }

    protected function loadAjaxHandler(): void
    {
        $handlerPath = get_template_directory() . "/acf-blocks/{$this->slug}/AjaxHandler.php";

        if (file_exists($handlerPath)) {
            require_once $handlerPath;

            $namespaceSlug = str_replace(' ', '', ucwords(str_replace('-', ' ', $this->slug)));
            $className = "\\AcfBlocks\\{$namespaceSlug}\\AjaxHandler";

            if (class_exists($className) && method_exists($className, 'register')) {
                $className::register();
            }
        }
    }

    protected function enqueueAssets(): void
    {
        $js_path = "/assets/js/{$this->slug}.js";
        $abs_path = get_template_directory() . $js_path;

        if (file_exists($abs_path)) {
            wp_enqueue_script(
                "block-{$this->slug}", // Handle unique : block-timeline
                get_template_directory_uri() . $js_path,
                [], // Dépendances (ajouter ['jquery'] si besoin)
                filemtime($abs_path), // Cache busting auto
                true // Footer
            );
        }
    }

    /**
     * Méthode destinée à être surchargée par les Controllers enfants.
     */
    protected function prepareContext(array $context): array
    {
        return $context;
    }

    /**
     * Retourne les champs ACF (ou [] si vide).
     */
    protected function getFields(): array
    {
        return function_exists('get_fields') ? (get_fields() ?: []) : [];
    }

    /**
     * Récupère une image via Timber (helper).
     */
    protected function getImage(array $fields, string $key): ?\Timber\Image
    {
        if (!empty($fields[$key])) {
            return Timber::get_image($fields[$key]);
        }
        return null;
    }

    /**
     * Permet à chaque bloc de spécifier son propre template si besoin.
     */
    protected function getTemplatePath(): string
    {
        return "acf-blocks/{$this->slug}/template.twig";
    }

    /**
     * Permet de savoir s'il y a bien une preview du bloc.
     */
    protected function isPreview(array $block): bool
    {
        return isset($block['data']['is_example']) && $block['data']['is_example'];
    }

    /**
     * Permet d'afficher un aperçu pour chaque bloc.
     */
    protected function getPreviewPath(): ?string
    {
        $relative = "acf-blocks/{$this->slug}/preview.png";
        $absolute = get_template_directory() . '/' . $relative;

        return file_exists($absolute) ? $relative : null;
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
