<h2>Framing Tab</h2>

<?php
if ( have_rows( 'frame_repeater' ) ) : ?>

		<?php
		while ( have_rows( 'frame_repeater' ) ) :

			the_row();

			$title   = get_sub_field( 'frame_title' );
			$image   = get_sub_field( 'frame_image' );
			$content = get_sub_field( 'frame_text', false, false );
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
