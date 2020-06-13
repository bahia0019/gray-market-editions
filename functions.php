<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

require_once __DIR__ . '/vendor/autoload.php';

// Helper paths
define('GME_UPLOADS_URL', wp_upload_dir()['baseurl'] . '/gme_images/');
define('GME_UPLOADS_PATH', wp_upload_dir()['basedir'] . '/gme_images/');
define('GME_TEMPLATES_PATH', get_stylesheet_directory() . '/inc/templates/');

// Make custom directory
wp_mkdir_p(GME_UPLOADS_PATH);

require_once get_stylesheet_directory() . '/woocommerce/function-overrides.php';

require_once get_stylesheet_directory() . '/inc/gme-functions.php';
require_once get_stylesheet_directory() . '/inc/wp-enqueue.php';
require_once get_stylesheet_directory() . '/inc/hooks/acf.php';
require_once get_stylesheet_directory() . '/inc/hooks/cart.php';
require_once get_stylesheet_directory() . '/inc/hooks/images.php';
require_once get_stylesheet_directory() . '/inc/hooks/checkout.php';
require_once get_stylesheet_directory() . '/inc/hooks/vue-wraps.php';
require_once get_stylesheet_directory() . '/inc/hooks/my-account.php';
require_once get_stylesheet_directory() . '/inc/hooks/admin-order.php';
require_once get_stylesheet_directory() . '/inc/hooks/admin-media.php';
require_once get_stylesheet_directory() . '/inc/ajax/images.php';

require_once get_stylesheet_directory() . '/inc/hooks/single-product/single-product.php';
require_once get_stylesheet_directory() . '/inc/hooks/single-product/posters.php';
require_once get_stylesheet_directory() . '/inc/hooks/single-product/vinyl.php';
require_once get_stylesheet_directory() . '/inc/hooks/single-product/exhibition-prints.php';
require_once get_stylesheet_directory() . '/inc/hooks/single-product/woocommerce-tabs.php';
