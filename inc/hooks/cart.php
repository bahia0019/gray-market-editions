<?php

// Validate item quantity being add to cart
add_filter('woocommerce_add_to_cart_validation', function ($is_valid, $product_id, $quantity) {
    $product_name = wc_get_product($product_id)->get_name();

    // Posters quantity
    if ($product_name === 'Posters') {
        $is_valid = (int) $quantity % 6 === 0 ? true : false;
    }

    return $is_valid;
}, 10, 3);

// Display GME images below each cart item
add_action('woocommerce_after_cart_item_name', function ($cart_item, $cart_item_key) {
    if (!isset($cart_item['gme_image_data'][0])) {
        return;
    }

    $gme_image_data = $cart_item['gme_image_data'];

    require GME_TEMPLATES_PATH . 'gme-mini-grid.php';
}, 10, 2);

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
