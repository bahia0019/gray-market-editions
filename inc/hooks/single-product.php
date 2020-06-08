<?php

// Control min/max when adding product to cart
add_filter('woocommerce_available_variation', function ($args, $product) {
    $args['min_qty'] = 6;

    return $args;
}, 10, 2);

add_filter('woocommerce_quantity_input_args', function ($args, $product) {
    $args['step'] = 6;
    $args['min_value'] = 6;

    return $args;
}, 10, 2);

// Conditionally Add Vue ref around 'add to cart'
add_action('woocommerce_before_add_to_cart_button', function () {
    global $product;

    if ($product->get_name() !== 'Exhibition Prints') {
        return;
    }

    echo '<div ref="addToCart">';
});

add_action('woocommerce_after_add_to_cart_button', function () {
    global $product;

    if ($product->get_name() !== 'Exhibition Prints') {
        return;
    }
    
    echo '</div>';
});

// Add hidden input field to bind to Vue data for capturing GME image URL's to send along with $_POST data
add_action('woocommerce_after_variations_form', function () {
    echo '<input type="hidden" name="gme_image_data" v-model="finData" />';
});

// Append GME image data to cart item during 'add to cart'
add_filter('woocommerce_add_cart_item_data', function ($cart_item_data, $product_id, $variation_id, $quantity) {
    if (empty($_POST['gme_image_data'])) {
        return;
    }

    $cart_item_data['gme_image_data'] = json_decode(stripslashes($_POST['gme_image_data']), true);

    return $cart_item_data;
}, 10, 4);

// Append the Informational Tabs
add_filter('woocommerce_product_tabs', function ($array) {
    $options = ['frame', 'paper', 'ink'];
    $order   = 1;
    $tabs    = [];

    foreach ($options as $option) {
        $tabs[$option] = [
            'title'    => ucfirst($option) . ' Options',
            'priority' => $order++,
            'callback' => function ($tab, $context) {
                require GME_TEMPLATES_PATH . 'tab.php';
            },
        ];
    }

    return array_merge($array, $tabs);
});

// Remove the variable price range.
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
