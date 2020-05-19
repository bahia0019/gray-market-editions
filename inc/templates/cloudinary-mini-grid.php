<div class="cloudinary-mini-grid">
    <p class="cloudinary-mini-grid__title">Cloudinary Images</p>
    <div class="cloudinary-mini-grid__grid">
        <?php foreach ($image_urls as $image_url): ?>
            <img class="cloudinary-mini-grid__image" src="<?php echo esc_url($image_url); ?>" alt="image" />
        <?php endforeach; ?>
    </div>
</div>
