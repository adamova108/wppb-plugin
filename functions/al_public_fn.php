<?php
/**
 * Functions for public use (mainly the front-end)
 */

function al_the_author_data() {
	global $author_data;
	$author_data = al_get_authordata();
}


function al_get_authordata( int $author_ID = null ) {

	global $post;

	return is_null( $author_ID ) ?
		( isset( $post->ID ) && get_post_type( $post->ID ) == 'authors'
			? Al_Author_Admin::get_author_custom( $post->ID ) : array() )
				: Al_Author_Admin::get_author_custom( $author_ID );
}


function al_get_author_field( string $field = '', array $author_data = array() ) {

	if ( $field == '' ) {
		return '';
	}

	global $author_data;
	return isset( $author_data[ 'al_author_' . $field ] ) ? $author_data[ 'al_author_' . $field ] : '';
}


function al_get_author_image( array $author_data = array() ) {

	global $author_data;
	$author_image = al_get_author_field( 'profile_pic' );

	if ( ! is_numeric( $author_image ) ) {
		return '';
	}

	$attachment_image_url = wp_get_attachment_url( $author_image );
	return $attachment_image_url;
}


function al_the_author_image( array $author_data = array() ) {
	global $author_data;
	$attachment_image_url = al_get_author_image( $author_data );
	echo $attachment_image_url != '' ? "<img class='al_author_profile_pic' src='$attachment_image_url' />" : '';
}


function al_get_the_author_title() {
	return '<h2><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
}


function al_the_author_title() {
	echo al_get_the_author_title();
}


function al_get_the_author_bio() {
	return '<article id="al_author_bio">' . wpautop( al_get_author_field( 'bio' ) ) . '</article>';
}


function al_the_author_bio() {
	echo al_get_the_author_bio();
}


function al_get_the_author_bio_excerpt( int $words = 25 ) {
	$bio_raw = strip_tags( al_get_author_field( 'bio' ) );
	$bio_raw = explode( ' ', $bio_raw );
	$bio_raw = array_slice( $bio_raw, 0, $words );
	return '<article id="al_author_bio_excerpt">' . wpautop( implode( ' ', $bio_raw ) . ' [...]' ) . '</article>';
}


function al_the_author_bio_excerpt( int $words = 25 ) {
	echo al_get_the_author_bio_excerpt( $words );
}


function al_the_author_social() {

	$fb = al_get_author_field( 'fb_url' );
	$li = al_get_author_field( 'li_url' );

	$output = '';

	if ( ! empty( $fb ) ) {
		$output .= "<a title='Facebook' class='al_author_social_link al_author_fb_link' href='$fb' target='_blank'><span class='dashicons-before dashicons-facebook-alt'>&nbsp;</span></a>";
	}

	if ( ! empty( $li ) ) {
		$output .= "<a title='LinkedIn' class='al_author_social_link al_author_li_link' href='$li' target='_blank'><span class='dashicons-before dashicons-linkedin'>&nbsp;</span></a>";
	}

	echo $output;
}


function al_the_author_profile_link() {
	echo '<a class="al_author_profile_link" href="' . get_permalink() . '">View Author Profile &raquo;</a>';
}


function al_the_author_gallery() {

	$gallery = al_get_author_field( 'gallery' );

	$output = '';

	if ( is_array( $gallery ) && ! empty( $gallery ) ) {

		$output .= '<h2>' . al_get_author_field( 'first_name' ) . '\'s Photo Gallery</h2>';

		$output .= '<div id="al_author_gallery_wrapper">';

		foreach ( $gallery as $image_ID ) {

			if ( ! is_numeric( $image_ID ) ) {
				continue;
			}

			$image_ID = intval( $image_ID );
			$image_URL = wp_get_attachment_image_src( $image_ID, 'full' );
			$image_URL = array_shift( $image_URL );

			$output .= '<a class="al_author_gallery_item" data-fancybox="gallery" href="' . $image_URL . '" >';
			$output .= '<img class="al_author_gallery_image" src="' . $image_URL . '" />';
			$output .= '</a>';
		}

		$output .= '</div>';

	}

	echo $output;
}


function al_author_exists( int $user_id ) {

	global $wpdb;

	$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->users WHERE ID = %d", $user_id ) );

	return $count > 0 ? true : false;

}


function al_the_author_related_posts() {

	$link_user = al_get_author_field( 'link_user' );

	$output = '';

	if ( is_numeric( $link_user ) && al_author_exists( $link_user ) ) {

		$related_posts = new WP_Query(
			array(
				'author' => $link_user,
			)
		);

		if ( $related_posts->have_posts() ) {

			$output .= '<h2>' . al_get_author_field( 'first_name' ) . '\'s Related Posts</h2>';
			$output .= '<ul id="al_author_posts">';

			while ( $related_posts->have_posts() ) {
				$related_posts->the_post();
				$output .= '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
			}

			$output .= '</ul>';
		}
	}

	echo $output;
}
