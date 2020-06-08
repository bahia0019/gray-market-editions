<?php

// Display GME images below each product in customer front-end 'my-account' page
add_filter('woocommerce_display_item_meta', function ($html, $item, $args) {
    $gme_image_data = $item->get_meta('gme_image_data');
}, 10, 3);
