<?php

namespace App;

use Roots\Sage\Container;
use Roots\Sage\Assets\JsonManifest;
use Roots\Sage\Template\Blade;
use Roots\Sage\Template\BladeProvider;
use StoutLogic\AcfBuilder\FieldsBuilder;

/**
 * Theme assets
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'sage/main.css', asset_path( 'styles/main.css' ), false, null );
		wp_enqueue_script( 'sage/main.js', asset_path( 'scripts/main.js' ), array( 'jquery' ), null, true );
		wp_enqueue_script( 'intersection-observer/polyfill', get_template_directory_uri() . '/assets/scripts/polyfills/intersection-observer.js', array(), null, false );
		wp_enqueue_script( 'lazyload/polyfill', get_template_directory_uri() . '/assets/scripts/polyfills/vanilla-lazyload.min.js', array(), null, false );

		if ( is_single() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	},
	100
);

/**
 * Theme setup
 */
add_action(
	'after_setup_theme',
	function () {
		/**
		 * Enable features from Soil when plugin is activated
		 *
		 * @link https://roots.io/plugins/soil/
		 */
		add_theme_support( 'soil-clean-up' );
		add_theme_support( 'soil-jquery-cdn' );
		add_theme_support( 'soil-nav-walker' );
		add_theme_support( 'soil-nice-search' );
		add_theme_support( 'soil-relative-urls' );

		/**
		 * Enable plugins to manage the document title
		 *
		 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Register navigation menus
		 *
		 * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
		 */
		register_nav_menus(
			array(
				'primary_navigation' => __( 'Primary Navigation', 'sage' ),
			)
		);
		/**
		 * Enable post thumbnails
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/**
		 * Enable HTML5 markup support
		 *
		 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
		 */
		add_theme_support( 'html5', array( 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );

		/**
		 * Enable selective refresh for widgets in customizer
		 *
		 * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#theme-support-in-sidebars
		 */
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Use main stylesheet for visual editor
		 *
		 * @see resources/assets/styles/layouts/_tinymce.scss
		 */
		add_editor_style( asset_path( 'styles/admin.css' ) );
	},
	20
);

/**
 * Register sidebars
 */
add_action(
	'widgets_init',
	function () {
		$config = array(
			'before_widget' => '<section class="widget %1$s %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>',
		);
		register_sidebar(
			array(
				'name' => __( 'Primary', 'sage' ),
				'id'   => 'sidebar-primary',
			) + $config
		);
		register_sidebar(
			array(
				'name' => __( 'Footer', 'sage' ),
				'id'   => 'sidebar-footer',
			) + $config
		);
	}
);

/**
 * Updates the `$post` variable on each iteration of the loop.
 * Note: updated value is only available for subsequently loaded views, such as partials
 */
add_action(
	'the_post',
	function ( $post ) {
		sage( 'blade' )->share( 'post', $post );
	}
);

/**
 * Setup Sage options
 */
add_action(
	'after_setup_theme',
	function () {
		/**
		 * Add JsonManifest to Sage container
		 */
		sage()->singleton(
			'sage.assets',
			function () {
				return new JsonManifest( config( 'assets.manifest' ), config( 'assets.uri' ) );
			}
		);

		/**
		 * Add Blade to Sage container
		 */
		sage()->singleton(
			'sage.blade',
			function ( Container $app ) {
				$cachePath = config( 'view.compiled' );
				if ( ! file_exists( $cachePath ) ) {
					wp_mkdir_p( $cachePath );
				}
				( new BladeProvider( $app ) )->register();
				return new Blade( $app['view'] );
			}
		);

		/**
		 * Create @asset() Blade directive
		 */
		sage( 'blade' )->compiler()->directive(
			'asset',
			function ( $asset ) {
				return '<?= ' . __NAMESPACE__ . "\\asset_path({$asset}); ?>";
			}
		);

		/**
		 * Add additional image sizes
		 */
		add_image_size( 'small', 300, 9999 ); // 300px wide unlimited height
		add_image_size( 'medium-small', 450, 9999 ); // 300px wide unlimited height
		add_image_size( 'xl', 1200, 9999 ); // 1200px wide unlimited height
		add_image_size( 'xxl', 2000, 9999 ); // 2000px wide unlimited height
		add_image_size( 'xxxl', 3000, 9999 ); // 3000px wide unlimited height
		add_image_size( 'portfolio_full', 9999, 900 ); // 900px tall unlimited width
	}
);

// Admin area styling
add_action(
	'admin_head',
	function() {
		echo '<style>
		.wp-block {
			max-width: calc(100% - 4rem);
		}
	</style>';
	}
);

/**
 * Register CPTs
 */

add_action(
	'init',
	function () {
		register_post_type(
			'blogs',
			array(
				'label'              => 'Blogs',
				'public'             => true,
				'publicly_queryable' => true,
				'description'        => 'Add Blogs here',
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_nav_menus'  => true,
				'show_in_admin_bar'  => true,
				'menu_icon'          => 'dashicons-welcome-write-blog',
				'has_archive'        => true,
				'supports'           => array( 'author', 'editor', 'title', 'thumbnail', 'excerpt' ),
				'rewrite'            => array(
					'slug'       => 'blogs',
					'with_front' => true,
				),
				'show_in_rest'       => true,
				'editor'             => true,
			)
		);

		register_post_type(
			'events',
			array(
				'label'              => 'Events',
				'public'             => true,
				'publicly_queryable' => true,
				'description'        => 'Add Events here',
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_nav_menus'  => true,
				'show_in_admin_bar'  => true,
				'menu_icon'          => 'dashicons-calendar-alt',
				'has_archive'        => true,
				'supports'           => array( 'author', 'editor', 'title', 'thumbnail' ),
				'rewrite'            => array(
					'slug'       => 'events',
					'with_front' => true,
				),
				'show_in_rest'       => true,
				'editor'             => true,
			)
		);

		register_post_type(
			'projects',
			array(
				'label'              => 'Projects',
				'public'             => true,
				'publicly_queryable' => true,
				'description'        => 'Add Projects here',
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_nav_menus'  => true,
				'show_in_admin_bar'  => true,
				'menu_icon'          => 'dashicons-analytics',
				'has_archive'        => true,
				'supports'           => array( 'author', 'editor', 'title', 'thumbnail', 'excerpt' ),
				'rewrite'            => array(
					'slug'       => 'projects',
					'with_front' => true,
				),
				'show_in_rest'       => true,
				'editor'             => true,
			)
		);

		register_post_type(
			'case-studies',
			array(
				'label'              => 'Case Studies',
				'public'             => true,
				'publicly_queryable' => true,
				'description'        => 'Add Case Studies here',
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_nav_menus'  => true,
				'show_in_admin_bar'  => true,
				'menu_icon'          => 'dashicons-analytics',
				'has_archive'        => true,
				'supports'           => array( 'author', 'editor', 'title', 'thumbnail', 'excerpt' ),
				'rewrite'            => array(
					'slug'       => 'case-studies',
					'with_front' => true,
				),
				'show_in_rest'       => true,
				'editor'             => true,
			)
		);

		register_post_type(
			'news',
			array(
				'label'              => 'News',
				'public'             => true,
				'publicly_queryable' => true,
				'description'        => 'Add News here',
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_nav_menus'  => true,
				'show_in_admin_bar'  => true,
				'menu_icon'          => 'dashicons-media-text',
				'has_archive'        => true,
				'supports'           => array( 'author', 'editor', 'title', 'thumbnail', 'excerpt' ),
				'rewrite'            => array(
					'slug'       => 'news',
					'with_front' => true,
				),
				'show_in_rest'       => true,
				'editor'             => true,
			)
		);

		register_post_type(
			'programmes',
			array(
				'label'              => 'Programmes',
				'public'             => true,
				'publicly_queryable' => true,
				'description'        => 'Add Programmes here',
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_nav_menus'  => true,
				'show_in_admin_bar'  => true,
				'menu_icon'          => 'dashicons-screenoptions',
				'has_archive'        => false,
				'supports'           => array( 'author', 'editor', 'title', 'thumbnail', 'excerpt' ),
				'rewrite'            => array(
					'slug'       => 'programmes',
					'with_front' => true,
				),
				'show_in_rest'       => true,
				'editor'             => true,
			)
		);

		register_post_type(
			'publications',
			array(
				'label'              => 'Publications',
				'public'             => true,
				'publicly_queryable' => true,
				'description'        => 'Add Publications here',
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_nav_menus'  => true,
				'show_in_admin_bar'  => true,
				'menu_icon'          => 'dashicons-media-text',
				'has_archive'        => true,
				'rewrite'            => array(
					'slug'       => 'publications',
					'with_front' => true,
				),
				'show_in_rest'       => true,
				'editor'             => true,
				'supports'           => array( 'author', 'editor', 'title', 'thumbnail', 'excerpt' ),
			)
		);

		register_post_type(
			'research-papers',
			array(
				'label'              => 'Research Papers',
				'public'             => true,
				'publicly_queryable' => true,
				'description'        => 'Add Research Papers here',
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_nav_menus'  => true,
				'show_in_admin_bar'  => true,
				'menu_icon'          => 'dashicons-media-text',
				'has_archive'        => true,
				'rewrite'            => array(
					'slug'       => 'research-papers',
					'with_front' => true,
				),
				'show_in_rest'       => true,
				'editor'             => true,
				'supports'           => array( 'author', 'editor', 'title', 'thumbnail', 'excerpt' ),
			)
		);

		register_post_type(
			'team',
			array(
				'label'              => 'Team',
				'public'             => true,
				'publicly_queryable' => true,
				'description'        => 'Add Team members here',
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_nav_menus'  => true,
				'show_in_admin_bar'  => true,
				'menu_icon'          => 'dashicons-admin-users',
				'has_archive'        => true,
				'rewrite'            => array(
					'slug'       => 'team',
					'with_front' => true,
				),
				'show_in_rest'       => true,
				'editor'             => true,
				'supports'           => array( 'author', 'title', 'thumbnail', 'excerpt' ),
			)
		);
	}
);

$post_type_list = array( 'news', 'events', 'case-studies', 'projects', 'blogs', 'programmes', 'publications', 'research-papers', 'team' );

register_taxonomy(
	'year_category',
	$post_type_list,
	array(
		'hierarchical'      => true,
		'labels'            => array(
			'name'              => _x( 'Year', 'taxonomy general name' ),
			'singular_name'     => _x( 'Year', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Years' ),
			'all_items'         => __( 'All Years' ),
			'parent_item'       => __( 'Parent Year' ),
			'parent_item_colon' => __( 'Parent Year:' ),
			'edit_item'         => __( 'Edit Year' ),
			'view_item'         => __( 'View Year' ),
			'update_item'       => __( 'Update Year' ),
			'add_new_item'      => __( 'Add New Year' ),
			'new_item_name'     => __( 'New Year' ),
			'menu_name'         => __( 'Year Categories' ),
		),
		'public'            => true,
		'show_in_menu'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => 1,
		'rewrite'           => array(
			'slug'         => 'year-category',
			'with_front'   => false,
			'hierarchical' => true,
		),
	)
);

register_taxonomy(
	'location_category',
	$post_type_list,
	array(
		'hierarchical'      => true,
		'labels'            => array(
			'name'              => _x( 'Location', 'taxonomy general name' ),
			'singular_name'     => _x( 'Location', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Locations' ),
			'all_items'         => __( 'All Locations' ),
			'parent_item'       => __( 'Parent Location' ),
			'parent_item_colon' => __( 'Parent Location:' ),
			'edit_item'         => __( 'Edit Location' ),
			'view_item'         => __( 'View Location' ),
			'update_item'       => __( 'Update Location' ),
			'add_new_item'      => __( 'Add New Location' ),
			'new_item_name'     => __( 'New Location' ),
			'menu_name'         => __( 'Location Categories' ),
		),
		'public'            => true,
		'show_in_menu'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => 1,
		'rewrite'           => array(
			'slug'         => 'location-category',
			'with_front'   => false,
			'hierarchical' => true,
		),
	)
);

register_taxonomy(
	'health_and_care_service',
	$post_type_list,
	array(
		'hierarchical'      => true,
		'labels'            => array(
			'name'              => _x( 'Health and Care Service', 'taxonomy general name' ),
			'singular_name'     => _x( 'Health and Care Service', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Health and Care Services' ),
			'all_items'         => __( 'All Health and Care Services' ),
			'parent_item'       => __( 'Parent Health and Care Service' ),
			'parent_item_colon' => __( 'Parent Health and Care Service:' ),
			'edit_item'         => __( 'Edit Health and Care Service' ),
			'view_item'         => __( 'View Health and Care Service' ),
			'update_item'       => __( 'Update Health and Care Service' ),
			'add_new_item'      => __( 'Add New Health and Care Service' ),
			'new_item_name'     => __( 'New Health and Care Service' ),
			'menu_name'         => __( 'Health and Care Service Categories' ),
		),
		'public'            => true,
		'show_in_menu'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => 1,
		'rewrite'           => array(
			'slug'         => 'health-and-care-service',
			'with_front'   => false,
			'hierarchical' => true,
		),
	)
);

register_taxonomy(
	'theme_category',
	$post_type_list,
	array(
		'hierarchical'      => true,
		'labels'            => array(
			'name'              => _x( 'Theme', 'taxonomy general name' ),
			'singular_name'     => _x( 'Theme', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Themes' ),
			'all_items'         => __( 'All Themes' ),
			'parent_item'       => __( 'Parent Theme' ),
			'parent_item_colon' => __( 'Parent Theme:' ),
			'edit_item'         => __( 'Edit Theme' ),
			'view_item'         => __( 'View Theme' ),
			'update_item'       => __( 'Update Theme' ),
			'add_new_item'      => __( 'Add New Theme' ),
			'new_item_name'     => __( 'New Theme' ),
			'menu_name'         => __( 'Theme Categories' ),
		),
		'public'            => true,
		'show_in_menu'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => 1,
		'rewrite'           => array(
			'slug'         => 'theme-category',
			'with_front'   => false,
			'hierarchical' => true,
		),
	)
);

register_taxonomy(
	'team_category',
	'team',
	array(
		'hierarchical'      => true,
		'labels'            => array(
			'name'              => _x( 'Team Category', 'taxonomy general name' ),
			'singular_name'     => _x( 'Team Category', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Team Categories' ),
			'all_items'         => __( 'All Team Categories' ),
			'parent_item'       => __( 'Parent Team Category' ),
			'parent_item_colon' => __( 'Parent Team Category:' ),
			'edit_item'         => __( 'Edit Team Category' ),
			'view_item'         => __( 'View Team Category' ),
			'update_item'       => __( 'Update Team Category' ),
			'add_new_item'      => __( 'Add New Team Category' ),
			'new_item_name'     => __( 'New Team Category' ),
			'menu_name'         => __( 'Team Categories' ),
		),
		'public'            => true,
		'show_in_menu'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => 1,
		'rewrite'           => array(
			'slug'         => 'team-category',
			'with_front'   => false,
			'hierarchical' => true,
		),
	)
);

register_taxonomy(
	'publication_category',
	'publications',
	array(
		'hierarchical'      => true,
		'labels'            => array(
			'name'              => _x( 'Publication Categories', 'taxonomy general name' ),
			'singular_name'     => _x( 'Publication Category', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Publication Categories' ),
			'all_items'         => __( 'All Publication Categories' ),
			'parent_item'       => __( 'Parent Publication Category' ),
			'parent_item_colon' => __( 'Parent Publication Category:' ),
			'edit_item'         => __( 'Edit Publication Category' ),
			'view_item'         => __( 'View Publication Category' ),
			'update_item'       => __( 'Update Publication Category' ),
			'add_new_item'      => __( 'Add New Publication Category' ),
			'new_item_name'     => __( 'New Publication Category' ),
			'menu_name'         => __( 'Publication Categories' ),
		),
		'public'            => true,
		'show_in_menu'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => 1,
		'rewrite'           => array(
			'slug'         => 'publication-category',
			'with_front'   => false,
			'hierarchical' => true,
		),
	)
);

register_taxonomy(
	'event_category',
	'events',
	array(
		'hierarchical'      => true,
		'labels'            => array(
			'name'              => _x( 'Event Categories', 'taxonomy general name' ),
			'singular_name'     => _x( 'Event Category', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Event Categories' ),
			'all_items'         => __( 'All Event Categories' ),
			'parent_item'       => __( 'Parent Event Category' ),
			'parent_item_colon' => __( 'Parent Event Category:' ),
			'edit_item'         => __( 'Edit Event Category' ),
			'view_item'         => __( 'View Event Category' ),
			'update_item'       => __( 'Update Event Category' ),
			'add_new_item'      => __( 'Add New Event Category' ),
			'new_item_name'     => __( 'New Event Category' ),
			'menu_name'         => __( 'Event Categories' ),
		),
		'public'            => true,
		'show_in_menu'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => 1,
		'rewrite'           => array(
			'slug'         => 'event-category',
			'with_front'   => false,
			'hierarchical' => true,
		),
	)
);

register_taxonomy(
	'blog_category',
	'blogs',
	array(
		'hierarchical'      => true,
		'labels'            => array(
			'name'              => _x( 'Blog Categories', 'taxonomy general name' ),
			'singular_name'     => _x( 'Blog Category', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Blog Categories' ),
			'all_items'         => __( 'All Blog Categories' ),
			'parent_item'       => __( 'Parent Blog Category' ),
			'parent_item_colon' => __( 'Parent Blog Category:' ),
			'edit_item'         => __( 'Edit Blog Category' ),
			'view_item'         => __( 'View Blog Category' ),
			'update_item'       => __( 'Update Blog Category' ),
			'add_new_item'      => __( 'Add New Blog Category' ),
			'new_item_name'     => __( 'New Blog Category' ),
			'menu_name'         => __( 'Blog Categories' ),
		),
		'public'            => true,
		'show_in_menu'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => 1,
		'rewrite'           => array(
			'slug'         => 'blog-category',
			'with_front'   => false,
			'hierarchical' => true,
		),
	)
);

register_taxonomy(
	'case_study_category',
	'case-studies',
	array(
		'hierarchical'      => true,
		'labels'            => array(
			'name'              => _x( 'Case Study Categories', 'taxonomy general name' ),
			'singular_name'     => _x( 'Case Study Category', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Case Study Categories' ),
			'all_items'         => __( 'All Case Study Categories' ),
			'parent_item'       => __( 'Parent Case Study Category' ),
			'parent_item_colon' => __( 'Parent Case Study Category:' ),
			'edit_item'         => __( 'Edit Case Study Category' ),
			'view_item'         => __( 'View Case Study Category' ),
			'update_item'       => __( 'Update Case Study Category' ),
			'add_new_item'      => __( 'Add New Case Study Category' ),
			'new_item_name'     => __( 'New Case Study Category' ),
			'menu_name'         => __( 'Case Study Categories' ),
		),
		'public'            => true,
		'show_in_menu'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => 1,
		'rewrite'           => array(
			'slug'         => 'case-study-category',
			'with_front'   => false,
			'hierarchical' => true,
		),
	)
);

register_taxonomy(
	'project_category',
	'projects',
	array(
		'hierarchical'      => true,
		'labels'            => array(
			'name'              => _x( 'Project Categories', 'taxonomy general name' ),
			'singular_name'     => _x( 'Project Category', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Project Categories' ),
			'all_items'         => __( 'All Project Categories' ),
			'parent_item'       => __( 'Parent Project Category' ),
			'parent_item_colon' => __( 'Parent Project Category:' ),
			'edit_item'         => __( 'Edit Project Category' ),
			'view_item'         => __( 'View Project Category' ),
			'update_item'       => __( 'Update Project Category' ),
			'add_new_item'      => __( 'Add New Project Category' ),
			'new_item_name'     => __( 'New Project Category' ),
			'menu_name'         => __( 'Project Categories' ),
		),
		'public'            => true,
		'show_in_menu'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => 1,
		'rewrite'           => array(
			'slug'         => 'project-category',
			'with_front'   => false,
			'hierarchical' => true,
		),
	)
);

/**
 * Initialize ACF Builder
 */
add_action(
	'init',
	function () {
		function globRecursive( $pattern, $flags = 0 ) {
			$files = glob( $pattern, $flags );

			foreach ( glob( dirname( $pattern ) . '/*', GLOB_ONLYDIR | GLOB_NOSORT ) as $dir ) {
				$files = array_merge( $files, globRecursive( $dir . '/' . basename( $pattern ), $flags ) );
			}

			return $files;
		}
		$app_fields = collect( globRecursive( config( 'theme.dir' ) . '/app/fields/*/' ) );

		$app_fields->each(
			function ( $field ) {
				if ( strpos( $field, '/app/fields/partials/' ) === false ) {
					if ( ! is_dir( $field ) && ! preg_match( '/.blade./', $field ) && ! preg_match( '/template/', $field ) ) {
						$field = require_once $field;

						if ( $field instanceof FieldsBuilder ) {
							if ( function_exists( 'acf_add_local_field_group' ) ) {
								acf_add_local_field_group( $field->build() );
							};
						}
					}
				}
			}
		);

	}
);


/**
 * Re-assign the default admin username.
 */
require_once ABSPATH . 'wp-admin/includes/user.php';

add_action(
	'init',
	function() {
		$username = env( 'ADMIN_USER_NAME' );
		$password = env( 'ADMIN_USER_PASSWORD' );
		$email    = env( 'ADMIN_USER_EMAIL' );

		// If environment variables are not set, take no action
		if ( ! $username || ! $password || ! $email ) {
			return;
		}

		// Check to see if the new admin user has already been created
		// This should ensure that this code only runs once per project
		if ( username_exists( $username ) || email_exists( $email ) ) {
			return;
		}

		// Create the new user account
		$user_id = wp_create_user( $username, $password, $email );
		$user    = get_user_by( 'id', $user_id );
		$user->remove_role( 'subscriber' );
		$user->add_role( 'administrator' );

		// Delete the old admin account
		$admin_user = get_user_by( 'login', 'admin' );

		if ( $admin_user ) {
			$admin_user_id = $admin_user->ID;
			// Pass in $user_id to assign the admin user's
			// posts to the new admin account.
			wp_delete_user( $admin_user_id, $user_id );
		}
	}
);

/**
 * Amend rest route to send back a formatted version of each post for post cards
 */
add_action(
	'rest_api_init',
	function () {
		$post_type_list = array( 'post', 'news', 'events', 'case-studies', 'projects', 'blogs', 'programmes', 'publications', 'research-papers', 'team' );

		collect( $post_type_list )->map(
			function( $post_type ) {
				register_rest_field(
					$post_type,
					'card',
					array(
						'get_callback' => function( $post ) {
							return \App::formatPostForPostCard( $post );
						},
					)
				);
			}
		);
	}
);

/**
 * Add custom rest route to search for any content.
 * To be used on search results page
 */
add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'wp/v2',
			'/searchall',
			array(
				'methods'             => 'GET',
				'permission_callback' => '__return_true',
				'callback'            => function( \WP_REST_Request $request ) {
					$params = $request->get_query_params();
					$args = array( 'post_type' => 'any' );

					$args['s'] = isset( $params['search'] ) ? $params['search'] : null;
					$args['posts_per_page'] = isset( $params['per_page'] ) ? $params['per_page'] : 10;
					$args['paged'] = isset( $params['page'] ) ? $params['page'] : 1;
					$args['relevanssi'] = true;
					$query = new \WP_Query();
					$query->parse_query( $args );

					\relevanssi_do_query( $query );
					$posts = collect( $query->posts )->map(
						function( $post ) {
							return array(
								'card' => \App::formatPostForPostCard( $post->to_array() ),
							);
						}
					);

					$headers = array( 'x-wp-totalpages' => $query->max_num_pages );
					$response = new \WP_REST_Response( $posts );
					$response->set_headers( $headers );
					return $response;
				},
			)
		);

		register_rest_route(
			'wp/v2',
			'/filter',
			array(
				'methods'             => 'GET',
				'permission_callback' => '__return_true',
				'callback'            => function( \WP_REST_Request $request ) {

					$params = $request->get_query_params();
					$post_type = $params['post_type'] ?? 'any';
					$args = array( 'post_type' => $post_type );

					$taxonomies = array();
					foreach ( $params as $param => $val ) {
						if ( $param !== 'per_page' && $param !== 'page' && $param !== 'search' && $param !== 'post_type' && $param !== 'split' ) {
							$valArray = explode( ',', $val );
							$terms = array();
							foreach ( $valArray as $val ) {
								array_push( $terms, (int) $val );
							}
							array_push( $taxonomies, array( $param => $terms ) );
						}
					};

					$taxonomies = collect( $taxonomies )->collapse();

					if ( isset( $params['search'] ) ) {
						$args['s'] = $params['search'];
					}
					$args['posts_per_page'] = isset( $params['per_page'] ) ? $params['per_page'] : 10;
					$args['paged'] = isset( $params['page'] ) ? $params['page'] : 1;
					$args['orderby'] = 'date';
					$args['order'] = 'DESC';
					if ( ! isset( $params['split'] ) ) {
						$args['relevanssi'] = true;
					}
					if ( isset( $params['split'] ) ) {
						$taxQueries = array();
						foreach ( $taxonomies as $tax => $terms ) {
							array_push(
								$taxQueries,
								array(
									'taxonomy' => $tax,
									'terms'    => $terms,
								)
							);
						}
						$args['tax_query'] = $taxQueries;
					}

					if ( $post_type === 'events' ) {
						$args['meta_key'] = 'start_date';
						$args['orderby'] = array( 'meta_value' => 'ASC' );
						$args['meta_query'] = array(
							array(
								'key'     => 'start_date',
								'value'   => date( 'Y-m-d' ),
								'compare' => '>=',
								'type'    => 'DATE',
							),
						);
					}

					if ( $post_type === 'publications' || $post_type === 'research_papers') {
						$args['meta_key'] = 'paper_date';
						$args['orderby'] = array( 'meta_value' => 'DESC' );
					}

					$query = new \WP_Query( $args );

					if ( ! isset( $params['split'] ) ) {
						$query = new \WP_Query();
						$query->parse_query( $args );
						\relevanssi_do_query( $query );
					}

					$posts = collect( $query->posts )->map(
						function( $post ) {
							return array(
								'card' => \App::formatPostForPostCard( $post->to_array() ),
							);
						}
					);

					$headers = array( 'x-wp-totalpages' => $query->max_num_pages );
					$response = new \WP_REST_Response( $posts );
					$response->set_headers( $headers );
					return $response;
				},
			)
		);
	}
);
