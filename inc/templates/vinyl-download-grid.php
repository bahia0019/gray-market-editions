<div class="template-grid-wrapper">
	<p>Don't have a vinyl design? Use one of our Photoshop templates:</p>
	<div class="template-grid">
		<?php
		if ( have_rows( 'vinyl_templates' ) ) :
			while ( have_rows( 'vinyl_templates' ) ) :
				the_row();
				$thumb = get_sub_field( 'template_thumb' );
				$file  = get_sub_field( 'template_file' );
				$name  = get_sub_field( 'template_name' );
				?>
				<a href="<?php echo $file['url']; ?>" download class="template-grid_item">
					<?php
					if ( $thumb ) {
						echo wp_get_attachment_image( $thumb['ID'], 'medium', false, array( 'alt' => $thumb['alt'] ) );
					}
					?>
										<p><?php echo $name; ?></p>
					<p>Download</p>
				</a>
			<?php endwhile; ?>
		<?php endif; ?>	
	</div>
</div>
