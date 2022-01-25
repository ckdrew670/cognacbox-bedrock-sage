<?php

namespace App\Controllers;

use Sober\Controller\Controller;
use DateTime;

class App extends Controller {

	public function siteName() {
		return get_bloginfo( 'name' );
	}

	public static function title() {
		if ( is_home() ) {
			if ( $home = get_option( 'page_for_posts', true ) ) {
				return get_the_title( $home );
			}
			return __( 'Latest Posts', 'sage' );
		}
		if ( is_archive() ) {
			return get_the_archive_title();
		}
		if ( is_search() ) {
			return sprintf( __( 'Search Results for %s', 'sage' ), get_search_query() );
		}
		if ( is_404() ) {
			return __( 'Not Found', 'sage' );
		}
		return get_the_title();
	}

	public static function getFooterOptions() {
		 return array(
			 'footer_logo'       => \App\responsive_image(
				 array(
					  'image_id'  => get_field( 'footer_logo', 'options' ),
					  'lazy_load' => false,
				  )
			 ),
			 'footer_copyright' => get_field( 'footer_copyright', 'options' ),
			 'menu_links'       => get_field( 'menu_links', 'options' ),
			 'social_links'     => get_field( 'social_links', 'options' ),
		 );
	}

	public static function getLinkedCptPage() {
		$posts = get_posts(
			array(
				'posts_per_page' => 1,
				'post_type'      => 'page',
				'meta_key'       => 'associated_cpt',
				'meta_value'     => get_post_type(),
			)
		);

		if ( ! empty( $posts ) ) {
			$p = $posts[0];
		} else {
			$p = get_post();
		}

		if ( is_admin() && empty( $post ) ) {
			$p = get_post( acf_maybe_get_POST( 'post_id' ) );
		}

		return $p;
	}

	public static function getAllTerms() {
		global $post;
		$taxonomies = get_post_taxonomies();
		$terms      = array();

		foreach ( $taxonomies as $taxonomy ) {
			if ( has_term( '', $taxonomy ) ) {
				$taxTerms = get_the_terms( $post, $taxonomy );
				foreach ( $taxTerms as $term ) {
					$terms[ $term->name ] = $term;
				}
			}
		}

		return $terms;
	}

	public static function formatPostForPostCard( $post ) {
		$id               = isset( $post['id'] ) ? $post['id'] : $post['ID'];
		$post_type        = get_post_type( $id );

		// dates
		$date             = isset( $post['date'] ) ? $post['date'] : $post['post_date'];
		$date             = new DateTime( strtok( $date, ' ' ) );
		$date             = $date->format( 'M d, Y' );
		$event_date 			= get_field( 'start_date', $id );
		$formatted_event_date = (new DateTime(strtok( $event_date, ' ' )))->format('M d, Y');
		$event_time			  = get_field( 'start_time', $id );
		$publication_date = get_field('paper_date', $id);
		$formatted_publication_date = (new DateTime(strtok( $publication_date, ' ' )))->format('M d, Y');
		$image_id         = get_post_thumbnail_id( $id );
		$image_alt        = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
		$image            = wp_get_attachment_image_src( $image_id, 'large' );
		$image            = $image ? $image[0] : '';

		// post type specific variables
		$location         = get_field( 'event_location', $id );
		$position         = get_field( 'position', $id );

		// authors
		$author_id        = $post['author'];
		$author           = get_the_author_meta( 'display_name', $author_id );

		if($post_type === 'publications' || $post_type === 'research-papers') {
			$author = get_field('paper_authors', $id)[0]['author'];

			if(count(get_field('paper_authors', $id)) > 1) {
				$author = get_field('paper_authors', $id)[0]['author'] . ' et al.';
			}
		}
		if($post_type === 'blogs') {
			$author = get_field('author', $id);
		}

		// right
		$right = $date;

		if($post_type === 'events') {
			$right = $location;
		}
		if($post_type === 'publications' || $post_type === 'research-papers') {
			$right = $formatted_publication_date;
		}

		// left
		$left = $author;

		if($post_type === 'events') {
			$left = $formatted_event_date . ', ' . $event_time;
		}

		// excerpt
		$excerpt = get_the_excerpt( $id );
		if($post_type === 'events') {
			$excerpt = get_field('event_summary', $id);
		}

		return array(
			'post_type' => $post_type,
			'title'     => get_the_title( $id ),
			'link'      => get_the_permalink( $id ),
			'date'      => $date,
			'author'    => $author,
			'excerpt'   => \App\limit_text($excerpt, 20),
			'image'     => $image,
			'image_alt' => $image_alt,
			'location'  => $location ? $location : '',
			'position'  => $position ? $position : '',
			'left'      => $left,
			'right'     => $right,
		);
	}
}
