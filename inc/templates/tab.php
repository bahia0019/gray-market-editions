<h2><?php echo esc_html( $context['title'] ); ?></h2>
	
	<?php
	echo $tab;
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
						<?php echo wp_get_attachment_image( $image, 'thumbnail', false ); ?>
					</figure>

					<?php echo esc_html( $content ); ?>
					<div style="clear:both;"></div>

				</div>

			</div>
			<?php endwhile; ?>

	<?php endif; ?>
