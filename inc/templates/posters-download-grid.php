<div class="poster-grid-wrapper">
	<p>Don't have a poster design? Use one of our Photoshop templates:</p>
	<div class="poster-grid">
		<?php
		if ( have_rows( 'poster_templates' ) ) :
			while ( have_rows( 'poster_templates' ) ) :
				the_row();
				$thumb = get_sub_field( 'poster_thumb' );
				$file  = get_sub_field( 'poster_file' );
				?>
				<a href="<?php echo $file['url']; ?>" download class="poster-grid_item">
					<?php
					if ( $thumb ) {
						echo wp_get_attachment_image( $thumb['ID'], 'medium', false, array( 'alt' => $thumb['alt'] ) );
					}
					?>
					<p>Download</p>
				</a>
			<?php endwhile; ?>
		<?php endif; ?>	
	</div>
</div>
