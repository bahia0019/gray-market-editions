<?php

// Add GME images array to order upon checkout
add_action('woocommerce_checkout_create_order_line_item', function ($item, $cart_item_key, $values, $order) {
    if (!isset($values['gme_image_data'])) {
        return;
    }

    $gme_items = $values['gme_image_data'];

    // Add images transformations to attachment
    foreach ($gme_items as $gme_item) {
        update_post_meta(
            $gme_item['attachment_id'], 'gme_image_transformations', $gme_item['transformations']
        );
    }

    $item->add_meta_data('gme_image_data', $gme_items);
}, 10, 4);
