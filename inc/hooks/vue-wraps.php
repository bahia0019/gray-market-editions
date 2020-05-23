<?php

// Vue instance around single product
add_action('woocommerce_before_single_product_summary', function () {
    echo '<div id="vue-app">';
}, 5);

add_action('woocommerce_after_single_product_summary', function () {
    echo '</div>';
}, 5);

// Vue instance around cart
add_action('woocommerce_before_cart', function () {
    echo '<div id="vue-app">';
});

add_action('woocommerce_after_cart', function () {
    echo '</div>';
});
