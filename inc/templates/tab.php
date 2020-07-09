<h2 data-info-tab="<?php the_field( $tab . '_options_link' ); ?>"><?php echo esc_html( $context['title'] ); ?></h2>

	<?php
	if ( !have_rows( $tab . '_repeater' ) ) {
			the_field('edition_pricing_title');
			the_field('edition_pricing_text');
		} else {
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
					<?php if ( $image ) { ?>
						<figure style="float:right; margin-left:10px; margin-bottom:10px;">	
							<?php echo wp_get_attachment_image( $image, 'medium', false ); ?>
						</figure>
					<?php } ?> 

					<?php echo $content; ?>
					<div style="clear:both;"></div>

				</div>

			</div>
			<?php endwhile; ?>

	<?php 
	}
