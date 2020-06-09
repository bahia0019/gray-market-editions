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
