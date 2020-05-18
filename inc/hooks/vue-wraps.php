<?php

// Vue instance around single product
add_action('woocommerce_before_single_product_summary', function () {
    echo '<div id="vue-single-product">';
}, 5);

add_action('woocommerce_after_single_product_summary', function () {
    echo '</div>';
}, 5);

// Vue instance around cart
add_action('woocommerce_before_cart', function () {
    echo '<div id="vue-cart">';
});

add_action('woocommerce_after_cart', function () {
    echo '</div>';
});

// Vue method around buy button quantity input
add_action('woocommerce_before_quantity_input_field', function () {
    if (!is_singular('product')) {
        return;
    }

    echo '<div @change="setQuantity">';
});

add_action('woocommerce_after_quantity_input_field', function () {
    if (!is_singular('product')) {
        return;
    }

    echo '</div>';
});

// Vue method around variations select dropdowns
add_action('woocommerce_before_variations_form', function () {
    if (!is_singular('product')) {
        return;
    }

    echo '<div @change="setVariations">';
});

add_action('woocommerce_after_variations_form', function () {
    if (!is_singular('product')) {
        return;
    }

    echo '</div>';
});
