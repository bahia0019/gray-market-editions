<?php

// Display GME images below each item in order
add_action('woocommerce_before_order_itemmeta', function ($item_id, $item, $product) {
    if (!$gme_image_data = $item->get_meta('gme_image_data')) {
        return;
    }

    $order_id = $item->get_data()['order_id'];

    $order = new WC_Order($order_id);

    $zip_file_name = !empty($order->get_billing_last_name()) ?
        strtolower($order->get_billing_last_name()) . '__order_id_' . $order_id
        : 'order_id_' . $order_id;

    require GME_TEMPLATES_PATH . 'admin-gme-mini-grid.php';
}, 10, 3);
