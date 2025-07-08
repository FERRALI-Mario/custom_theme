<?php
/**
 * The main template file for the theme.
 *
 * Uses Timber to render Twig templates.
 */

use Timber\Timber;

// Get global context
$context = Timber::context();

// Choose template: front-page if home, else index
$template = is_front_page() ? 'views/pages/front-page.twig' : 'views/pages/index.twig';

// Render the template
Timber::render( $template, $context );
