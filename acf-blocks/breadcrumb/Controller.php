<?php

namespace AcfBlocks\Breadcrumb;

use App\Core\BlockFactory;
use Timber\Timber;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('breadcrumb');
    }

    public function render(array $block): void
    {
        $context = Timber::context();
        $fields = $this->getFields();
        $context['fields'] = $fields;

        if (!empty($fields['enabled'])) :
            $context['breadcrumb'] = $this->generateBreadcrumb();
        endif;

        $previewPath = $this->getPreviewPath();

        if ($this->isPreview($block) && $previewPath) :
            $previewUrl = get_template_directory_uri() . '/' . $previewPath;
            echo '<img src="' . esc_url($previewUrl) . '" style="width:100%;height:auto;" alt="Aperçu du bloc" />';
            return;
        endif;

        Timber::render($this->getTemplatePath(), $context);
    }

    public function generateBreadcrumb(): array
    {
        global $post;

        $breadcrumb = [];

        $breadcrumb[] = [
            'title' => 'Accueil',
            'url'   => home_url(),
        ];

        if (is_singular() && !is_front_page()) :
            $ancestors = get_post_ancestors($post);
            $ancestors = array_reverse($ancestors);

            foreach ($ancestors as $ancestor_id) :
                $breadcrumb[] = [
                    'title' => get_the_title($ancestor_id),
                    'url'   => get_permalink($ancestor_id),
                ];
            endforeach;

            $breadcrumb[] = [
                'title' => get_the_title($post),
                'url'   => get_permalink($post),
            ];
        elseif (is_category()) :
            $term = get_queried_object();
            $breadcrumb[] = [
                'title' => single_cat_title('', false),
                'url'   => get_category_link($term->term_id),
            ];
        elseif (is_404()) :
            $breadcrumb[] = [
                'title' => 'Page introuvable',
                'url'   => '',
            ];
        elseif (is_archive()) :
            $breadcrumb[] = [
                'title' => post_type_archive_title('', false),
                'url'   => '',
            ];
        endif;

        return $breadcrumb;
    }

    public function getTitle(): string
    {
        return 'Fil d\'Ariane';
    }

    public function getDescription(): string
    {
        return 'Affiche le fil d\'Ariane dynamique pour la page actuelle.';
    }

    public function getCategory(): string
    {
        return 'contenu';
    }

    public function getKeywords(): array
    {
        return ['breadcrumb', 'fil d\'Ariane', 'navigation'];
    }

    public function getIcon(): string
    {
        return 'menu';
    }
}
