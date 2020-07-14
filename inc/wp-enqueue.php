<?php

add_action('wp_enqueue_scripts', function () {
    $version = md5_file(get_stylesheet_directory() . '/mix-manifest.json');

    wp_enqueue_style('woo_photo', get_stylesheet_directory_uri() . '/dist/css/app.css', ['storefront-style', 'storefront-child-style', 'storefront-woocommerce-style'], $version);

    if (is_product() || is_cart() && !WC()->cart->is_empty()) {
        global $product;

		wp_enqueue_script('woo_photo', get_stylesheet_directory_uri() . '/dist/js/app.js', ['jquery'], $version, true);
		wp_enqueue_script('scroll_magic', '//cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.7/ScrollMagic.min.js', null, '2.0.7', true );
		wp_enqueue_script('scroll_magic_indicators', '//cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.7/plugins/debug.addIndicators.min.js', null, '2.0.7', true );


        wp_localize_script('woo_photo', 'GME_DATA', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('gme_ajax_nonce'),
        ]);

        $need_vue = in_array($product, ['exhibition-prints', 'posters', 'outdoor-vinyl-prints']);

        wp_localize_script('woo_photo', 'GME_PRODUCT', [
            'type'    => $product,
            'needVue' => (bool) $need_vue,
        ]);
    }
});

add_action('admin_enqueue_scripts', function () {
    if (get_post_type() === 'shop_order') {
        $version = md5_file(get_stylesheet_directory() . '/mix-manifest.json');

        wp_enqueue_style('woo_photo', get_stylesheet_directory_uri() . '/dist/css/app.css', [], $version);
        wp_enqueue_script('woo_admin_photo', get_stylesheet_directory_uri() . '/dist/js/admin-app.js', ['jquery'], $version, true);
    }
});
