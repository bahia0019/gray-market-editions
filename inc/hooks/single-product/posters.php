<?php

// Control min/max when adding product to cart on variations
add_filter('woocommerce_available_variation', function ($args, $product) {
    // Posters quantity
    if ($product->get_name() === 'Posters') {
        $args['step'] = 6;
        $args['min_qty'] = 6;
        $args['min_value'] = 6;
    }

    return $args;
}, 10, 2);

// Control min/max when adding product to cart
add_filter('woocommerce_quantity_input_args', function ($args, $product) {
    // Posters quantity
    if ($product->get_name() === 'Posters') {
        $args['step'] = 6;
        $args['min_value'] = 6;
    }

    return $args;
}, 10, 2);
