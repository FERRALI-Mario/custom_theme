<?php
namespace App\Blocks\ProductCard;

use App\Core\BlockFactory;
use Timber\Timber;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('product-card');
    }

    public static function render(array $block): void
    {
        $context = Timber::context();
        $context['fields'] = get_fields();
        $context['block']  = $block;
        Timber::render("acf-blocks/product-card/template.twig", $context);
    }
}