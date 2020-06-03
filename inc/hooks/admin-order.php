<?php

// Display GME images below each item in order
add_action('woocommerce_before_order_itemmeta', function ($item_id, $item, $product) {
    if (!$image_urls = $item->get_meta('gme_image_urls')) {
        return;
    }

    require BB_TEMPLATES_PATH . 'gme-image-mini-grid.php';
}, 10, 3);
