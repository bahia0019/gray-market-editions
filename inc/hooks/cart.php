<?php

// Delete GME images if cart item is removed
add_action('woocommerce_remove_cart_item', function ($cart_item_key) {
    if (!$cart_data = WC()->cart->get_cart_item($cart_item_key)) {
        return;
    };

    $gme_image_data = isset($cart_data['gme_image_data'])
        ? $cart_data['gme_image_data']
        : [];

    foreach ($gme_image_data as $item) {
        wp_delete_attachment($item['attachment_id'], true);
    }
});

// If Exhibition Prints, replace quantity input with quantity as number
add_filter('woocommerce_cart_item_quantity', function ($product_quantity, $cart_item_key, $cart_item) {
    if ($cart_item['data']->get_name() !== 'Exhibition Prints') {
        return $product_quantity;
    }

    return '<span>' . $cart_item['quantity'] . '</span>';
}, 10, 3);
