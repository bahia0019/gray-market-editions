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

// Add hidden input field to capture Cloudinary image URL's for when $_POST action
add_action('woocommerce_after_variations_form', function () {
    // echo '<input type="hidden" name="cloudinary_image_urls" value="' . json_encode(["blah" => "dee"]) . '" />';
});

// Append Cloudinary image to cart itmem during 'add to cart'
add_filter('woocommerce_add_cart_item_data', function ($cart_item_data, $product_id, $variation_id, $quantity) {
    $cart_item_data['cloudinary_image_urls'] = [
        'https://i.picsum.photos/id/' . random_int(1, 20) . '/600/400.jpg',
        'https://i.picsum.photos/id/' . random_int(1, 20) . '/600/400.jpg',
        'https://i.picsum.photos/id/' . random_int(1, 20) . '/600/400.jpg',
        'https://i.picsum.photos/id/' . random_int(1, 20) . '/600/400.jpg',
        'https://i.picsum.photos/id/' . random_int(1, 20) . '/600/400.jpg',
        'https://i.picsum.photos/id/' . random_int(1, 20) . '/600/400.jpg',
        'https://i.picsum.photos/id/' . random_int(1, 20) . '/600/400.jpg',
    ];

    return $cart_item_data;
}, 10, 4);

// Append 'Framing' Tab
add_filter('woocommerce_product_tabs', function ($array) {
    $framing = [
        'framing' => [
            'title'    => 'Framing Options',
            'priority' => 5,
            'callback' => function () {
                require_once BB_TEMPLATES_PATH . 'framing-tab.php';
            },
		],
		'paper' => [
            'title'    => 'Paper Options',
            'priority' => 5,
            'callback' => function () {
                require_once BB_TEMPLATES_PATH . 'paper-tab.php';
            },
        ],
    ];

    return array_merge($array, $framing);
});
