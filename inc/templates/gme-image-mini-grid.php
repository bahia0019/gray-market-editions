<div class="gme-image-mini-grid">
    <p class="gme-image-mini-grid__title">Images</p>
    <div class="gme-image-mini-grid__grid">
        <?php foreach ($image_urls as $image_url): ?>
            <img class="gme-image-mini-grid__image" src="<?php echo esc_url($image_url); ?>" alt="image" />
        <?php endforeach; ?>
    </div>
</div>
