<?php

// Validate item quantity being add to cart
add_filter('woocommerce_add_to_cart_validation', function ($is_valid, $product_id, $quantity) {
    $is_valid = (int) $quantity % 6 === 0 ? true : false;

    return $is_valid;
}, 10, 3);

// Display Cloudinary images below each cart item
add_action('woocommerce_after_cart_item_name', function ($cart_item, $cart_item_key) {
    if (!isset($cart_item['cloudinary_image_urls'][0])) {
        var_dump('No Cloudinary images found. Please address issue.');

        return;
    }

    $image_urls = $cart_item['cloudinary_image_urls'];

    require BB_TEMPLATES_PATH . 'cloudinary-mini-grid.php';
}, 10, 2);
