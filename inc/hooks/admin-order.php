<?php

// Display Cloudinary images below each item in order
add_action('woocommerce_before_order_itemmeta', function ($item_id, $item, $product) {
    if (!$image_urls = $item->get_meta('cloudinary_image_urls')) {
        return;
    }

    require BB_TEMPLATES_PATH . 'cloudinary-mini-grid.php';
}, 10, 3);
