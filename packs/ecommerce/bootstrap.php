<?php

namespace Packs\Ecommerce;

if (!class_exists('WooCommerce')) {
  add_action('admin_notices', static function () {
    echo '<div class="notice notice-error"><p>Le pack e-commerce requiert WooCommerce.</p></div>';
  });
  // Ne rien charger de Woo ici si WooCommerce absent.
  return;
}

/**
 * Ici: hooks Woo, CPT/Taxo spécifiques shop si besoin,
 * enregistrement des blocs e-commerce, etc.
 *
 * NB: Tu ne fais AUCUN require de Woo dans "app/"; tout reste ici.
 */
