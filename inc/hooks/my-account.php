<?php

// Display Cloudinary images below each product in customer front-end 'my-account' page
add_filter('woocommerce_display_item_meta', function ($html, $item, $args) {
    $image_urls = $item->get_meta('cloudinary_image_urls'); ?>
    <div style="margin-top: 1rem;">
        <p style="font-weight: bold; color: gray; margin-bottom: 0.1rem;">Cloudinary Images</p>
        <div style="display: grid; grid-gap: 1rem; grid-template-columns: repeat(4, 1fr);">
            <?php foreach ($image_urls as $image_url): ?>
                <img style="width: 100%;" src="<?php echo $image_url; ?>" alt="image" />
            <?php endforeach; ?>
        </div>
    </div>
<?php
}, 10, 3);
