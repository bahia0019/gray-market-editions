<?php

// Add Cloudinary images array to order upon checkout
add_action('woocommerce_checkout_create_order_line_item', function ($item, $cart_item_key, $values, $order) {
    if (!isset($values['cloudinary_image_urls'])) {
        return;
    }

    $item->add_meta_data('cloudinary_image_urls', $values['cloudinary_image_urls']);
}, 10, 4);
