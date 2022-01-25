<?php

namespace App;

/**
 * Add <body> classes
 */
add_filter(
	'body_class',
	function ( array $classes ) {
		/** Add page slug if it doesn't exist */
		if ( is_single() || is_page() && ! is_front_page() ) {
			if ( ! in_array( basename( get_permalink() ), $classes ) ) {
					$classes[] = basename( get_permalink() );
			}
		}

		/** Add class if sidebar is active */
		if ( display_sidebar() ) {
			$classes[] = 'sidebar-primary';
		}

		/** Clean up class names for custom templates */
		$classes = array_map(
			function ( $class ) {
				return preg_replace( array( '/-blade(-php)?$/', '/^page-template-views/' ), '', $class );
			},
			$classes
		);

		return array_filter( $classes );
	}
);

/**
 * Add "… Continued" to the excerpt
 */
add_filter(
	'excerpt_more',
	function () {
		return ' &hellip; <a href="' . get_permalink() . '">' . __( 'Continued', 'sage' ) . '</a>';
	}
);

/**
 * Template Hierarchy should search for .blade.php files
 */
collect(
	array(
		'index',
		'404',
		'archive',
		'author',
		'category',
		'tag',
		'taxonomy',
		'date',
		'home',
		'frontpage',
		'page',
		'paged',
		'search',
		'single',
		'singular',
		'attachment',
		'embed',
	)
)->map(
	function ( $type ) {
		add_filter( "{$type}_template_hierarchy", __NAMESPACE__ . '\\filter_templates' );
	}
);

/**
 * Render page using Blade
 */
add_filter(
	'template_include',
	function ( $template ) {
		collect( array( 'get_header', 'wp_head' ) )->each(
			function ( $tag ) {
				ob_start();
				do_action( $tag );
				$output = ob_get_clean();
				remove_all_actions( $tag );
				add_action(
					$tag,
					function () use ( $output ) {
						echo $output;
					}
				);
			}
		);
		$data = collect( get_body_class() )->reduce(
			function ( $data, $class ) use ( $template ) {
				return apply_filters( "sage/template/{$class}/data", $data, $template );
			},
			array()
		);
		if ( $template ) {
			echo template( $template, $data );
			return get_stylesheet_directory() . '/index.php';
		}
		return $template;
	},
	PHP_INT_MAX
);

/**
 * Render comments.blade.php
 */
add_filter(
	'comments_template',
	function ( $comments_template ) {
		$comments_template = str_replace(
			array( get_stylesheet_directory(), get_template_directory() ),
			'',
			$comments_template
		);

		$data = collect( get_body_class() )->reduce(
			function ( $data, $class ) use ( $comments_template ) {
				return apply_filters( "sage/template/{$class}/data", $data, $comments_template );
			},
			array()
		);

		$theme_template = locate_template( array( "views/{$comments_template}", $comments_template ) );

		if ( $theme_template ) {
			echo template( $theme_template, $data );
			return get_stylesheet_directory() . '/index.php';
		}

		return $comments_template;
	},
	100
);

/**
 * Limit the Gutenberg blocks that are available to the content editors
 */
add_filter(
	'allowed_block_types_all',
	function ( $allowed_block_types ) {
		$registered_blocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();

		$custom_blocks = array_filter(
			$registered_blocks,
			function( $block ) {
				return strpos( $block->name, 'acf/' ) > -1 && $block->name != 'acf/template';
			}
		);

		$custom_blocks = array_map(
			function( $block ) {
				return $block->name;
			},
			$custom_blocks
		);
		$other_blocks  = json_decode( file_get_contents( get_theme_root() . '/sage/config/allowed_blocks.json' ) );

		return array_merge( $other_blocks, array_values( $custom_blocks ) );
	}
);

add_filter(
	'block_editor_settings_all',
	function ( $settings ) {
		unset( $settings['styles'][0] );

		return $settings;
	}
);

add_filter(
	'render_block',
	function( $block_content, $block ) {
		$content = $block_content;

		if ( preg_match( '/core/', $block['blockName'] ) ) {
			$content  = '<div class="wp-core-block">';
			$content .= $block_content;
			$content .= '</div>';
		}

		preg_match( '/<h[1|2|3|4|5].*>(.*)<\/h[1|2|3|4|5]>/', $content, $matches );

		if ( ! empty( $matches ) ) {
			// Give headings an ID to so that sticky can target them
			$new_heading = preg_replace( '/<(h.*?)>/', '<$1 id="' . sanitize_title_with_dashes( $matches[1] ) . '">', $matches[0], 1 );
			$content     = str_replace( $matches[0], $new_heading, $content );
		}

		return $content;
	},
	10,
	2
);

// ************* Remove default Posts type since no blog *************
add_action( 'admin_menu', function () {
	remove_menu_page( 'edit.php' );
});
add_action( 'admin_bar_menu', function ( $wp_admin_bar ) {
	$wp_admin_bar->remove_node( 'new-post' );
}, 999 );
add_action( 'wp_dashboard_setup', function (){
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
}, 999 );
