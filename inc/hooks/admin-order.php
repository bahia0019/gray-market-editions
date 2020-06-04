<?php

// Display GME images below each item in order
add_action('woocommerce_before_order_itemmeta', function ($item_id, $item, $product) {
    if (!$gme_image_data = $item->get_meta('gme_image_data')) {
        return;
    }

    require GME_TEMPLATES_PATH . 'gme-image-mini-grid.php';
}, 10, 3);
