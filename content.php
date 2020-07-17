<?php
/**
 * Template used to display post content.
 *
 * @package storefront
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<hr class="wp-block-separator">
	<div class="wp-block-columns">
	<div class="wp-block-column" style="width:33%; flex-grow:initial; flex-basis:initial;">
			<figure class="wp-block-image size-large">
				<?php the_post_thumbnail('large_square'); ?>
			</figure>
		</div>
		<div class="wp-block-column" style="width:66%; flex-grow:initial; flex-basis:initial;">
			<div style="display:flex;flex-direction:column;justify-content:center;height:100%;">
				<a href="<?php the_permalink(); ?>"><h2><?php the_title(); ?></h2></a>
				<?php the_excerpt(); ?>
				<a href="<?php the_permalink(); ?>" style="border-radius:9px">Learn More</a>
			</div>
		</div>
	</div>
</article><!-- #post-## -->
