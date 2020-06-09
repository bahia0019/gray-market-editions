<?php

// Adjust Exhibition Prints quantity based on number of images
add_filter('woocommerce_add_to_cart_quantity', function ($quantity, $product_id) {
    $product = wc_get_product($product_id);

    if ($product->get_name() !== 'Exhibition Prints') {
        return $quantity;
    }

    $gme_quantity = isset($_POST['gme_exhibition_quantity'])
        ? (int) $_POST['gme_exhibition_quantity']
        : 0;

    return $gme_quantity;
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
    echo '<input type="hidden" name="gme_exhibition_quantity" v-model="exhibitionQuantity" />';
});

// Append GME image data to cart item during 'add to cart'
add_filter('woocommerce_add_cart_item_data', function ($cart_item_data, $product_id, $variation_id, $quantity) {
    if (empty($_POST['gme_image_data'])) {
        return;
    }

    $cart_item_data['gme_image_data'] = json_decode(stripslashes($_POST['gme_image_data']), true);

    return $cart_item_data;
}, 10, 4);
