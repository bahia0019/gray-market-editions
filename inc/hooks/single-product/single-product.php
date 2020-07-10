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

// Add hidden input field to bind to Vue data for capturing GME image URL's to send along with $_POST data
add_action('woocommerce_after_variations_form', function () {
    echo '<input type="hidden" name="gme_image_data" v-model="finData" />';
    echo '<input type="hidden" name="gme_editions_quantity" v-model="editionsQuantity" />';
});

add_action('woocommerce_composite_after_components', function () {
    echo '<input type="hidden" name="gme_image_data" v-model="finData" />';
    echo '<input type="hidden" name="gme_editions_quantity" v-model="editionsQuantity" />';
});

// Adjust editions prints quantity based on number of images
add_filter('woocommerce_add_to_cart_quantity', function ($quantity, $product_id) {
    $product = wc_get_product($product_id);

    $print_types = ['Exhibition Prints', 'Outdoor/Vinyl Prints'];

    if (!in_array($product->get_name(), $print_types)) {
        return $quantity;
    }

    $gme_quantity = isset($_POST['gme_editions_quantity'])
        ? (int) $_POST['gme_editions_quantity']
        : 0;

    return $gme_quantity;
}, 10, 2);

// Hide woo input field and use Vue binding to handle quantity based on number of images
add_action('woocommerce_before_quantity_input_field', function () {
    global $product;

    $print_types = ['Exhibition Prints', 'Outdoor/Vinyl Prints'];

    if (!is_product() || !in_array($product->get_name(), $print_types)) {
        return;
    }

    echo '<editions-quantity></editions-quantity>';
    echo '<div style="display: none;">';
});

add_action('woocommerce_after_quantity_input_field', function () {
    global $product;

    $print_types = ['Exhibition Prints', 'Outdoor/Vinyl Prints'];

    if (!is_product() || !in_array($product->get_name(), $print_types)) {
        return;
    }

    echo '</div>';
});

// Append GME image data to cart item during 'add to cart'
add_filter('woocommerce_add_cart_item_data', function ($cart_item_data, $product_id, $variation_id, $quantity) {
    if (empty($_POST['gme_image_data'])) {
        return;
    }

    $cart_item_data['gme_image_data'] = json_decode(stripslashes($_POST['gme_image_data']), true);

    return $cart_item_data;
}, 10, 4);

// Remove the variable price range.
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);

// Remove related products.
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
