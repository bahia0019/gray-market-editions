<?php

// Append the Informational Tabs
add_filter('woocommerce_product_tabs', function ( $array ) {
	global $product;
	$product_name = $product->get_name(); // returns 'Exhibition Prints', 'Posters', etc...
	$option_products = [ 'Exhibition Prints', 'Outdoor/Vinyl Prints' ];

	if ( ( 'Exhibition Prints' === $product_name ) ) {
		$options = [ [ 'paper', 'Paper Styles' ], [ 'ink', 'Ink Styles' ], [ 'frame', 'Frame Styles' ], [ 'frame_rental', 'Frame Rental Info' ], [ 'frame_options', 'Additional Options' ] ];
	} else if ( 'Outdoor/Vinyl Prints' === $product_name ) {
		$options = [ [ 'vinyl', 'Vinyl Styles' ], [ 'grommet', 'Grommet Info' ], [ 'hem', 'Hems Info' ] ];
	}

	$order = 1;
	$tabs  = [];

	foreach ( $options as $option ) {
		$tabs[ $option[0] ] = [
			'title'    => ucfirst( $option[1] ),
			'priority' => $order++,
			'callback' => function ( $tab, $context ) {
				require GME_TEMPLATES_PATH . 'tab.php';
			},
		];
	}

	return array_merge( $array, $tabs );

});

function remove_additional_tabs( $tabs ) {
	unset( $tabs['description'] );
	unset( $tabs['additional_information'] );
	unset( $tabs['reviews'] );
	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'remove_additional_tabs' );
