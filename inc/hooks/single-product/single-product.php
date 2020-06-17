<?php

// Remove breadcrumbs from single product
add_action('wp', function () {
    if (!is_product()) {
        return;
    }

    remove_action('storefront_before_content', 'woocommerce_breadcrumb', 10);
});

// Hide product meta
add_action('woocommerce_product_meta_start', function () {
    if (!is_product()) {
        return;
    }

    echo '<div style="display: none;">';
});

add_action('woocommerce_product_meta_end', function () {
    if (!is_product()) {
        return;
    }

    echo '</div>';
});

// Remove the variable price range.
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);

// Conditionally Add Vue ref around 'add to cart'
add_action('woocommerce_before_add_to_cart_button', function () {
    global $product;

    $allowed = ['Exhibition Prints', 'Posters', 'Outdoor/Vinyl Prints'];

    if (!in_array($product->get_name(), $allowed)) {
        return;
    }

    echo '<div ref="addToCart">';
});

add_action('woocommerce_after_add_to_cart_button', function () {
    global $product;

    $allowed = ['Exhibition Prints', 'Posters', 'Outdoor/Vinyl Prints'];

    if (!in_array($product->get_name(), $allowed)) {
        return;
    }

    echo '</div>';
});
