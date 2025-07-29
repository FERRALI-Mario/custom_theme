<?php

namespace AcfBlocks\ProductGrid;

use App\Core\BlockFactory;
use Timber\Timber;
use WC_Product;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('product-grid');
    }

    public function render(array $block): void
    {
        $context = Timber::context();
        $fields = $this->getFields();
        $context['fields'] = $fields;

        // Pagination
        $posts_per_page = isset($fields['products_per_page']) ? min(40, max(4, (int) $fields['products_per_page'])) : 12;
        $paged = isset($_GET['paged']) ? max(1, (int) $_GET['paged']) : 1;

        // WP_Query args
        $args = [
            'post_type'      => 'product',
            'posts_per_page' => $posts_per_page,
            'paged'          => $paged,
            'post_status'    => 'publish',
        ];

        // Filtrage par catégorie si précisé
        if (!empty($fields['product_category']) && $fields['product_category'] !== 'tout') {
            $args['tax_query'] = [[
                'taxonomy' => 'product_cat',
                'field'    => 'id',
                'terms'    => (array) $fields['product_category'],
            ]];
        }

        // Récupération des produits (converti en tableau pour foreach par référence)
        $products = Timber::get_posts($args);
        $products = is_array($products) ? $products : iterator_to_array($products);

        // Enrichissement avec les données WooCommerce
        foreach ($products as &$product) {
            $wc_product = wc_get_product($product->ID);

            if ($wc_product instanceof WC_Product) {
                $product->price_html       = $wc_product->get_price_html();
                $product->is_on_sale       = $wc_product->is_on_sale();
                $product->in_stock         = $wc_product->is_in_stock();
                $product->sale_price       = $wc_product->get_sale_price();
                $product->regular_price    = $wc_product->get_regular_price();
                $product->add_to_cart_url  = $wc_product->add_to_cart_url();
                $product->sku              = $wc_product->get_sku();
            }
        }
        unset($product); // sécurité après référence

        $context['products'] = $products;

        // Pagination
        $total_products = wp_count_posts('product')->publish ?? 0;
        $context['pagination'] = paginate_links([
            'total'     => ceil($total_products / $posts_per_page),
            'current'   => $paged,
            'format'    => '?paged=%#%',
            'prev_text' => 'Précédent',
            'next_text' => 'Suivant',
        ]);

        // Aperçu dans l’éditeur
        if ($this->isPreview($block) && $this->getPreviewPath()) {
            echo sprintf(
                '<img src="%s" alt="Aperçu du bloc" style="width:100%%;height:auto;" />',
                esc_url(get_template_directory_uri() . '/' . $this->getPreviewPath())
            );
            return;
        }

        // Rendu final via Twig
        Timber::render($this->getTemplatePath(), $context);
    }

    public function getTitle(): string
    {
        return 'Grille de produits';
    }

    public function getDescription(): string
    {
        return 'Affiche une grille de produits WooCommerce avec filtre par catégorie et pagination.';
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
}
