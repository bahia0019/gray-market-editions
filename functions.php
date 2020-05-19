<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Helper paths
define('BB_TEMPLATES_PATH', get_stylesheet_directory() . '/inc/templates/');

require_once get_stylesheet_directory() . '/inc/wp-enqueue.php';
require_once get_stylesheet_directory() . '/inc/hooks/cart.php';
require_once get_stylesheet_directory() . '/inc/hooks/checkout.php';
require_once get_stylesheet_directory() . '/inc/hooks/vue-wraps.php';
require_once get_stylesheet_directory() . '/inc/hooks/my-account.php';
require_once get_stylesheet_directory() . '/inc/hooks/admin-order.php';
require_once get_stylesheet_directory() . '/inc/hooks/single-product.php';

// Tab template.
// require_once get_stylesheet_directory() . '/templates/framing-tab.php';



function gme_tab( $tab ) { ?>

	<h2><?php echo esc_html( $tab ); ?> Tab</h2>

	<?php
	if ( have_rows( $tab . '_repeater' ) ) : ?>

			<?php
			while ( have_rows( $tab . '_repeater' ) ) :

				the_row();

				$title   = get_sub_field( $tab . '_title' );
				$content = get_sub_field( $tab . '_text', false, false );
				$image   = get_sub_field( $tab . '_image' );
				?>
			<div style="border-bottom:1px solid rgba(0, 0, 0, 0.05); padding-bottom: 1em; margin-bottom: 2em;">

				<?php if ( $title ) : ?>
					<header>
						<h4><?php echo esc_html( $title ); ?></h4>
					</header>
				<?php endif; ?>

				<div>

					<figure style="float:right;">	
						<?php echo wp_get_attachment_image( $image, 'thumbnail', false, array( 'alt' => $image['alt'] ) ); ?>
					</figure>

					<?php echo esc_html( $content ); ?>
					<div style="clear:both;"></div>

				</div>

			</div>
			<?php endwhile; ?>

	<?php endif; ?>

<?php
	}
