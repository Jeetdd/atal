<?php
/* Plugin Name: SEO fixes (seo-auditor) */
defined( 'ABSPATH' ) || exit;

/**
 * Output Canonical URL on archive and taxonomy pages where WordPress core does not output one.
 */
add_action( 'wp_head', function () {
	if ( is_singular() ) {
		return;
	}

	$canonical_url = '';

	if ( is_front_page() ) {
		$canonical_url = home_url( '/' );
	} elseif ( is_home() ) {
		$canonical_url = get_permalink( get_option( 'page_for_posts' ) );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$canonical_url = get_term_link( get_queried_object() );
	} elseif ( is_author() ) {
		$canonical_url = get_author_posts_url( get_queried_object_id() );
	} elseif ( is_post_type_archive() ) {
		$canonical_url = get_post_type_archive_link( get_query_var( 'post_type' ) );
	}

	if ( ! empty( $canonical_url ) && ! is_wp_error( $canonical_url ) ) {
		echo '<link rel="canonical" href="' . esc_url( $canonical_url ) . '" />' . "\n";
	}
}, 1 );

/**
 * Output Meta Description, Open Graph and Twitter Card tags.
 */
add_action( 'wp_head', function () {
	$title = '';
	$description = '';
	$url = '';
	$type = 'website';
	$image = '';

	if ( is_front_page() ) {
		$title = get_bloginfo( 'name' );
		$description = get_bloginfo( 'description' );
		$url = home_url( '/' );
		$type = 'website';
	} elseif ( is_singular() ) {
		$title = get_the_title();
		$post_id = get_the_ID();
		if ( has_excerpt( $post_id ) ) {
			$description = get_the_excerpt( $post_id );
		}
		if ( empty( $description ) ) {
			$description = wp_trim_words( wp_strip_all_tags( get_the_content( null, false, $post_id ) ), 35 );
		}
		$url = get_permalink( $post_id );
		$type = 'article';
		$img_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
		if ( $img_src ) {
			$image = $img_src[0];
		}
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		if ( $term ) {
			$title = $term->name;
			$term_desc = term_description( $term->term_id );
			if ( ! empty( $term_desc ) ) {
				$description = wp_strip_all_tags( $term_desc );
			}
			$term_link = get_term_link( $term );
			if ( ! is_wp_error( $term_link ) ) {
				$url = $term_link;
			}
		}
	} elseif ( is_home() ) {
		$blog_page_id = get_option( 'page_for_posts' );
		if ( $blog_page_id ) {
			$title = get_the_title( $blog_page_id );
			$url = get_permalink( $blog_page_id );
			if ( has_excerpt( $blog_page_id ) ) {
				$description = get_the_excerpt( $blog_page_id );
			}
		} else {
			$title = get_bloginfo( 'name' );
			$url = home_url( '/' );
		}
		if ( empty( $description ) ) {
			$description = get_bloginfo( 'description' );
		}
	} elseif ( is_post_type_archive() ) {
		$post_type = get_query_var( 'post_type' );
		if ( is_array( $post_type ) ) {
			$post_type = reset( $post_type );
		}
		$post_type_obj = get_post_type_object( $post_type );
		if ( $post_type_obj ) {
			$title = $post_type_obj->labels->name;
			$url = get_post_type_archive_link( $post_type );
		}
	}

	$title = wp_strip_all_tags( $title );
	$description = wp_strip_all_tags( $description );

	if ( ! empty( $description ) ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
		echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
		echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '" />' . "\n";
	}

	if ( ! empty( $title ) ) {
		echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";
		echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '" />' . "\n";
	}

	if ( ! empty( $url ) ) {
		echo '<meta property="og:url" content="' . esc_url( $url ) . '" />' . "\n";
	}

	echo '<meta property="og:type" content="' . esc_attr( $type ) . '" />' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";

	if ( ! empty( $image ) ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '" />' . "\n";
		echo '<meta name="twitter:image" content="' . esc_url( $image ) . '" />' . "\n";
		echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
	}
}, 2 );

/**
 * Trim long titles if necessary to avoid truncation in search engine results.
 */
add_filter( 'document_title_parts', function ( $parts ) {
	if ( ! empty( $parts['title'] ) && mb_strlen( $parts['title'] ) > 45 ) {
		// If title alone is long, keep site name short or omit site part if needed
		if ( isset( $parts['site'] ) && mb_strlen( $parts['title'] . ' - ' . $parts['site'] ) > 60 ) {
			// Do not break the title text itself as we never invent or truncate copy arbitrarily,
			// but we can remove the site part if total length exceeds limit.
			unset( $parts['site'] );
		}
	}
	return $parts;
}, 20 );
