<?php

namespace AcfBlocks\ProductGrid;

use App\Core\BlockFactory;
use Timber\Timber;
use WC_Product;
use WP_Query;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('product-grid');
    }

    public function getTitle(): string
    {
        return 'Grille de produits';
    }
    public function getDescription(): string
    {
        return 'Affiche une grille de produits WooCommerce avec filtres et pagination.';
    }
    public function getCategory(): string
    {
        return 'woocommerce';
    }
    public function getKeywords(): array
    {
        return ['product', 'woocommerce', 'grid', 'shop'];
    }
    public function getIcon(): string
    {
        return 'cart';
    }

    public function render(array $block): void
    {
        $context = Timber::context();
        $fields  = $this->getFields();
        $context['fields'] = $fields;

        // -------- Sélection GET --------
        $selected = [
            'categories' => isset($_GET['product_category']) ? array_map('intval', (array) $_GET['product_category']) : [],
            'price_min'  => isset($_GET['price_min']) ? (float) str_replace(',', '.', $_GET['price_min']) : null,
            'price_max'  => isset($_GET['price_max']) ? (float) str_replace(',', '.', $_GET['price_max']) : null,
            'rating_min' => isset($_GET['rating_min']) ? min(5, max(1, (int) $_GET['rating_min'])) : null,
        ];

        // -------- Bornes de prix réelles --------
        $price_bounds = $this->getPriceBounds();
        if ($selected['price_min'] === null) {
            $selected['price_min'] = $price_bounds['min'];
        }
        if ($selected['price_max'] === null) {
            $selected['price_max'] = $price_bounds['max'];
        }
        $selected['price_min'] = max($price_bounds['min'], min($selected['price_min'], $price_bounds['max']));
        $selected['price_max'] = max($selected['price_min'], min($selected['price_max'], $price_bounds['max']));

        // Clamp
        $selected['price_min'] = max($price_bounds['min'], min($selected['price_min'], $price_bounds['max']));
        $selected['price_max'] = max($selected['price_min'], min($selected['price_max'], $price_bounds['max']));

        // -------- Pagination --------
        $posts_per_page = isset($fields['products_per_page']) ? min(40, max(4, (int) $fields['products_per_page'])) : 12;
        $paged = isset($_GET['paged']) ? max(1, (int) $_GET['paged']) : 1;

        // -------- Requête produits --------
        $tax_query  = [];
        $meta_query = ['relation' => 'AND'];

        // Catégorie fixée par le bloc
        if (!empty($fields['product_category']) && $fields['product_category'] !== 'tout') {
            $tax_query[] = [
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => (array) $fields['product_category'],
            ];
        }

        // Catégorie depuis le filtre (forcer single côté serveur)
        if (!empty($selected['categories'])) {
            $tax_query[] = [
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => [(int) $selected['categories'][0]],
            ];
        }

        // Prix (DECIMAL pour Woo)
        $meta_query[] = [
            'key'     => '_price',
            'value'   => [(float)$selected['price_min'], (float)$selected['price_max']],
            'type'    => 'DECIMAL(10,2)',
            'compare' => 'BETWEEN',
        ];

        // Note minimale (exclut les produits sans note si définie)
        if (!empty($selected['rating_min'])) {
            $meta_query[] = [
                'key'     => '_wc_average_rating',
                'value'   => (float) $selected['rating_min'],
                'type'    => 'DECIMAL(3,2)',
                'compare' => '>=',
            ];
        }

        $args = [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $posts_per_page,
            'paged'          => $paged,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'meta_query'     => $meta_query,
            'no_found_rows'  => false, // on veut la pagination
        ];
        if ($tax_query) {
            $args['tax_query'] = $tax_query;
        }


        $wpq = new WP_Query($args);
        $products = Timber::get_posts($wpq->posts);

        // Enrichissement WooCommerce (pas de foreach par référence)
        foreach ($products as $product) {
            $wc = wc_get_product($product->ID);
            if ($wc instanceof WC_Product) {
                $product->price_html      = $wc->get_price_html();
                $product->in_stock        = $wc->is_in_stock();
                $product->regular_price   = $wc->get_regular_price();
                $product->sale_price      = $wc->get_sale_price();
                $product->add_to_cart_url = $wc->add_to_cart_url();
                $product->sku             = $wc->get_sku();
                $product->average_rating  = $wc->get_average_rating();
                $product->_wc_product     = $wc;
            }
        }

        // Contexte
        $context['products']        = $products;
        $context['pagination']      = paginate_links([
            'total'     => max(1, (int) $wpq->max_num_pages),
            'current'   => $paged,
            'format'    => '?paged=%#%',
            'prev_text' => 'Précédent',
            'next_text' => 'Suivant',
        ]);
        $context['shop_url']        = get_permalink(wc_get_page_id('shop'));
        $context['selected']        = $selected;
        $context['price_bounds']    = $price_bounds;

        // Catégories existantes (pour l’UI)
        $terms = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true]);
        $context['product_categories'] = array_map(fn($t) => ['id' => (int) $t->term_id, 'name' => $t->name], is_array($terms) ? $terms : []);

        // Filtres activés ?
        $context['filter_component'] = !empty($fields['enable_filter']);

        // Aperçu ACF
        if ($this->isPreview($block) && $this->getPreviewPath()) {
            echo sprintf('<img src="%s" alt="Aperçu du bloc" style="width:100%%;height:auto;" />', esc_url(get_template_directory_uri() . '/' . $this->getPreviewPath()));
            return;
        }

        Timber::render($this->getTemplatePath(), $context);
    }

    /**
     * Bornes de prix réelles du catalogue
     */
    protected function getPriceBounds(): array
    {
        // MAX
        $qMax = new WP_Query([
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC',
            'meta_key'       => '_price',
            'fields'         => 'ids',
            'no_found_rows'  => true,
            'meta_query'     => [[
                'key'     => '_price',
                'value'   => 0,
                'type'    => 'NUMERIC',
                'compare' => '>',
            ]],
        ]);
        $max = $qMax->posts ? (float) get_post_meta($qMax->posts[0], '_price', true) : 0.0;

        // MIN (>0)
        $qMin = new WP_Query([
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'orderby'        => 'meta_value_num',
            'order'          => 'ASC',
            'meta_key'       => '_price',
            'fields'         => 'ids',
            'no_found_rows'  => true,
            'meta_query'     => [[
                'key'     => '_price',
                'value'   => 0,
                'type'    => 'NUMERIC',
                'compare' => '>',
            ]],
        ]);
        $min = $qMin->posts ? (float) get_post_meta($qMin->posts[0], '_price', true) : 0.0;

        if ($max <= 0) {
            $max = 1000.0;
        }
        if ($min <= 0 || $min > $max) {
            $min = 0.0;
        }

        return ['min' => $min, 'max' => $max];
    }
}
