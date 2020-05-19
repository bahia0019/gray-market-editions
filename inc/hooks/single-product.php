<?php

// Control min/max when adding product to cart
add_filter('woocommerce_available_variation', function ($args, $product) {
    $args['min_qty'] = 6;
    $args['max_qty'] = 60;

    return $args;
}, 10, 2);

add_filter('woocommerce_quantity_input_args', function ($args, $product) {
    $args['step'] = 6;
    $args['min_value'] = 6;
    $args['max_value'] = 60;

    return $args;
}, 10, 2);

// Add hidden input field to bind to Vue data for capturing Cloudinary image URL's to send along with $_POST data
add_action('woocommerce_after_variations_form', function () {
    echo '<input type="hidden" name="cloudinary_image_urls" v-model="cloudinaryUrls" />';
});

// Append Cloudinary image to cart item during 'add to cart'
add_filter('woocommerce_add_cart_item_data', function ($cart_item_data, $product_id, $variation_id, $quantity) {
    if (!isset($_POST['cloudinary_image_urls'])) {
        return;
    }

    $cart_item_data['cloudinary_image_urls'] = explode(',', $_POST['cloudinary_image_urls']);

    return $cart_item_data;
}, 10, 4);

// Append the Informational Tabs
add_filter('woocommerce_product_tabs', function ($array) {
    $tab = 'frame';
    $framing = [
        'framing' => [
            'title'    => 'Framing Options',
            'priority' => 1,
            'callback' => gme_tab($tab),
        ],
        'paper' => [
            'title'    => 'Paper Options',
            'priority' => 2,
            'callback' => function () {
                require_once BB_TEMPLATES_PATH . 'paper-tab.php';
            },
        ],
    ];

    return array_merge($array, $framing);
});

// Add Cloudinary widgets scripts
add_action('woocommerce_after_single_product', function () {
    require_once BB_TEMPLATES_PATH . 'cloudinary-widget.php';
});
