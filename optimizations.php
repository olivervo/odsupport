<?php
//Function to defer all scripts which are not excluded
function crave_js_defer_attr($tag) {
	if (is_admin()) {
		return $tag;
	}
	// Do not add defer attribute to these scripts
	$scripts_to_exclude = array('jquery.js, map.js, common.js'); // add a string of js file e.g. script.js

	foreach($scripts_to_exclude as $exclude_script) {
		if (true == strpos($tag, $exclude_script ) )
			return $tag;
	}
	// Defer all remaining scripts not excluded above
	return str_replace( ' src', ' defer src', $tag );
}
add_filter( 'script_loader_tag', 'crave_js_defer_attr', 10);

//Remove query strings
function crave_remove_script_version( $src ) {
	$parts = explode( '?ver', $src );
	return $parts[0];
}
add_filter( 'script_loader_src', 'crave_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', 'crave_remove_script_version', 15, 1 );

// Disable the emoji's
function crave_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'crave_disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', 'crave_disable_emojis_remove_dns_prefetch', 10, 2 );
}
add_action( 'init', 'crave_disable_emojis' );

/**
* Filter function used to remove the tinymce emoji plugin.
*
* @param array $plugins
* @return array Difference betwen the two arrays
*/
function crave_disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

/**
* Remove emoji CDN hostname from DNS prefetching hints.
*
* @param array $urls URLs to print for resource hints.
* @param string $relation_type The relation type the URLs are printed for.
* @return array Difference betwen the two arrays.
*/
function crave_disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' == $relation_type ) {
		/** This filter is documented in wp-includes/formatting.php */
		$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
		$urls = array_diff( $urls, array( $emoji_svg_url ) );
	}
	return $urls;
}

//Disable jQuery migrate
function crave_remove_jquery_migrate( &$scripts) {
	if(!is_admin()) {
		$scripts->remove('jquery');
		$scripts->add('jquery', false, array( 'jquery-core' ), '1.12.4');
	}
}
add_action( 'wp_default_scripts', 'crave_remove_jquery_migrate' );

// remove WordPress version number
function crave_remove_version() {
	return '';
}
add_filter('the_generator', 'crave_remove_version');
remove_action('wp_head', 'wp_generator');

remove_action('wp_head', 'rsd_link'); // remove really simple discovery (RSD) link
remove_action('wp_head', 'wlwmanifest_link'); // remove wlwmanifest.xml (needed to support windows live writer)

remove_action('wp_head', 'feed_links', 2); // remove rss feed links (if you don't use rss)
remove_action('wp_head', 'feed_links_extra', 3); // removes all extra rss feed links

remove_action('wp_head', 'index_rel_link'); // remove link to index page

remove_action('wp_head', 'start_post_rel_link', 10, 0); // remove random post link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // remove parent post link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // remove the next and previous post links
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0 ); // remove shortlink
add_filter( 'revslider_meta_generator', 'remove_revslider_meta_tag' ); //remove rev slider meta
?>
