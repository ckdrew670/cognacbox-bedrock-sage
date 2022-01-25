<?php

namespace App;

use Roots\Sage\Container;
use Illuminate\Support\Collection;

/**
 * Get the sage container.
 *
 * @param string    $abstract
 * @param array     $parameters
 * @param Container $container
 * @return Container|mixed
 */
function sage( $abstract = null, $parameters = array(), Container $container = null ) {
	$container = $container ?: Container::getInstance();
	if ( ! $abstract ) {
		return $container;
	}
	return $container->bound( $abstract )
		? $container->makeWith( $abstract, $parameters )
		: $container->makeWith( "sage.{$abstract}", $parameters );
}

/**
 * Get / set the specified configuration value.
 *
 * If an array is passed as the key, we will assume you want to set an array of values.
 *
 * @param array|string $key
 * @param mixed        $default
 * @return mixed|\Roots\Sage\Config
 * @copyright Taylor Otwell
 * @link https://github.com/laravel/framework/blob/c0970285/src/Illuminate/Foundation/helpers.php#L254-L265
 */
function config( $key = null, $default = null ) {
	if ( is_null( $key ) ) {
		return sage( 'config' );
	}
	if ( is_array( $key ) ) {
		return sage( 'config' )->set( $key );
	}
	return sage( 'config' )->get( $key, $default );
}

/**
 * @param string $file
 * @param array  $data
 * @return string
 */
function template( $file, $data = array() ) {
	return sage( 'blade' )->render( $file, $data );
}

/**
 * Retrieve path to a compiled blade view
 *
 * @param $file
 * @param array $data
 * @return string
 */
function template_path( $file, $data = array() ) {
	return sage( 'blade' )->compiledPath( $file, $data );
}

/**
 * @param $asset
 * @return string
 */
function asset_path( $asset ) {
	return sage( 'assets' )->getUri( $asset );
}

/**
 * @param string|string[] $templates Possible template files
 * @return array
 */
function filter_templates( $templates ) {
	$paths         = apply_filters(
		'sage/filter_templates/paths',
		array(
			'views',
			'resources/views',
		)
	);
	$paths_pattern = '#^(' . implode( '|', $paths ) . ')/#';

	return collect( $templates )
		->map(
			function ( $template ) use ( $paths_pattern ) {
				/** Remove .blade.php/.blade/.php from template names */
				$template = preg_replace( '#\.(blade\.?)?(php)?$#', '', ltrim( $template ) );

				/** Remove partial $paths from the beginning of template names */
				if ( strpos( $template, '/' ) ) {
					$template = preg_replace( $paths_pattern, '', $template );
				}

				return $template;
			}
		)
		->flatMap(
			function ( $template ) use ( $paths ) {
				return collect( $paths )
				->flatMap(
					function ( $path ) use ( $template ) {
						return array(
							"{$path}/{$template}.blade.php",
							"{$path}/{$template}.php",
						);
					}
				)
				->concat(
					array(
						"{$template}.blade.php",
						"{$template}.php",
					)
				);
			}
		)
		->filter()
		->unique()
		->all();
}

/**
 * @param string|string[] $templates Relative path to possible template files
 * @return string Location of the template
 */
function locate_template( $templates ) {
	return \locate_template( filter_templates( $templates ) );
}

/**
 * Determine whether to show the sidebar
 *
 * @return bool
 */
function display_sidebar() {
	static $display;
	isset( $display ) || $display = apply_filters( 'sage/display_sidebar', false );
	return $display;
}

/**
 * Simple function to pretty up our field partial includes.
 *
 * @param  mixed $partial
 * @return mixed
 */
function get_field_partial( $partial, $variables = array() ) : object {
	extract( $variables );

	$partial = str_replace( '.', '/', $partial );
	return include config( 'theme.dir' ) . "/app/fields/{$partial}.php";
}

/**
 * Get nav menu items by location
 *
 * @param $location The menu location id/name
 * @param $args the arguments passed into wp_get_nav_menu_items
 */
function get_nav_menu_items_by_location( $location, $args = array() ): array {
	// Get all locations
	$locations = get_nav_menu_locations();

	// Get object id by location
	if ( isset( $locations[ $location ] ) ) {
		$object = wp_get_nav_menu_object( $locations[ $location ] );

		// Get menu items by menu name
		$menu_items = wp_get_nav_menu_items( $object->name, $args );

		// Return menu post objects
		return array( 'items' => format_menu_items( $menu_items ) );
	} else {
		return array( 'items' => array() );
	}
}

/**
 * Returns arguments for the image pattern
 *
 * @param $config - {array}
 *          ['image_id] {int}
 *          ['attributes] {array} - HTML attributes to be added to <picture> element
 *          ['lazy_load] {bool} - whether to add lazy load functionality
 */
function responsive_image( array $config ) : array {
	$image_id  = $config['image_id'];
	$lazy_load = $config['lazy_load'];

	if ( empty( $image_id ) ) {
		return array();
	}

	$image     = wp_get_attachment_metadata( $image_id );
	$image_url = wp_get_attachment_image_url( $image_id, 'xl' );

	$sources = collect( $image['sizes'] )->map(
		function( $src ) use ( $image_id ) {
			$asset  = wp_prepare_attachment_for_js( $image_id );
			$folder = explode( $asset['filename'], $asset['url'] )[0]; // WordPress does not make it easy to get an images path

			$new_src = $folder . $src['file'];
			return array(
				'srcset' => $new_src,
				'mq'     => "(min-width: {$src['width']}px)",
				'width'  => $src['width'],
			);
		}
	)->sortByDesc( 'width' );

	return array_merge(
		array(
			'image'   => $image_url,
			// 'attributes' => $attributes,
			'alt'     => get_post_meta( $image_id, '_wp_attachment_image_alt', true ),
			'sources' => $sources,
		),
		$config
	);
}

/**
 * Format nav menu items to nest within their parents
 */
function format_menu_items( array $menu_items ) : array {
	$result = collect( $menu_items )
		->map(
			function( $item ) {
				return array(
					'id'       => $item->ID,
					'url'      => $item->url,
					'title'    => $item->title,
					'parent'   => (int) $item->menu_item_parent,
					'children' => array(),
				);
			}
		);

	$result = $result->map(
		function( $parent ) use ( $result ) {
			$parent['children'] = $result->filter(
				function( $child ) use ( $parent ) {
					return $parent['id'] === $child['parent'];
				}
			)->values()->all();

			return $parent;
		}
	)
		->filter(
			function( $item ) {
				return $item['parent'] === 0;
			}
		);

	return $result->all();
}

/**
 * Is the current site the main site within a multisite setup
 */
function isMainSite() : bool {
	return get_current_blog_id() === 1;
}

/**
 * Limit a string to the first 'n' number of words
 */

function limit_text( $text, $limit ) {
	if ( str_word_count( $text, 0 ) > $limit ) {
			$words = str_word_count( $text, 2 );
			$pos   = array_keys( $words );
			$text  = substr( $text, 0, $pos[ $limit ] ) . '...';
	}
	return $text;
}

/**
 * Rename Posts Menu item to Blog
 */

// add_action( 'admin_menu', function() {
// global $menu;
// global $submenu;
// $menu[5][0] = 'Blog';
// $submenu['edit.php'][5][0] = 'Blog';
// $submenu['edit.php'][10][0] = 'Add Blog Post';
// } );
// add_action( 'init', function() {
// global $wp_post_types;
// $labels = &$wp_post_types['post']->labels;
// $labels->name = 'Blog';
// $labels->singular_name = 'Blog Post';
// $labels->add_new = 'Add Blog Post';
// $labels->add_new_item = 'Add Blog Post';
// $labels->edit_item = 'Edit Blog Post';
// $labels->new_item = 'Blog Post';
// $labels->view_item = 'View Blog Post';
// $labels->search_items = 'Search Blog';
// $labels->not_found = 'No Blog Posts found';
// $labels->not_found_in_trash = 'No Blog Posts found in Trash';
// $labels->all_items = 'All Blog';
// $labels->menu_name = 'Blog';
// $labels->name_admin_bar = 'Blog';
// } );

/**
 * Remove default categories and tags taxonomies and menu items for these
 */

add_action(
	'init',
	function () {
		register_taxonomy( 'post_tag', array(), array( 'show_in_nav_menus' => false ) );
	}
);
add_action(
	'init',
	function () {
		register_taxonomy( 'category', array(), array( 'show_in_nav_menus' => false ) );
	}
);

// Remove menu item
add_action(
	'admin_menu',
	function () {
		remove_menu_page( 'edit-tags.php?taxonomy=post_tag' );
	}
);

function formatBytes( $size, $precision = 2 ) {
	$base     = log( $size, 1024 );
	$suffixes = array( '', 'kb', 'mb', 'gb' );
	return round( pow( 1024, $base - floor( $base ) ), $precision ) . $suffixes[ floor( $base ) ];
}

/*
=============================================
=            BREADCRUMBS			            =
=============================================*/
// reference: https://github.com/ahmadthedev/wp-breadcrumb-function/

function the_breadcrumb() {

	// Check if is front/home page, return
	if ( is_front_page() ) {
		return;
	}

	// Define
	global $post;
	$custom_taxonomy = ''; // If you have custom taxonomy place it here

	$defaults = array(
		'seperator'  => '>',
		'classes'    => 'breadcrumbs',
		'home_title' => esc_html__( 'Home', '' ),
	);

	$sep = '<li class="breadcrumbs__seperator">' . esc_html( $defaults['seperator'] ) . '</li>';

	// Start the breadcrumb with a link to your homepage
	echo '<ul class="' . esc_attr( $defaults['classes'] ) . '">';

	// Creating home link
	echo '<li class="' . $defaults['classes'] . '__item"><a class="' . $defaults['classes'] . '__link" href="' . get_home_url() . '">' . esc_html( $defaults['home_title'] ) . '</a></li>' . $sep;

	if ( is_single() ) {

		// Get posts type
		$post_type = get_post_type();

		// If post type is not post
		if ( $post_type != 'post' ) {

			$post_type_object = get_post_type_object( $post_type );
			$post_type_link   = $post_type !== 'programmes' ? get_post_type_archive_link( $post_type ) : get_home_url() . '/programmes';

			echo '<li class="' . $defaults['classes'] . '__item item-cat"><a class="' . $defaults['classes'] . '__link" href="' . $post_type_link . '">' . $post_type_object->labels->name . '</a></li>' . $sep;

		}

		// Get categories
		$category = get_the_category( $post->ID );

		// If category not empty
		if ( ! empty( $category ) ) {

			// Arrange category parent to child
			$category_values   = array_values( $category );
			$get_last_category = end( $category_values );
			// $get_last_category    = $category[count($category) - 1];
			$get_parent_category = rtrim( get_category_parents( $get_last_category->term_id, true, ',' ), ',' );
			$cat_parent          = explode( ',', $get_parent_category );

			// Store category in $display_category
			$display_category = '';
			foreach ( $cat_parent as $p ) {
				$display_category .= '<li class="' . $defaults['classes'] . '__item item-cat">' . $p . '</li>' . $sep;
			}
		}

		// If it's a custom post type within a custom taxonomy
		$taxonomy_exists = taxonomy_exists( $custom_taxonomy );

		if ( empty( $get_last_category ) && ! empty( $custom_taxonomy ) && $taxonomy_exists ) {

			$taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
			$cat_id         = $taxonomy_terms[0]->term_id;
			$cat_link       = get_term_link( $taxonomy_terms[0]->term_id, $custom_taxonomy );
			$cat_name       = $taxonomy_terms[0]->name;

		}

		// Check if the post is in a category
		if ( ! empty( $get_last_category ) ) {

			echo $display_category;
			echo '<li class="' . $defaults['classes'] . '__item item-current">' . get_the_title() . '</li>';

		} elseif ( ! empty( $cat_id ) ) {

			echo '<li class="' . $defaults['classes'] . '__item item-cat"><a class="' . $defaults['classes'] . '__link" href="' . $cat_link . '">' . $cat_name . '</a></li>' . $sep;
			echo '<li class="' . $defaults['classes'] . '__item-current item">' . get_the_title() . '</li>';

		} else {

			echo '<li class="' . $defaults['classes'] . '__item-current item">' . get_the_title() . '</li>';

		}
	} elseif ( is_archive() ) {

		if ( is_tax() ) {
			// Get posts type
			$post_type = get_post_type();

			// If post type is not post
			if ( $post_type != 'post' ) {

				$post_type_object = get_post_type_object( $post_type );
				$post_type_link   = get_post_type_archive_link( $post_type );

				echo '<li class="' . $defaults['classes'] . '__item item-cat item-custom-post-type-' . $post_type . '"><a class="' . $defaults['classes'] . '__link" href="' . $post_type_link . '">' . $post_type_object->labels->name . '</a></li>' . $sep;

			}

			$custom_tax_name = get_queried_object()->name;
			echo '<li class="' . $defaults['classes'] . '__item item-current">' . $custom_tax_name . '</li>';

		} elseif ( is_category() ) {

			$parent = get_queried_object()->category_parent;

			if ( $parent !== 0 ) {

				$parent_category = get_category( $parent );
				$category_link   = get_category_link( $parent );

				echo '<li class="' . $defaults['classes'] . '__item"><a class="' . $defaults['classes'] . '__link" href="' . esc_url( $category_link ) . '">' . $parent_category->name . '</a></li>' . $sep;

			}

			echo '<li class="' . $defaults['classes'] . '__item item-current">' . single_cat_title( '', false ) . '</li>';

		} elseif ( is_tag() ) {

			// Get tag information
			$term_id       = get_query_var( 'tag_id' );
			$taxonomy      = 'post_tag';
			$args          = 'include=' . $term_id;
			$terms         = get_terms( $taxonomy, $args );
			$get_term_name = $terms[0]->name;

			// Display the tag name
			echo '<li class="' . $defaults['classes'] . '__item-current item">' . $get_term_name . '</li>';

		} elseif ( is_day() ) {

			// Day archive

			// Year link
			echo '<li class="' . $defaults['classes'] . '__item-year item"><a class="' . $defaults['classes'] . '__link" href="' . get_year_link( get_the_time( 'Y' ) ) . '">' . get_the_time( 'Y' ) . ' Archives</a></li>' . $sep;

			// Month link
			echo '<li class="' . $defaults['classes'] . '__item-month item"><a class="' . $defaults['classes'] . '__link" href="' . get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) . '">' . get_the_time( 'M' ) . ' Archives</a></li>' . $sep;

			// Day display
			echo '<li class="' . $defaults['classes'] . '__item-current item">' . get_the_time( 'jS' ) . ' ' . get_the_time( 'M' ) . ' Archives</li>';

		} elseif ( is_month() ) {

			// Month archive

			// Year link
			echo '<li class="' . $defaults['classes'] . '__item-year item"><a class="' . $defaults['classes'] . '__link" href="' . get_year_link( get_the_time( 'Y' ) ) . '">' . get_the_time( 'Y' ) . ' Archives</a></li>' . $sep;

			// Month Display
			echo '<li class="' . $defaults['classes'] . '__item-month item-current item">' . get_the_time( 'M' ) . ' Archives</li>';

		} elseif ( is_year() ) {

			// Year Display
			echo '<li class="' . $defaults['classes'] . '__item-year item-current item">' . get_the_time( 'Y' ) . ' Archives</li>';

		} elseif ( is_author() ) {

			// Auhor archive

			// Get the author information
			global $author;
			$userdata = get_userdata( $author );

			// Display author name
			echo '<li class="' . $defaults['classes'] . '__item-current item">' . 'Author: ' . $userdata->display_name . '</li>';

		} else {

			echo '<li class="' . $defaults['classes'] . '__item item-current">' . post_type_archive_title() . '</li>';

		}
	} elseif ( is_page() ) {

		  // Standard page
		if ( $post->post_parent ) {

			// If child page, get parents
			$anc = get_post_ancestors( $post->ID );

			// Get parents in the right order
			$anc = array_reverse( $anc );

			// Parent page loop
			if ( ! isset( $parents ) ) {
				$parents = null;
			}
			foreach ( $anc as $ancestor ) {

				$parents .= '<li class="' . $defaults['classes'] . '__item-parent item"><a class="' . $defaults['classes'] . '__link" href="' . get_permalink( $ancestor ) . '">' . get_the_title( $ancestor ) . '</a></li>' . $sep;

			}

			// Display parent pages
			echo $parents;

			// Current page
			echo '<li class="' . $defaults['classes'] . '__item-current item">' . get_the_title() . '</li>';

		} else {

			// Just display current page if not parents
			echo '<li class="' . $defaults['classes'] . '__item-current item">' . get_the_title() . '</li>';

		}
	} elseif ( is_search() ) {

		  // Search results page
		  echo '<li class="' . $defaults['classes'] . '__item-current item">Search results for: ' . get_search_query() . '</li>';

	} elseif ( is_404() ) {

		// 404 page
		echo '<li class="' . $defaults['classes'] . '__item-current item">' . 'Error 404' . '</li>';

	}

	// End breadcrumb
	echo '</ul>';

}
