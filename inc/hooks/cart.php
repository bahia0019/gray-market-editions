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

// If editions prints, replace quantity input with quantity as number
add_filter('woocommerce_cart_item_quantity', function ($product_quantity, $cart_item_key, $cart_item) {
    $print_types = ['Exhibition Prints', 'Outdoor/Vinyl Prints'];

    if (!in_array($cart_item['data']->get_name(), $print_types)) {
        return $product_quantity;
    }

    return '<span>' . $cart_item['quantity'] . '</span>';
}, 10, 3);

// Display GME images below each cart item
add_action('woocommerce_after_cart_item_name', function ($cart_item, $cart_item_key) {
    if (!isset(['gme_image_data'][0])) {
        return;
    }

    $gme_image_data = $cart_item['gme_image_data'];

    require GME_TEMPLATES_PATH . 'gme-mini-grid.php';
}, 10, 2);
