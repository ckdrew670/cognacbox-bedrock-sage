<?php

namespace App;

/**
 * Theme customizer
 */
add_action('customize_register', function (\WP_Customize_Manager $wp_customize) {
		// Add postMessage support
		$wp_customize->get_setting('blogname')->transport = 'postMessage';
		$wp_customize->selective_refresh->add_partial('blogname', [
				'selector' => '.brand',
				'render_callback' => function () {
						bloginfo('name');
				}
		]);
});

/**
 * Customizer JS
 */
add_action('customize_preview_init', function () {
		wp_enqueue_script('sage/customizer.js', asset_path('scripts/customizer.js'), ['customize-preview'], null, true);
});


add_action('admin_head', function() {
	echo '<style>
		.wp-block {
			max-width: calc(100% - 4rem);
		}
	</style>';
});

add_action( 'enqueue_block_editor_assets', function() {
	wp_enqueue_script('lazyload/polyfill', get_template_directory_uri() . '/assets/scripts/polyfills/vanilla-lazyload.min.js', [], null, false);
	wp_enqueue_script('sage/main.js', asset_path('scripts/main.js'), ['jquery'], null, true);
	wp_enqueue_style('sage/admin.css', asset_path('styles/admin.css'), false, null);
} );